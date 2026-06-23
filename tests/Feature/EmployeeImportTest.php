<?php

use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeSpreadsheetService;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('allows admin to download employee import template', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $this->actingAs($admin)
        ->get(route('employees.import.template'))
        ->assertOk()
        ->assertHeader('content-type', 'application/vnd.ms-excel');
});

it('imports employees from xls file', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $path = storage_path('app/test_employees.xls');
    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([EmployeeSpreadsheetService::HEADERS], null, 'A1');
    $sheet->fromArray([
        ['Alice', 'Brown', 'Female', 'Engineer'],
        ['Bob', 'Green', 'Male', 'Supervisor'],
    ], null, 'A2');
    (new Xls($spreadsheet))->save($path);

    $this->actingAs($admin)
        ->post(route('employees.import.store'), [
            'file' => new UploadedFile($path, 'employees.xls', 'application/vnd.ms-excel', null, true),
        ])
        ->assertRedirect(route('employees.index'))
        ->assertSessionHas('success');

    expect(Employee::count())->toBe(2);
    expect(Employee::where('first_name', 'Alice')->exists())->toBeTrue();

    @unlink($path);
});

it('denies non admin from importing employees', function () {
    $user = User::factory()->create();
    $user->assignRole('HSE Site Officer');

    $this->actingAs($user)->get(route('employees.import'))->assertForbidden();
});
