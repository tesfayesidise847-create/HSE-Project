<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeSpreadsheetService
{
    /**
     * @var list<string>
     */
    public const HEADERS = ['first_name', 'last_name', 'gender', 'job_title'];

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Employees');

        $sheet->fromArray([self::HEADERS], null, 'A1');
        $sheet->fromArray([
            ['John', 'Doe', 'Male', 'Technician'],
        ], null, 'A2');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xls($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="employees_import_template.xls"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @return array{imported: int, errors: list<string>}
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if ($rows === []) {
            return ['imported' => 0, 'errors' => ['The file is empty.']];
        }

        $headerRow = array_map(
            fn ($value): string => strtolower(trim((string) $value)),
            $rows[0] ?? []
        );

        if (! $this->headersAreValid($headerRow)) {
            return [
                'imported' => 0,
                'errors' => ['Invalid template. Download the template and use columns: first_name, last_name, gender, job_title.'],
            ];
        }

        $imported = 0;
        $errors = [];
        $batch = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $rowNumber = $index + 2;
            $data = $this->mapRowToData($row);

            if ($this->rowIsEmpty($data)) {
                continue;
            }

            $validator = Validator::make($data, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'in:Male,Female,Other'],
                'job_title' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                $errors[] = 'Row '.$rowNumber.': '.implode(' ', $validator->errors()->all());

                continue;
            }

            $now = now();
            $batch[] = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'job_title' => $data['job_title'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $imported++;
        }

        foreach (array_chunk($batch, 500) as $chunk) {
            Employee::insert($chunk);
        }

        return ['imported' => $imported, 'errors' => $errors];
    }

    /**
     * @param  list<mixed>  $headerRow
     */
    private function headersAreValid(array $headerRow): bool
    {
        foreach (self::HEADERS as $index => $expected) {
            if (($headerRow[$index] ?? '') !== $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  list<mixed>  $row
     * @return array{first_name: string, last_name: string, gender: string, job_title: string}
     */
    private function mapRowToData(array $row): array
    {
        return [
            'first_name' => trim((string) ($row[0] ?? '')),
            'last_name' => trim((string) ($row[1] ?? '')),
            'gender' => $this->normalizeGender((string) ($row[2] ?? '')),
            'job_title' => trim((string) ($row[3] ?? '')),
        ];
    }

    /**
     * @param  array{first_name: string, last_name: string, gender: string, job_title: string}  $data
     */
    private function rowIsEmpty(array $data): bool
    {
        return $data['first_name'] === ''
            && $data['last_name'] === ''
            && $data['gender'] === ''
            && $data['job_title'] === '';
    }

    private function normalizeGender(string $gender): string
    {
        $normalized = ucfirst(strtolower(trim($gender)));

        return match ($normalized) {
            'Male', 'Female', 'Other' => $normalized,
            default => $gender,
        };
    }
}
