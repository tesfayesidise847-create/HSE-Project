<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Services\WorkflowNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MaterialQuantityController extends Controller
{
    public function index(): View
    {
        return view('material-quantities.index', [
            'materials' => Material::orderBy('material_name')->paginate(10),
        ]);
    }

    public function edit(Material $material): View
    {
        return view('material-quantities.edit', [
            'material' => $material,
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $data = $request->validate([
            'quantity_to_add' => ['required', 'integer', 'min:1'],
        ]);

        $quantityToAdd = (int) $data['quantity_to_add'];
        $material->addQuantity($quantityToAdd);
        $material->recordHistory('stock_added', $quantityToAdd, 'Head office quantity added.', $request->user()->id);

        app(WorkflowNotificationService::class)->materialQuantityUpdated(
            $material->fresh(),
            $quantityToAdd,
            $request->user(),
        );

        return Redirect::route('material-quantities.index')
            ->with('success', 'Head office quantity updated for '.$material->material_name.'.');
    }
}
