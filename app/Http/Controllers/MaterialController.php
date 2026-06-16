<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UnitOfMeasure;
use App\Services\MaterialSpreadsheetService;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function __construct(private MaterialSpreadsheetService $spreadsheetService) {}

    public function index(): View
    {
        return view('materials.index', [
            'materials' => Material::with('unitOfMeasure')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('materials.create', [
            'material' => new Material,
            'unitOfMeasures' => UnitOfMeasure::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'material_name' => ['required', 'string', 'max:255'],
            'material_description' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit_of_measure_id' => ['required', 'exists:unit_of_measures,id'],
        ]);

        $material = Material::create($data);

        app(WorkflowNotificationService::class)->materialCreated($material, $request->user());

        return Redirect::route('materials.index')->with('success', 'Material created successfully.');
    }

    public function edit(Material $material): View
    {
        return view('materials.edit', [
            'material' => $material,
            'unitOfMeasures' => UnitOfMeasure::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $data = $request->validate([
            'material_name' => ['required', 'string', 'max:255'],
            'material_description' => ['required', 'string'],
            'unit_of_measure_id' => ['required', 'exists:unit_of_measures,id'],
        ]);

        $material->update($data);

        app(WorkflowNotificationService::class)->materialUpdated($material, $request->user());

        return Redirect::route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $material->delete();

        return Redirect::route('materials.index')->with('success', 'Material deleted successfully.');
    }

    public function importForm(): View
    {
        return view('materials.import');
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

        $result = $this->spreadsheetService->import($request->file('file'), $request->user()->id);

        if ($result['imported'] === 0 && $result['errors'] !== []) {
            return Redirect::route('materials.import')
                ->with('error', implode(' ', $result['errors']));
        }

        $message = $result['imported'].' material(s) imported successfully.';

        if ($result['errors'] !== []) {
            $message .= ' Some rows were skipped: '.implode(' ', $result['errors']);
        }

        return Redirect::route('materials.index')->with('success', $message);
    }
}
