<?php

namespace App\Http\Controllers;

use App\MaterialInventoryReportService;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MaterialReportController extends Controller
{
    public function __construct(private MaterialInventoryReportService $inventoryReport) {}

    public function inventory(): View
    {
        $report = $this->inventoryReport->forHeadOffice();

        return view('material-reports.inventory', [
            'summary' => $report['summary'],
            'materials' => $report['materials'],
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

    /**
     * @param  Collection<int, MaterialProjectAssignment>  $assignments
     * @return array<int, Collection<int, array{material: \App\Models\Material, total_quantity: int}>>
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
