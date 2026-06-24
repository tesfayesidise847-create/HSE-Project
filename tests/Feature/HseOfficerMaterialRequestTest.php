<?php

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestEmployee;
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

function createMaterialRequestWithEmployees(
    Project $project,
    Material $material,
    User $siteOfficer,
    array $employeeRows,
    ?int $quantity = null,
): MaterialRequest {
    $materialRequest = MaterialRequest::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'requested_by' => $siteOfficer->id,
        'quantity' => $quantity ?? collect($employeeRows)->sum('quantity'),
        'status' => 'pending',
    ]);

    foreach ($employeeRows as $row) {
        MaterialRequestEmployee::create([
            'material_request_id' => $materialRequest->id,
            'employee_id' => $row['employee_id'],
            'quantity' => $row['quantity'],
        ]);
    }

    return $materialRequest;
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

    $employee = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    createMaterialRequestWithEmployees($project, $material, $siteOfficer, [
        ['employee_id' => $employee->id, 'quantity' => 10],
    ]);

    $this->actingAs($hseOfficer)
        ->get(route('hse-officer.material-requests.index'))
        ->assertOk()
        ->assertSee('Helmet')
        ->assertSee('View');
});

it('allows hse officer to approve all requested employees and assign materials', function () {
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

    $employeeA = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    $employeeB = Employee::create([
        'first_name' => 'Tom',
        'last_name' => 'Lee',
        'gender' => 'Male',
        'job_title' => 'Worker',
    ]);

    $materialRequest = createMaterialRequestWithEmployees($project, $material, $siteOfficer, [
        ['employee_id' => $employeeA->id, 'quantity' => 4],
        ['employee_id' => $employeeB->id, 'quantity' => 6],
    ], 10);

    $response = $this->actingAs($hseOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest), [
            'approve_mode' => 'all',
        ]);

    $response->assertRedirect(route('hse-officer.material-requests.index'));
    expect($materialRequest->fresh()->isApproved())->toBeTrue();
    expect($materialRequest->fresh()->approved_by)->toBe($hseOfficer->id);
    expect($material->fresh()->quantity)->toBe(90);

    $assignment = MaterialProjectAssignment::first();
    expect($assignment)->not->toBeNull();
    expect($assignment->quantity)->toBe(10);

    expect(MaterialEmployeeAssignment::count())->toBe(2);
    expect(MaterialEmployeeAssignment::where('employee_id', $employeeA->id)->first()->quantity)->toBe(4);
    expect(MaterialEmployeeAssignment::where('employee_id', $employeeB->id)->first()->quantity)->toBe(6);
});

it('allows hse officer to partially approve selected employees', function () {
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

    $employeeA = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    $employeeB = Employee::create([
        'first_name' => 'Tom',
        'last_name' => 'Lee',
        'gender' => 'Male',
        'job_title' => 'Worker',
    ]);

    $materialRequest = createMaterialRequestWithEmployees($project, $material, $siteOfficer, [
        ['employee_id' => $employeeA->id, 'quantity' => 4],
        ['employee_id' => $employeeB->id, 'quantity' => 6],
    ], 10);

    $this->actingAs($hseOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest), [
            'approve_mode' => 'selected',
            'employee_ids' => [$employeeA->id],
        ])
        ->assertRedirect(route('hse-officer.material-requests.index'));

    expect($materialRequest->fresh()->isPartiallyApproved())->toBeTrue();
    expect($material->fresh()->quantity)->toBe(96);
    expect(MaterialEmployeeAssignment::count())->toBe(1);
    expect(MaterialEmployeeAssignment::first()->employee_id)->toBe($employeeA->id);

    $this->actingAs($hseOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest), [
            'approve_mode' => 'selected',
            'employee_ids' => [$employeeB->id],
        ])
        ->assertRedirect(route('hse-officer.material-requests.index'));

    expect($materialRequest->fresh()->isApproved())->toBeTrue();
    expect(MaterialEmployeeAssignment::count())->toBe(2);
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

    $employee = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    $materialRequest = createMaterialRequestWithEmployees($project, $material, $siteOfficer, [
        ['employee_id' => $employee->id, 'quantity' => 10],
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

    $employee = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    $materialRequest = createMaterialRequestWithEmployees($project, $material, $siteOfficer, [
        ['employee_id' => $employee->id, 'quantity' => 10],
    ]);

    $this->actingAs($nonOfficer)
        ->post(route('hse-officer.material-requests.approve', $materialRequest), [
            'approve_mode' => 'all',
        ])
        ->assertForbidden();

    $this->actingAs($nonOfficer)
        ->post(route('hse-officer.material-requests.reject', $materialRequest), [
            'rejection_reason' => 'Reason',
        ])
        ->assertForbidden();
});
