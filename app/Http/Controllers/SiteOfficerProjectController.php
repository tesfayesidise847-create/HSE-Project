<?php

namespace App\Http\Controllers;

use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use App\SiteMaterialBalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteOfficerProjectController extends Controller
{
    public function __construct(private SiteMaterialBalanceService $balanceService) {}

    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('projects.index');
    }

    public function show(Request $request, Project $project): View
    {
        $this->authorizeProject($request, $project);

        $balances = $this->balanceService->balancesForProject($project);

        $incomingHistory = MaterialProjectAssignment::query()
            ->where('project_id', $project->id)
            ->with(['material', 'assignedBy'])
            ->latest()
            ->get();

        $employeeAssignments = MaterialEmployeeAssignment::query()
            ->where('project_id', $project->id)
            ->with(['material', 'employee'])
            ->latest()
            ->get();

        return view('site-officer.projects.show', [
            'project' => $project,
            'balances' => $balances,
            'incomingHistory' => $incomingHistory,
            'employeeAssignments' => $employeeAssignments,
        ]);
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        abort_unless($project->isManagedBy($request->user()), 403);
    }
}
