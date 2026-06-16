<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\Project;
use App\Services\WorkflowNotificationService;
use App\SiteMaterialBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SiteOfficerEmployeeAssignmentController extends Controller
{
    public function __construct(private SiteMaterialBalanceService $balanceService) {}

    public function create(Request $request): View
    {
        $projects = Project::query()
            ->when(! $request->user()->hasAnyRole(['Admin', 'HSE Officer']), function ($query) use ($request) {
                return $query->where('site_officer_id', $request->user()->id);
            })
            ->orderBy('project_name')
            ->get();

        $employees = Employee::orderBy('first_name')->orderBy('last_name')->get();

        $balancesForJs = $projects->mapWithKeys(function (Project $project): array {
            $balances = $this->balanceService->balancesForProject($project)
                ->filter(fn (array $row): bool => $row['available'] > 0)
                ->map(fn (array $row): array => [
                    'material_id' => $row['material']->id,
                    'label' => $row['material']->material_name.' (Available: '.$row['available'].')',
                ])
                ->values()
                ->all();

            return [$project->id => $balances];
        })->all();

        $employeesForJs = $employees->map(fn (Employee $employee): array => [
            'id' => $employee->id,
            'label' => $employee->first_name.' '.$employee->last_name.' — '.$employee->job_title,
            'search' => strtolower($employee->first_name.' '.$employee->last_name.' '.$employee->job_title),
        ])->values()->all();

        return view('site-officer.employee-assignments.create', [
            'projects' => $projects,
            'balancesForJs' => $balancesForJs,
            'employeesForJs' => $employeesForJs,
            'defaultProjectId' => old('project_id', request('project_id', '')),
            'employeeHistoryUrlTemplate' => route('site-officer.employees.assignment-history', ['employee' => '__EMPLOYEE__']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.material_id' => ['required', 'exists:materials,id'],
            'assignments.*.quantity' => ['required', 'integer', 'min:1'],
            'assignments.*.employee_id' => ['required', 'exists:employees,id'],
            'assignments.*.assigned_date' => ['required', 'date'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        abort_unless($project->isManagedBy($request->user()), 403);

        $assignments = collect($validated['assignments'])
            ->filter(fn (array $row): bool => filled($row['material_id'] ?? null))
            ->values();

        if ($assignments->isEmpty()) {
            throw ValidationException::withMessages([
                'assignments' => 'Add at least one material assignment.',
            ]);
        }

        $requestedByMaterial = $assignments->groupBy('material_id')->map(
            fn ($rows) => $rows->sum(fn (array $row): int => (int) $row['quantity'])
        );

        foreach ($requestedByMaterial as $materialId => $totalQuantity) {
            $available = $this->balanceService->availableQuantity($project, (int) $materialId);

            if ($available < $totalQuantity) {
                throw ValidationException::withMessages([
                    'assignments' => 'Insufficient site balance for one or more materials.',
                ]);
            }
        }

        DB::transaction(function () use ($assignments, $project, $request): void {
            foreach ($assignments as $row) {
                MaterialEmployeeAssignment::create([
                    'material_id' => $row['material_id'],
                    'project_id' => $project->id,
                    'employee_id' => $row['employee_id'],
                    'quantity' => $row['quantity'],
                    'assigned_date' => $row['assigned_date'],
                    'assigned_by' => $request->user()->id,
                ]);
            }
        });

        $materials = Material::query()->whereIn('id', $assignments->pluck('material_id'))->get()->keyBy('id');
        $employees = Employee::query()->whereIn('id', $assignments->pluck('employee_id'))->get()->keyBy('id');

        $assignmentDetails = $assignments
            ->map(fn (array $row): array => [
                'material' => $materials->get($row['material_id']),
                'employee' => $employees->get($row['employee_id']),
                'quantity' => (int) $row['quantity'],
            ])
            ->filter(fn (array $row): bool => $row['material'] !== null && $row['employee'] !== null);

        app(WorkflowNotificationService::class)->materialsAssignedToEmployees(
            $project,
            $assignmentDetails,
            $request->user(),
        );

        return Redirect::route('site-officer.projects.show', $project)
            ->with('success', 'Materials assigned to employees successfully.');
    }

    public function index(Request $request): View
    {
        $projectIds = Project::query()
            ->when(! $request->user()->hasAnyRole(['Admin', 'HSE Officer']), function ($query) use ($request) {
                return $query->where('site_officer_id', $request->user()->id);
            })
            ->pluck('id');

        $assignments = MaterialEmployeeAssignment::query()
            ->whereIn('project_id', $projectIds)
            ->with(['material', 'project', 'employee'])
            ->latest()
            ->paginate(15);

        return view('site-officer.employee-assignments.index', [
            'assignments' => $assignments,
            'employees' => Employee::orderBy('first_name')->orderBy('last_name')->get(),
            'employeeHistoryUrlTemplate' => route('site-officer.employees.assignment-history', ['employee' => '__EMPLOYEE__']),
        ]);
    }

    public function employeeHistory(Request $request, Employee $employee): JsonResponse
    {
        $projectIds = Project::query()
            ->when(! $request->user()->hasAnyRole(['Admin', 'HSE Officer']), function ($query) use ($request) {
                return $query->where('site_officer_id', $request->user()->id);
            })
            ->pluck('id');

        $assignments = MaterialEmployeeAssignment::query()
            ->where('employee_id', $employee->id)
            ->whereIn('project_id', $projectIds)
            ->with(['material', 'project'])
            ->orderByDesc('assigned_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (MaterialEmployeeAssignment $assignment): array => [
                'date' => $assignment->assigned_date->format('M d, Y'),
                'project_code' => $assignment->project->project_code,
                'project_name' => $assignment->project->project_name,
                'material' => $assignment->material->material_name,
                'quantity' => $assignment->quantity,
            ]);

        return response()->json([
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->fullName(),
                'job_title' => $employee->job_title,
            ],
            'assignments' => $assignments,
        ]);
    }
}
