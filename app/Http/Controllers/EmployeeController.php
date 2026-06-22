<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\EmployeeSpreadsheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeSpreadsheetService $spreadsheetService) {}

    public function index(): View
    {
        return view('employees.index', [
            'employees' => Employee::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('employees.create', [
            'employee' => new Employee,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'job_title' => ['required', 'string', 'max:255'],
        ]);

        Employee::create($data);

        return Redirect::route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', [
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'job_title' => ['required', 'string', 'max:255'],
        ]);

        $employee->update($data);

        return Redirect::route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return Redirect::route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function importForm(): View
    {
        return view('employees.import');
    }

    public function downloadTemplate(): StreamedResponse
    {
        return $this->spreadsheetService->downloadTemplate();
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xls,xlsx', 'max:10240'],
        ]);

        $result = $this->spreadsheetService->import($request->file('file'));

        if ($result['imported'] === 0 && $result['errors'] !== []) {
            return Redirect::route('employees.import')
                ->with('error', implode(' ', $result['errors']));
        }

        $message = $result['imported'].' employee(s) imported successfully.';

        if ($result['errors'] !== []) {
            $message .= ' Some rows were skipped: '.implode(' ', $result['errors']);
        }

        return Redirect::route('employees.index')->with('success', $message);
    }
}
