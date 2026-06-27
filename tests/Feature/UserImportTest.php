<?php

use App\Models\User;
use App\Services\UserSpreadsheetService;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('allows admin to download user import template', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->get(route('users.import.template'))
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.ms-excel');
});

it('imports users from xls file', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $path = storage_path('app/test_users.xls');
    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([UserSpreadsheetService::HEADERS], null, 'A1');
    $sheet->fromArray([
        ['HSE User', 'hse-import@example.com', 'password123', 'HSE Officer'],
        ['Site User', 'site-import@example.com', 'password123', 'HSE Site Officer'],
    ], null, 'A2');
    (new Xls($spreadsheet))->save($path);

    $this->actingAs($admin)
        ->post(route('users.import.store'), [
            'file' => new UploadedFile($path, 'users.xls', 'application/vnd.ms-excel', null, true),
        ])
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    expect(User::where('email', 'hse-import@example.com')->first()->hasRole('HSE Officer'))->toBeTrue();
    expect(User::where('email', 'site-import@example.com')->first()->hasRole('HSE Site Officer'))->toBeTrue();

    @unlink($path);
});

it('denies non admin from importing users', function () {
    $user = User::factory()->create();
    $user->assignRole('HSE Officer');

    $this->actingAs($user)->get(route('users.import'))->assertForbidden();
});
