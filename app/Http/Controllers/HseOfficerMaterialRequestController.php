<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialProjectAssignment;
use App\Models\MaterialRequest;
use App\Models\Project;
use App\Notifications\WorkflowNotification;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HseOfficerMaterialRequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = MaterialRequest::query()
            ->with(['material', 'project', 'requester', 'approver'])
            ->latest()
            ->get();

        return view('hse-officer.material-requests.index', [
            'requests' => $requests,
        ]);
    }

    public function approve(Request $request, MaterialRequest $materialRequest): RedirectResponse
    {
        if (! $materialRequest->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'This request has already been processed.',
            ]);
        }

        $materialRequest->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        $project = $materialRequest->project;
        $material = $materialRequest->material;

        // Check if material has enough quantity at head office
        if (! $material->hasAvailableQuantity($materialRequest->quantity)) {
            throw ValidationException::withMessages([
                'quantity' => "Insufficient head office balance for {$material->material_name}. Available: {$material->quantity}.",
            ]);
        }

        DB::transaction(function () use ($materialRequest, $project, $material, $request): void {
            // Deduct from head office
            $material->deductQuantity($materialRequest->quantity);
            $material->recordHistory(
                'assigned_to_project',
                -$materialRequest->quantity,
                "Material request approved - assigned to project {$project->project_code}. Description: {$materialRequest->description}",
                $request->user()->id,
            );

            // Create a project assignment record
            MaterialProjectAssignment::create([
                'material_id' => $material->id,
                'project_id' => $project->id,
                'quantity' => $materialRequest->quantity,
                'receiver_id' => $project->site_officer_id,
                'assigned_by' => $request->user()->id,
            ]);

            // If employee file was uploaded, we need to distribute to employees
            // but for now we just mark it as approved - the site officer can distribute later
        });

        app(WorkflowNotificationService::class)->notifyUser(
            $materialRequest->requester,
            new WorkflowNotification(
                category: 'material_request_approved',
                title: 'Material request approved',
                message: "Your request for {$materialRequest->quantity} of {$material->material_name} on project {$project->project_code} has been approved by {$request->user()->name}.",
                actionUrl: route('site-officer.material-requests.index'),
            ),
        );

        return Redirect::route('hse-officer.material-requests.index')
            ->with('success', "Material request for {$material->material_name} approved successfully.");
    }

    public function reject(Request $request, MaterialRequest $materialRequest): RedirectResponse
    {
        if (! $materialRequest->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'This request has already been processed.',
            ]);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $materialRequest->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $material = $materialRequest->material;
        $project = $materialRequest->project;

        app(WorkflowNotificationService::class)->notifyUser(
            $materialRequest->requester,
            new WorkflowNotification(
                category: 'material_request_rejected',
                title: 'Material request rejected',
                message: "Your request for {$materialRequest->quantity} of {$material->material_name} on project {$project->project_code} was rejected by {$request->user()->name}. Reason: {$validated['rejection_reason']}",
                actionUrl: route('site-officer.material-requests.index'),
            ),
        );

        return Redirect::route('hse-officer.material-requests.index')
            ->with('success', "Material request for {$material->material_name} rejected.");
    }
}
