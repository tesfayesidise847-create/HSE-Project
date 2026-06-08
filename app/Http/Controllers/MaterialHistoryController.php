<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialHistoryController extends Controller
{
    public function index(): View
    {
        $histories = MaterialHistory::with(['material', 'createdBy'])
            ->latest()
            ->paginate(15);

        $materials = Material::with('histories')
            ->orderBy('material_name')
            ->get()
            ->map(function (Material $material): array {
                $openingBalance = $material->histories->last()?->balance_before ?? 0;

                return [
                    'material' => $material,
                    'opening_balance' => $openingBalance,
                    'current_balance' => $material->quantity,
                ];
            });

        return view('material-histories.index', [
            'histories' => $histories,
            'materials' => $materials,
        ]);
    }

    public function export(Request $request): Response
    {
        $format = strtolower($request->query('format', 'xlsx'));
        $histories = MaterialHistory::with(['material', 'createdBy'])
            ->latest()
            ->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('material-histories.export', [
                'histories' => $histories,
                'title' => 'Material History Report',
            ]);

            return $pdf->download('material_history.pdf');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Material History');

        $sheet->fromArray([
            ['Date', 'Material', 'Event', 'Quantity Change', 'Balance Before', 'Balance After', 'Description', 'Recorded By'],
        ], null, 'A1');

        $row = 2;

        foreach ($histories as $history) {
            $sheet->fromArray([
                $history->created_at->format('Y-m-d H:i:s'),
                $history->material?->material_name,
                $this->eventLabel($history->event_type),
                $history->quantity_change,
                $history->balance_before,
                $history->balance_after,
                $history->description,
                $history->createdBy?->name,
            ], null, "A{$row}");

            $row++;
        }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xls($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="material_history.xls"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function eventLabel(string $eventType): string
    {
        return match ($eventType) {
            'created' => 'Created',
            'imported' => 'Imported',
            'stock_added' => 'Head Office Stock Added',
            'assigned_to_project' => 'Assigned to Project',
            default => ucfirst(str_replace('_', ' ', $eventType)),
        };
    }
}
