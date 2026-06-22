<?php

use App\Models\Material;
use App\Models\UnitOfMeasure;
use App\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('allows hse officer to view material history', function () {
    $uom = UnitOfMeasure::firstOrCreate(['name' => 'Pcs']);

    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $material = Material::create([
        'material_name' => 'Gloves',
        'material_description' => 'Protective gloves',
        'quantity' => 10,
        'unit_of_measure_id' => $uom->id,
    ]);

    $material->recordHistory('created', 10, 'Initial balance entered.', $officer->id);

    $this->actingAs($officer)
        ->get(route('material-histories.index'))
        ->assertOk()
        ->assertSee('Material History')
        ->assertSee('Gloves')
        ->assertSee('Initial balance entered.');
});

it('denies non hse officer from viewing material history', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user)
        ->get(route('material-histories.index'))
        ->assertForbidden();
});

it('exports material history to excel and pdf', function () {
    $uom = UnitOfMeasure::firstOrCreate(['name' => 'Pcs']);

    $officer = User::factory()->create();
    $officer->assignRole('HSE Officer');

    $material = Material::create([
        'material_name' => 'Boots',
        'material_description' => 'Safety boots',
        'quantity' => 20,
        'unit_of_measure_id' => $uom->id,
    ]);

    $material->recordHistory('created', 20, 'Initial stock created.', $officer->id);

    $this->actingAs($officer)
        ->get(route('material-histories.export', ['format' => 'xlsx']))
        ->assertOk()
        ->assertHeaderContains('content-type', 'application/vnd.ms-excel');

    $this->actingAs($officer)
        ->get(route('material-histories.export', ['format' => 'pdf']))
        ->assertOk()
        ->assertHeaderContains('content-type', 'application/pdf');
});
