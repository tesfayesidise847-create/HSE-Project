<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserSpreadsheetService
{
    /**
     * @var list<string>
     */
    public const HEADERS = ['name', 'email', 'password', 'role'];

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Users');

        $sheet->fromArray([self::HEADERS], null, 'A1');
        $sheet->fromArray([
            ['Jane Admin', 'jane@example.com', 'password123', 'HSE Officer'],
        ], null, 'A2');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xls($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="users_import_template.xls"',
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
                'errors' => ['Invalid template. Download the template and use columns: name, email, password, role.'],
            ];
        }

        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $rowNumber = $index + 2;
            $data = $this->mapRowToData($row);

            if ($this->rowIsEmpty($data)) {
                continue;
            }

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
                'role' => ['required', 'string', 'exists:roles,name'],
            ]);

            if ($validator->fails()) {
                $errors[] = 'Row '.$rowNumber.': '.implode(' ', $validator->errors()->all());

                continue;
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->assignRole($data['role']);
            $imported++;
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
     * @return array{name: string, email: string, password: string, role: string}
     */
    private function mapRowToData(array $row): array
    {
        return [
            'name' => trim((string) ($row[0] ?? '')),
            'email' => trim((string) ($row[1] ?? '')),
            'password' => trim((string) ($row[2] ?? '')),
            'role' => $this->normalizeRole((string) ($row[3] ?? '')),
        ];
    }

    /**
     * @param  array{name: string, email: string, password: string, role: string}  $data
     */
    private function rowIsEmpty(array $data): bool
    {
        return $data['name'] === ''
            && $data['email'] === ''
            && $data['password'] === ''
            && $data['role'] === '';
    }

    private function normalizeRole(string $roleName): string
    {
        $normalized = strtolower(trim($roleName));

        return Role::query()
            ->pluck('name')
            ->first(fn (string $role): bool => strtolower($role) === $normalized) ?? trim($roleName);
    }
}
