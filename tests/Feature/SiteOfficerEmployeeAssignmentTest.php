<?php

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('allows site officer to assign materials to employees from site balance', function () {
    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Site A',
        'project_code' => 'SA-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Mask',
        'material_description' => 'Mask',
        'quantity' => 0,
    ]);

    MaterialProjectAssignment::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'quantity' => 15,
        'receiver_id' => $siteOfficer->id,
        'assigned_by' => User::factory()->create()->id,
    ]);

    $employee = Employee::create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'gender' => 'Female',
        'job_title' => 'Worker',
    ]);

    $this->actingAs($siteOfficer)->post(route('site-officer.employee-assignments.store'), [
        'project_id' => $project->id,
        'assignments' => [
            [
                'material_id' => $material->id,
                'quantity' => 5,
                'employee_id' => $employee->id,
                'assigned_date' => '2026-06-01',
            ],
        ],
    ])->assertRedirect(route('site-officer.projects.show', $project));

    $assignment = MaterialEmployeeAssignment::first();

    expect($assignment)->not->toBeNull();
    expect($assignment->quantity)->toBe(5);
    expect($assignment->employee_id)->toBe($employee->id);
    expect($assignment->assigned_date->format('Y-m-d'))->toBe('2026-06-01');
});

it('rejects assignment when site balance is insufficient', function () {
    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Site B',
        'project_code' => 'SB-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Vest',
        'material_description' => 'Vest',
        'quantity' => 0,
    ]);

    MaterialProjectAssignment::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'quantity' => 2,
        'receiver_id' => $siteOfficer->id,
        'assigned_by' => User::factory()->create()->id,
    ]);

    $employee = Employee::create([
        'first_name' => 'Tom',
        'last_name' => 'Lee',
        'gender' => 'Male',
        'job_title' => 'Worker',
    ]);

    $this->actingAs($siteOfficer)->post(route('site-officer.employee-assignments.store'), [
        'project_id' => $project->id,
        'assignments' => [
            [
                'material_id' => $material->id,
                'quantity' => 10,
                'employee_id' => $employee->id,
                'assigned_date' => now()->toDateString(),
            ],
        ],
    ])->assertSessionHasErrors('assignments');

    expect(MaterialEmployeeAssignment::count())->toBe(0);
});

it('denies non site officers from employee assignment routes', function () {
    $user = User::factory()->create();
    $user->assignRole('Employee');

    $this->actingAs($user)->get(route('site-officer.employee-assignments.create'))->assertForbidden();
});

it('returns employee assignment history for site officer projects', function () {
    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Site C',
        'project_code' => 'SC-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Boots',
        'material_description' => 'Boots',
        'quantity' => 0,
    ]);

    $employee = Employee::create([
        'first_name' => 'Sam',
        'last_name' => 'Ray',
        'gender' => 'Male',
        'job_title' => 'Worker',
    ]);

    MaterialEmployeeAssignment::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'employee_id' => $employee->id,
        'quantity' => 4,
        'assigned_date' => '2026-06-01',
        'assigned_by' => $siteOfficer->id,
    ]);

    $this->actingAs($siteOfficer)
        ->getJson(route('site-officer.employees.assignment-history', $employee))
        ->assertOk()
        ->assertJsonPath('employee.name', 'Sam Ray')
        ->assertJsonPath('assignments.0.material', 'Boots')
        ->assertJsonPath('assignments.0.quantity', 4)
        ->assertJsonPath('assignments.0.project_code', 'SC-001');
});
