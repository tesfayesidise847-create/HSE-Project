<?php

use App\Models\Employee;
use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('shows material balances for all projects and a specific project', function () {
    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Site Gamma',
        'project_code' => 'SG-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $material = Material::create([
        'material_name' => 'Mask',
        'material_description' => 'Safety mask',
        'quantity' => 0,
    ]);

    MaterialProjectAssignment::create([
        'material_id' => $material->id,
        'project_id' => $project->id,
        'quantity' => 12,
        'receiver_id' => $siteOfficer->id,
        'assigned_by' => $officer->id,
    ]);

    $this->actingAs($officer)
        ->get(route('material-reports.index'))
        ->assertOk()
        ->assertSee('Site Gamma')
        ->assertSee('Mask')
        ->assertSee('12');

    $this->actingAs($officer)
        ->get(route('material-reports.show', $project))
        ->assertOk()
        ->assertSee('Total assigned balance')
        ->assertSee('12');
});

it('allows hse officer to add head office quantity', function () {
    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $material = Material::create([
        'material_name' => 'Goggles',
        'material_description' => 'Safety goggles',
        'quantity' => 3,
    ]);

    $this->actingAs($officer)
        ->patch(route('material-quantities.update', $material), [
            'quantity_to_add' => 7,
        ])
        ->assertRedirect(route('material-quantities.index'));

    expect($material->fresh()->quantity)->toBe(10);
});

it('filters and paginates the head office material report', function () {
    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Quarter One Site',
        'project_code' => 'Q1-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    foreach (range(1, 16) as $index) {
        $material = Material::create([
            'material_name' => sprintf('Helmet %02d', $index),
            'material_description' => 'Safety helmet',
            'quantity' => 20,
        ]);

        $assignment = MaterialProjectAssignment::create([
            'material_id' => $material->id,
            'project_id' => $project->id,
            'quantity' => 5,
            'receiver_id' => $siteOfficer->id,
            'assigned_by' => $officer->id,
        ]);

        $assignment->forceFill([
            'created_at' => '2026-02-15 09:00:00',
            'updated_at' => '2026-02-15 09:00:00',
        ])->save();
    }

    $excludedMaterial = Material::create([
        'material_name' => 'Boots',
        'material_description' => 'Safety boots',
        'quantity' => 10,
    ]);

    MaterialProjectAssignment::create([
        'material_id' => $excludedMaterial->id,
        'project_id' => $project->id,
        'quantity' => 3,
        'receiver_id' => $siteOfficer->id,
        'assigned_by' => $officer->id,
    ]);

    $this->actingAs($officer)
        ->get(route('material-reports.head-office', [
            'search' => 'Helmet',
            'project_id' => $project->id,
            'from_date' => '2026-01-01',
            'to_date' => '2026-03-31',
            'quarter' => 1,
        ]))
        ->assertOk()
        ->assertSee('Helmet 01')
        ->assertDontSee('Boots')
        ->assertViewHas('materials', function ($materials): bool {
            return $materials instanceof LengthAwarePaginator
                && $materials->total() === 16
                && $materials->count() === 15;
        });
});

it('filters and paginates the site material report', function () {
    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $siteOfficer = User::factory()->create();
    $siteOfficer->assignRole('HSE Site Officer');

    $project = Project::create([
        'project_name' => 'Quarter Two Site',
        'project_code' => 'Q2-001',
        'site_officer_id' => $siteOfficer->id,
    ]);

    $employee = Employee::create([
        'first_name' => 'Alex',
        'last_name' => 'Reed',
        'gender' => 'Male',
        'job_title' => 'Worker',
    ]);

    foreach (range(1, 16) as $index) {
        $material = Material::create([
            'material_name' => sprintf('Harness %02d', $index),
            'material_description' => 'Fall arrest harness',
            'quantity' => 0,
        ]);

        $assignment = MaterialProjectAssignment::create([
            'material_id' => $material->id,
            'project_id' => $project->id,
            'quantity' => 5,
            'receiver_id' => $siteOfficer->id,
            'assigned_by' => $officer->id,
        ]);

        $assignment->forceFill([
            'created_at' => '2026-05-15 09:00:00',
            'updated_at' => '2026-05-15 09:00:00',
        ])->save();

        MaterialEmployeeAssignment::create([
            'material_id' => $material->id,
            'project_id' => $project->id,
            'employee_id' => $employee->id,
            'quantity' => 2,
            'assigned_date' => '2026-05-20',
            'assigned_by' => $siteOfficer->id,
        ]);
    }

    $excludedMaterial = Material::create([
        'material_name' => 'Gloves',
        'material_description' => 'Safety gloves',
        'quantity' => 0,
    ]);

    MaterialProjectAssignment::create([
        'material_id' => $excludedMaterial->id,
        'project_id' => $project->id,
        'quantity' => 5,
        'receiver_id' => $siteOfficer->id,
        'assigned_by' => $officer->id,
    ]);

    $this->actingAs($officer)
        ->get(route('material-reports.site', [
            'search' => 'Harness',
            'project_id' => $project->id,
            'from_date' => '2026-04-01',
            'to_date' => '2026-06-30',
            'quarter' => 2,
        ]))
        ->assertOk()
        ->assertSee('Harness 01')
        ->assertSee('Q2-001')
        ->assertDontSee('Gloves')
        ->assertViewHas('materials', function ($materials): bool {
            return $materials instanceof LengthAwarePaginator
                && $materials->total() === 16
                && $materials->count() === 15;
        });
});
