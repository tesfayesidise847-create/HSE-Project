<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MaterialAssignmentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialQuantityController;
use App\Http\Controllers\MaterialReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteOfficerEmployeeAssignmentController;
use App\Http\Controllers\SiteOfficerMaterialReportController;
use App\Http\Controllers\SiteOfficerProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::get('employees/import', [EmployeeController::class, 'importForm'])->name('employees.import');
        Route::get('employees/import/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.import.template');
        Route::post('employees/import', [EmployeeController::class, 'importStore'])->name('employees.import.store');
        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::resource('projects', ProjectController::class)->except(['show']);
    });

    Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');

    Route::middleware('role:HSE Officer')->group(function () {
        Route::get('materials/create', [MaterialController::class, 'create'])->name('materials.create');
        Route::post('materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::get('materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::delete('materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

        Route::get('material-assignments/create', [MaterialAssignmentController::class, 'create'])->name('material-assignments.create');
        Route::post('material-assignments', [MaterialAssignmentController::class, 'store'])->name('material-assignments.store');

        Route::get('material-quantities', [MaterialQuantityController::class, 'index'])->name('material-quantities.index');
        Route::get('material-quantities/{material}/edit', [MaterialQuantityController::class, 'edit'])->name('material-quantities.edit');
        Route::patch('material-quantities/{material}', [MaterialQuantityController::class, 'update'])->name('material-quantities.update');

        Route::get('material-reports/inventory', [MaterialReportController::class, 'inventory'])->name('material-reports.inventory');
        Route::get('material-reports', [MaterialReportController::class, 'index'])->name('material-reports.index');
        Route::get('material-reports/{project}', [MaterialReportController::class, 'show'])->name('material-reports.show');
    });

    Route::middleware('role:HSE Site Officer')->prefix('site-officer')->name('site-officer.')->group(function () {
        Route::get('projects', [SiteOfficerProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [SiteOfficerProjectController::class, 'show'])->name('projects.show');

        Route::get('material-reports', [SiteOfficerMaterialReportController::class, 'index'])->name('material-reports.index');

        Route::get('employee-assignments', [SiteOfficerEmployeeAssignmentController::class, 'index'])->name('employee-assignments.index');
        Route::get('employee-assignments/create', [SiteOfficerEmployeeAssignmentController::class, 'create'])->name('employee-assignments.create');
        Route::post('employee-assignments', [SiteOfficerEmployeeAssignmentController::class, 'store'])->name('employee-assignments.store');
        Route::get('employees/{employee}/assignment-history', [SiteOfficerEmployeeAssignmentController::class, 'employeeHistory'])->name('employees.assignment-history');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

require __DIR__.'/auth.php';
