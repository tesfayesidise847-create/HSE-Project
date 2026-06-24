<?php

use App\Models\Material;
use App\Models\MaterialProjectAssignment;
use App\Models\MaterialRequest;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

function requestTestHseOfficer(): User
{
    $user = User::factory()->create();
    $user->assignRole('HSE Officer');

    return $user;
}

function requestTestSiteOfficer(): User
{
    $user = User::factory()->create();
    $user->assignRole('HSE Site Officer');

    return $user;
}

it('allows hse officer to view material requests index', function () {
    $hseOfficer = requestTestHseOfficer();
    $siteOfficer = requestTestSiteOfficer();

    $project = Project::create([
        'project_name' => 'Site Alpha',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Helmet',
        'material_description' => 'Safety helmet',
        'quantity' => 100,
    ]);

    $materialRequest = MaterialRequest::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'requested_by' => $siteOfficer->id,
        'quantity' => 10,
        'description' => 'Need 10 helmets for new employees',
        'status' => 'pending',
    ]);

    $this->actingAs($hseOfficer)
        ->get(route('hse-officer.material-requests.index'))
        ->assertOk()
        ->assertSee('Helmet')
        ->assertSee('Need 10 helmets for new employees')
        ->assertSee('View');
});

it('allows hse officer to approve a pending material request', function () {
    $hseOfficer = requestTestHseOfficer();
    $siteOfficer = requestTestSiteOfficer();

    $project = Project::create([
        'project_name' => 'Site Alpha',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Helmet',
        'material_description' => 'Safety helmet',
        'quantity' => 100,
    ]);

    $materialRequest = MaterialRequest::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'requested_by' => $siteOfficer->id,
        'quantity' => 10,
        'description' => 'Need 10 helmets for new employees',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($hseOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest));

    $response->assertRedirect(route('hse-officer.material-requests.index'));
    expect($materialRequest->fresh()->isApproved())->toBeTrue();
    expect($materialRequest->fresh()->approved_by)->toBe($hseOfficer->id);
    expect($material->fresh()->quantity)->toBe(90);

    $assignment = MaterialProjectAssignment::first();
    expect($assignment)->not->toBeNull();
    expect($assignment->material_id)->toBe($material->id);
    expect($assignment->project_id)->toBe($project->id);
    expect($assignment->quantity)->toBe(10);
    expect($assignment->receiver_id)->toBe($siteOfficer->id);
    expect($assignment->assigned_by)->toBe($hseOfficer->id);
});

it('allows hse officer to reject a pending material request with reason', function () {
    $hseOfficer = requestTestHseOfficer();
    $siteOfficer = requestTestSiteOfficer();

    $project = Project::create([
        'project_name' => 'Site Alpha',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Helmet',
        'material_description' => 'Safety helmet',
        'quantity' => 100,
    ]);

    $materialRequest = MaterialRequest::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'requested_by' => $siteOfficer->id,
        'quantity' => 10,
        'description' => 'Need 10 helmets for new employees',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($hseOfficer)
        ->post(route('hse-officer.material-requests.reject', $materialRequest), [
            'rejection_reason' => 'Duplicate request',
        ]);

    $response->assertRedirect(route('hse-officer.material-requests.index'));
    expect($materialRequest->fresh()->isRejected())->toBeTrue();
    expect($materialRequest->fresh()->rejection_reason)->toBe('Duplicate request');
    expect($material->fresh()->quantity)->toBe(100);
});

it('denies non-hse-officers from approving or rejecting material requests', function () {
    $nonOfficer = User::factory()->create();
    $nonOfficer->assignRole('HSE Site Officer');

    $siteOfficer = requestTestSiteOfficer();

    $project = Project::create([
        'project_name' => 'Site Alpha',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Helmet',
        'material_description' => 'Safety helmet',
        'quantity' => 100,
    ]);

    $materialRequest = MaterialRequest::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'requested_by' => $siteOfficer->id,
        'quantity' => 10,
        'description' => 'Need 10 helmets',
        'status' => 'pending',
    ]);

    $this->actingAs($nonOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest))
        ->assertForbidden();

    $this->actingAs($nonOfficer)
        ->post(route('hse-officer.material-requests.reject', $materialRequest), [
            'rejection_reason' => 'Reason',
        ])
        ->assertForbidden();
});
