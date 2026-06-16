<?php

use App\Models\Project;
use App\Models\User;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('allows hse officers to create a project for a site officer', function () {
    $hseOfficer = User::factory()->create();
    $hseOfficer->assignRole('HSE Officer');

    $siteOfficer = User::factory()->create([
        'name' => 'Supply Site Officer',
        'email' => 'site-officer@example.com',
    ]);
    $siteOfficer->assignRole('HSE Site Officer');

    $this->actingAs($hseOfficer)
        ->get(route('projects.create'))
        ->assertOk()
        ->assertSee('Supply Site Officer')
        ->assertSee('HSE Site Officer');

    $this->actingAs($hseOfficer)
        ->post(route('projects.store'), [
            'project_name' => 'Supply Project',
            'project_code' => 'SUP-001',
            'site_officer_id' => $siteOfficer->id,
        ])
        ->assertRedirect(route('projects.index'));

    expect(Project::query()->where('project_code', 'SUP-001')->first())
        ->site_officer_id->toBe($siteOfficer->id);
});
