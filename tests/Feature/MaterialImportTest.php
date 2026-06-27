<?php

use App\Models\Material;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Services\MaterialSpreadsheetService;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UnitOfMeasureSeeder;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(UnitOfMeasureSeeder::class);
});

it('allows hse officer to download material import template', function () {
    $user = User::factory()->create();
    $user->assignRole('HSE Officer');

    $this->actingAs($user)
        ->get(route('materials.import.template'))
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.ms-excel');
});

it('imports materials from xls file', function () {
    $user = User::factory()->create();
    $user->assignRole('HSE Officer');

    $path = storage_path('app/test_materials.xls');
    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([MaterialSpreadsheetService::HEADERS], null, 'A1');
    $sheet->fromArray([
        ['Gravel', 'Coarse gravel bags', 50, 'Bag'],
        ['Sand', 'Fine construction sand', 120, 'Bag'],
    ], null, 'A2');
    (new Xls($spreadsheet))->save($path);

    $this->actingAs($user)
        ->post(route('materials.import.store'), [
            'file' => new UploadedFile($path, 'materials.xls', 'application/vnd.ms-excel', null, true),
        ])
        ->assertRedirect(route('materials.index'))
        ->assertSessionHas('success');

    expect(Material::count())->toBe(2);
    expect(Material::where('material_name', 'Gravel')->exists())->toBeTrue();

    @unlink($path);
});

it('filters and searches materials', function () {
    $user = User::factory()->create();
    $user->assignRole('HSE Officer');
    $bag = UnitOfMeasure::where('name', 'Bag')->firstOrFail();
    $pcs = UnitOfMeasure::where('name', 'Pcs')->firstOrFail();

    Material::create([
        'material_name' => 'Safety Helmet',
        'material_description' => 'Head protection',
        'quantity' => 15,
        'unit_of_measure_id' => $pcs->id,
    ]);

    Material::create([
        'material_name' => 'Cement',
        'material_description' => 'Construction bag',
        'quantity' => 50,
        'unit_of_measure_id' => $bag->id,
    ]);

    $this->actingAs($user)
        ->get(route('materials.index', ['search' => 'helmet', 'unit_of_measure_id' => $pcs->id]))
        ->assertOk()
        ->assertSee('Safety Helmet')
        ->assertDontSee('Cement');
});

it('denies non hse officer from importing materials', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user)->get(route('materials.import'))->assertForbidden();
});
