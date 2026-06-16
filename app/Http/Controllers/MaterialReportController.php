<?php

namespace App\Http\Controllers;

use App\MaterialInventoryReportService;
use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MaterialReportController extends Controller
{
    public function __construct(private MaterialInventoryReportService $inventoryReport) {}

    public function inventory(Request $request): View
    {
        $report = $this->inventoryReport->forHeadOffice(
            search: $request->get('search'),
            fromDate: $request->get('from_date'),
            toDate: $request->get('to_date'),
            quarter: $request->get('quarter'),
            projectId: $request->get('project_id'),
        );

        $projects = Project::query()
            ->orderBy('project_name')
            ->get();

        return view('material-reports.inventory', [
            'summary' => $report['summary'],
            'materials' => $report['materials'],
            'projects' => $projects,
        ]);
    }

    public function index(): View
    {
        $projects = Project::with('siteOfficer')
            ->orderBy('project_name')
            ->get();

        $balancesByProject = $this->balancesGroupedByProject(
            MaterialProjectAssignment::query()
                ->with(['material', 'receiver'])
                ->get()
        );

        return view('material-reports.index', [
            'projects' => $projects,
            'balancesByProject' => $balancesByProject,
        ]);
    }

    public function show(Project $project): View
    {
        $project->load('siteOfficer');

        $balances = MaterialProjectAssignment::query()
            ->where('project_id', $project->id)
            ->with(['material', 'receiver', 'assignedBy'])
            ->latest()
            ->get()
            ->groupBy('material_id')
            ->map(function (Collection $assignments): array {
                $first = $assignments->first();

                return [
                    'material' => $first->material,
                    'total_quantity' => $assignments->sum('quantity'),
                    'assignments' => $assignments,
                ];
            })
            ->values();

        return view('material-reports.show', [
            'project' => $project,
            'balances' => $balances,
        ]);
    }

    public function balance(Request $request): View
    {
        $materials = $this->getMaterialBalances();

        // If a specific material is requested, get its breakdown
        $selectedMaterial = null;
        $selectedMaterialBreakdown = null;

        if ($request->get('material_id')) {
            $selectedMaterial = Material::find($request->get('material_id'));
            if ($selectedMaterial) {
                $selectedMaterialBreakdown = $this->getMaterialBreakdown($selectedMaterial);
            }
        }

        return view('material-reports.balance', [
            'materials' => $materials,
            'selectedMaterial' => $selectedMaterial,
            'selectedMaterialBreakdown' => $selectedMaterialBreakdown,
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getMaterialBalances(): array
    {
        $materials = Material::with(['unitOfMeasure', 'assignments'])
            ->orderBy('material_name')
            ->get();

        return $materials->map(function ($material) {
            $projectAssignments = MaterialProjectAssignment::query()
                ->where('material_id', $material->id)
                ->sum('quantity');

            $employeeAssignments = MaterialEmployeeAssignment::query()
                ->where('material_id', $material->id)
                ->sum('quantity');

            $headOfficeBalance = $material->quantity;
            $siteBalance = max(0, $projectAssignments - $employeeAssignments);
            $totalBalance = $headOfficeBalance + $siteBalance;

            return [
                'id' => $material->id,
                'name' => $material->material_name,
                'description' => $material->material_description,
                'unit_of_measure' => $material->unitOfMeasure?->name,
                'head_office_balance' => $headOfficeBalance,
                'site_balance' => $siteBalance,
                'total_balance' => $totalBalance,
                'assigned_to_projects' => $projectAssignments,
                'assigned_to_employees' => $employeeAssignments,
            ];
        })->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function getMaterialBreakdown(Material $material): array
    {
        $projects = Project::with('siteOfficer')
            ->orderBy('project_name')
            ->get();

        $projectBreakdown = $projects->map(function ($project) use ($material) {
            $projectAssigned = MaterialProjectAssignment::query()
                ->where('material_id', $material->id)
                ->where('project_id', $project->id)
                ->sum('quantity');

            $employeeAssigned = MaterialEmployeeAssignment::query()
                ->where('material_id', $material->id)
                ->where('project_id', $project->id)
                ->sum('quantity');

            if ($projectAssigned === 0) {
                return null;
            }

            return [
                'project_id' => $project->id,
                'project_name' => $project->project_name,
                'project_code' => $project->project_code,
                'site_officer' => $project->siteOfficer?->name,
                'assigned_to_project' => $projectAssigned,
                'assigned_to_employees' => $employeeAssigned,
                'available_balance' => max(0, $projectAssigned - $employeeAssigned),
            ];
        })->filter()->values();

        $totalProjectAssigned = MaterialProjectAssignment::query()
            ->where('material_id', $material->id)
            ->sum('quantity');

        $totalEmployeeAssigned = MaterialEmployeeAssignment::query()
            ->where('material_id', $material->id)
            ->sum('quantity');

        return [
            'material_id' => $material->id,
            'material_name' => $material->material_name,
            'material_description' => $material->material_description,
            'unit_of_measure' => $material->unitOfMeasure?->name,
            'head_office_balance' => $material->quantity,
            'total_assigned_to_projects' => $totalProjectAssigned,
            'total_assigned_to_employees' => $totalEmployeeAssigned,
            'total_site_balance' => max(0, $totalProjectAssigned - $totalEmployeeAssigned),
            'projects' => $projectBreakdown->all(),
        ];
    }

    /**
     * @param  Collection<int, MaterialProjectAssignment>  $assignments
     * @return array<int, Collection<int, array{material: Material, total_quantity: int}>>
     */
    private function balancesGroupedByProject(Collection $assignments): array
    {
        return $assignments
            ->groupBy('project_id')
            ->map(function (Collection $projectAssignments): Collection {
                return $projectAssignments
                    ->groupBy('material_id')
                    ->map(function (Collection $materialAssignments): array {
                        $first = $materialAssignments->first();

                        return [
                            'material' => $first->material,
                            'total_quantity' => $materialAssignments->sum('quantity'),
                        ];
                    })
                    ->values();
            })
            ->all();
    }
}
