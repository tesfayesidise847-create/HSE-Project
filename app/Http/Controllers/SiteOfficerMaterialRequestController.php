<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestEmployee;
use App\Models\Project;
use App\Notifications\WorkflowNotification;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SiteOfficerMaterialRequestController extends Controller
{
    public function create(Request $request): View
    {
        $projects = Project::query()
            ->where('site_officer_id', $request->user()->id)
            ->orderBy('project_name')
            ->get();

        $materials = Material::query()
            ->where('quantity', '>', 0)
            ->with('unitOfMeasure')
            ->orderBy('material_name')
            ->get();

        $materialsForJs = $materials->map(fn (Material $material): array => [
            'id' => $material->id,
            'label' => $material->material_name.' (Available: '.$material->quantity.' '.($material->unitOfMeasure?->name ?? 'units').')',
            'search' => strtolower($material->material_name.' '.($material->unitOfMeasure?->name ?? '')),
        ])->values()->all();

        $employeesByProjectForJs = $projects
            ->load('employees')
            ->mapWithKeys(function (Project $project): array {
                $employees = $project->employees->map(fn (Employee $employee): array => [
                    'id' => $employee->id,
                    'label' => $employee->fullName().' — '.$employee->job_title,
                    'search' => strtolower($employee->fullName().' '.$employee->job_title),
                    'name' => $employee->fullName(),
                ])->values()->all();

                return [$project->id => $employees];
            })->all();

        return view('site-officer.material-requests.create', [
            'projects' => $projects,
            'materials' => $materials,
            'materialsForJs' => $materialsForJs,
            'employeesByProjectForJs' => $employeesByProjectForJs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'employee_requests' => ['required', 'array', 'min:1'],
            'employee_requests.*.employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'employee_requests.*.employee_name' => ['nullable', 'string', 'max:255'],
            'employee_requests.*.quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        abort_unless($project->isManagedBy($request->user()), 403);

        $employeeRows = collect($validated['employee_requests'])
            ->filter(fn (array $row): bool => filled($row['employee_id'] ?? null) || filled($row['employee_name'] ?? null))
            ->values();

        if ($employeeRows->isEmpty()) {
            throw ValidationException::withMessages([
                'employee_requests' => 'Add at least one employee row.',
            ]);
        }

        $resolvedRows = $this->resolveEmployeeRows($project, $employeeRows);

        $totalQuantity = $resolvedRows->sum(fn (array $row): int => $row['quantity']);

        $materialRequest = DB::transaction(function () use ($validated, $project, $request, $totalQuantity, $resolvedRows): MaterialRequest {
            $materialRequest = MaterialRequest::create([
                'material_id' => $validated['material_id'],
                'project_id' => $project->id,
                'requested_by' => $request->user()->id,
                'quantity' => $totalQuantity,
                'description' => $validated['description'] ?? null,
                'employee_file' => null,
                'status' => 'pending',
            ]);

            foreach ($resolvedRows as $row) {
                MaterialRequestEmployee::create([
                    'material_request_id' => $materialRequest->id,
                    'employee_id' => $row['employee_id'],
                    'quantity' => $row['quantity'],
                ]);
            }

            return $materialRequest;
        });

        $material = Material::find($validated['material_id']);

        app(WorkflowNotificationService::class)->notifyRoleUsers(
            ['HSE Officer'],
            new WorkflowNotification(
                category: 'material_request_created',
                title: 'Material request from site',
                message: "{$request->user()->name} requested {$totalQuantity} of {$material->material_name} for project {$project->project_code}. Review and approve.",
                actionUrl: route('hse-officer.material-requests.index'),
            ),
        );

        return Redirect::route('site-officer.material-requests.index')
            ->with('success', 'Material request submitted for approval successfully.');
    }

    public function index(Request $request): View
    {
        $projectIds = Project::query()
            ->where('site_officer_id', $request->user()->id)
            ->pluck('id');

        $requests = MaterialRequest::query()
            ->whereIn('project_id', $projectIds)
            ->with(['material', 'project', 'requester', 'approver', 'requestedEmployees'])
            ->latest()
            ->get();

        return view('site-officer.material-requests.index', [
            'requests' => $requests,
        ]);
    }

    public function show(Request $request, MaterialRequest $materialRequest): View
    {
        $projectIds = Project::query()
            ->where('site_officer_id', $request->user()->id)
            ->pluck('id');

        abort_unless($projectIds->contains($materialRequest->project_id), 403);

        $materialRequest->load(['material', 'project', 'requester', 'approver', 'requestedEmployees.employee']);

        return view('site-officer.material-requests.show', [
            'request' => $materialRequest,
        ]);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return Collection<int, array{employee_id:int,quantity:int}>
     */
    private function resolveEmployeeRows(Project $project, Collection $rows): Collection
    {
        $projectEmployees = $project->employees()->get()->keyBy('id');

        return $rows->map(function (array $row) use ($project, &$projectEmployees): array {
            $employeeId = isset($row['employee_id']) ? (int) $row['employee_id'] : null;
            $quantity = (int) $row['quantity'];

            if ($employeeId !== null) {
                if (! $projectEmployees->has($employeeId)) {
                    throw ValidationException::withMessages([
                        'employee_requests' => 'Selected employee must belong to the selected project.',
                    ]);
                }

                return [
                    'employee_id' => $employeeId,
                    'quantity' => $quantity,
                ];
            }

            $employeeName = trim((string) ($row['employee_name'] ?? ''));
            if ($employeeName === '') {
                throw ValidationException::withMessages([
                    'employee_requests' => 'Employee name is required for new employees.',
                ]);
            }

            $employee = $projectEmployees->first(function (Employee $employee) use ($employeeName): bool {
                return strcasecmp($employee->fullName(), $employeeName) === 0;
            });

            if (! $employee) {
                [$firstName, $lastName] = $this->splitName($employeeName);

                $employee = Employee::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => 'Not specified',
                    'job_title' => 'Worker',
                ]);

                $project->employees()->syncWithoutDetaching([$employee->id]);
                $projectEmployees->put($employee->id, $employee);
            }

            return [
                'employee_id' => $employee->id,
                'quantity' => $quantity,
            ];
        });
    }

    /**
     * @return array{0:string,1:string}
     */
    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        if (count($parts) < 2) {
            return [$name, 'Unknown'];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [$firstName ?: $name, $lastName ?: 'Unknown'];
    }
}
