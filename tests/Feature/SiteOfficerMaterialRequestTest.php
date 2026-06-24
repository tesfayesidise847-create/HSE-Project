<?php

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestEmployee;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

function siteOfficerForMaterialRequest(): User
{
    $user = User::factory()->create();
    $user->assignRole('HSE Site Officer');

    return $user;
}

it('allows site officer to submit material request with multiple employees', function () {
    $siteOfficer = siteOfficerForMaterialRequest();

    $project = Project::create([
        'project_name' => 'Site Alpha',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
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

    $project->employees()->sync([$employeeA->id, $employeeB->id]);

    $material = Material::create([
        'material_name' => 'Helmet',
        'material_description' => 'Safety helmet',
        'quantity' => 100,
    ]);

    $this->actingAs($siteOfficer)->post(route('site-officer.material-requests.store'), [
        'project_id' => $project->id,
        'material_id' => $material->id,
        'employee_requests' => [
            ['employee_id' => $employeeA->id, 'quantity' => 2],
            ['employee_id' => $employeeB->id, 'quantity' => 3],
        ],
    ])->assertRedirect(route('site-officer.material-requests.index'));

    $materialRequest = MaterialRequest::first();

    expect($materialRequest)->not->toBeNull();
    expect($materialRequest->quantity)->toBe(5);
    expect(MaterialRequestEmployee::count())->toBe(2);
});

it('creates and attaches a new employee when name is not in project', function () {
    $siteOfficer = siteOfficerForMaterialRequest();

    $project = Project::create([
        'project_name' => 'Site Beta',
        'project_code' => 'SB-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Gloves',
        'material_description' => 'Safety gloves',
        'quantity' => 50,
    ]);

    $this->actingAs($siteOfficer)->post(route('site-officer.material-requests.store'), [
        'project_id' => $project->id,
        'material_id' => $material->id,
        'employee_requests' => [
            ['employee_name' => 'New Worker', 'quantity' => 1],
        ],
    ])->assertRedirect(route('site-officer.material-requests.index'));

    $employee = Employee::where('first_name', 'New')->where('last_name', 'Worker')->first();

    expect($employee)->not->toBeNull();
    expect($project->fresh()->employees->contains($employee))->toBeTrue();
    expect(MaterialRequestEmployee::first()->employee_id)->toBe($employee->id);
});
