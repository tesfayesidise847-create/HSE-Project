<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(): View
    {
        return view('materials.index', [
            'materials' => Material::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('materials.create', [
            'material' => new Material(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'material_name' => ['required', 'string', 'max:255'],
            'material_description' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $material = Material::create($data);

        app(WorkflowNotificationService::class)->materialCreated($material, $request->user());

        return Redirect::route('materials.index')->with('success', 'Material created successfully.');
    }

    public function edit(Material $material): View
    {
        return view('materials.edit', [
            'material' => $material,
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $data = $request->validate([
            'material_name' => ['required', 'string', 'max:255'],
            'material_description' => ['required', 'string'],
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
}
