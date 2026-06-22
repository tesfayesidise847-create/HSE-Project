<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Project;
use App\Notifications\WorkflowNotification;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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

        return view('site-officer.material-requests.create', [
            'projects' => $projects,
            'materials' => $materials,
            'materialsForJs' => $materialsForJs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:2000'],
            'employee_file' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:2048'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        abort_unless($project->isManagedBy($request->user()), 403);

        $employeeFilePath = null;
        if ($request->hasFile('employee_file')) {
            $employeeFilePath = $request->file('employee_file')->store('material-requests/employees', 'public');
        }

        $materialRequest = MaterialRequest::create([
            'material_id' => $validated['material_id'],
            'project_id' => $project->id,
            'requested_by' => $request->user()->id,
            'quantity' => $validated['quantity'],
            'description' => $validated['description'],
            'employee_file' => $employeeFilePath,
            'status' => 'pending',
        ]);

        $material = Material::find($validated['material_id']);

        app(WorkflowNotificationService::class)->notifyRoleUsers(
            ['HSE Officer'],
            new WorkflowNotification(
                category: 'material_request_created',
                title: 'Material request from site',
                message: "{$request->user()->name} requested {$validated['quantity']} of {$material->material_name} for project {$project->project_code}. Review and approve.",
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
            ->with(['material', 'project', 'requester', 'approver'])
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

        $materialRequest->load(['material', 'project', 'requester', 'approver']);

        return view('site-officer.material-requests.show', [
            'request' => $materialRequest,
        ]);
    }
}
