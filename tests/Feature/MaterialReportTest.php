<?php

use App\Models\Material;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;

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
