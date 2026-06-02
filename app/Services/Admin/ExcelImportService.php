<?php

namespace App\Services\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelImportService
{
    use HandlesUploads;

    private ?int $forcedCaborId = null;

    public function modules(): array
    {
        return array_keys(config('admin-excel', []));
    }

    public function caborModules(): array
    {
        return array_keys(config('cabor-excel', []));
    }

    public function config(string $module, bool $caborPanel = false): array
    {
        $config = $caborPanel
            ? config("cabor-excel.{$module}")
            : config("admin-excel.{$module}");

        if (! $config) {
            abort(404, 'Modul impor tidak ditemukan.');
        }

        return $config;
    }

    public function downloadTemplate(string $module, ?int $forcedCaborId = null): StreamedResponse
    {
        $this->forcedCaborId = $forcedCaborId;
        $config = $this->config($module, $forcedCaborId !== null);
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        $keys = array_keys($config['headers']);
        $col = 1;
        foreach ($config['headers'] as $label) {
            $sheet->setCellValue([$col, 1], $label);
            $sheet->getStyle([$col, 1])->getFont()->setBold(true);
            $col++;
        }

        $row = 2;
        $col = 1;
        foreach ($keys as $key) {
            $sheet->setCellValue([$col, $row], $config['example'][$key] ?? '');
            $col++;
        }

        foreach (range(1, count($keys)) as $c) {
            $sheet->getColumnDimensionByColumn($c)->setAutoSize(true);
        }

        $filename = 'template-import-'.Str::slug($config['label']).'.xlsx';

        $this->forcedCaborId = null;

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return array{imported: int, errors: array<int, string>}
     */
    public function import(string $module, UploadedFile $file, ?int $forcedCaborId = null): array
    {
        $this->forcedCaborId = $forcedCaborId;
        $config = $this->config($module, $forcedCaborId !== null);
        $spreadsheet = IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if (count($rows) < 2) {
            return ['imported' => 0, 'errors' => ['File kosong atau hanya berisi header.']];
        }

        $headerRow = array_map(fn ($v) => $this->normalizeHeader((string) $v), $rows[0]);
        $keyMap = $this->mapHeadersToKeys($headerRow, $config['headers']);

        $imported = 0;
        $errors = [];

        for ($i = 1; $i < count($rows); $i++) {
            $line = $i + 1;
            $raw = $rows[$i];

            if ($this->rowIsEmpty($raw)) {
                continue;
            }

            $data = [];
            foreach ($keyMap as $colIndex => $key) {
                $data[$key] = trim((string) ($raw[$colIndex] ?? ''));
            }

            try {
                $this->importRow($module, $data);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Baris {$line}: {$e->getMessage()}";
            }
        }

        $this->forcedCaborId = null;

        return compact('imported', 'errors');
    }

    private function mapHeadersToKeys(array $headerRow, array $headers): array
    {
        $lookup = [];
        foreach ($headers as $key => $label) {
            $lookup[$this->normalizeHeader($label)] = $key;
            $lookup[$this->normalizeHeader($key)] = $key;
        }

        $map = [];
        foreach ($headerRow as $index => $header) {
            if ($header !== '' && isset($lookup[$header])) {
                $map[$index] = $lookup[$header];
            }
        }

        if ($map === []) {
            throw new \RuntimeException('Header kolom tidak dikenali. Unduh ulang template Excel.');
        }

        return $map;
    }

    private function normalizeHeader(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return $value;
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }

    private function parseBool(string $value): bool
    {
        $value = strtolower(trim($value));

        if (in_array($value, ['ya', 'yes', '1', 'aktif', 'true'], true)) {
            return true;
        }

        if (in_array($value, ['tidak', 'no', '0', 'nonaktif', 'false', ''], true)) {
            return false;
        }

        return true;
    }

    private function parseDate(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value))->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            throw new \InvalidArgumentException("Format tanggal tidak valid: {$value}");
        }
    }

    private function importRow(string $module, array $data): void
    {
        match ($module) {
            'cabor' => $this->importCabor($data),
            'atlet' => $this->importAtlet($data),
            'pelatih' => $this->importPelatih($data),
            'wasit' => $this->importWasit($data),
            'juri' => $this->importJuri($data),
            default => throw new \InvalidArgumentException('Modul tidak didukung.'),
        };
    }

    private function importCabor(array $data): void
    {
        if (empty($data['kode']) || empty($data['name'])) {
            throw new \InvalidArgumentException('Kode dan nama cabor wajib diisi.');
        }

        if (Cabor::where('kode', $data['kode'])->exists()) {
            throw new \InvalidArgumentException("Kode cabor {$data['kode']} sudah ada.");
        }

        Cabor::create([
            'kode' => $data['kode'],
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], Cabor::class),
            'description' => $data['description'] ?: null,
            'is_active' => $this->parseBool($data['is_active'] ?? 'ya'),
        ]);
    }

    private function resolveCaborId(array $data): int
    {
        if ($this->forcedCaborId !== null) {
            return $this->forcedCaborId;
        }

        $kode = $data['kode_cabor'] ?? '';

        if ($kode === '') {
            throw new \InvalidArgumentException('Kode cabor wajib diisi.');
        }

        $cabor = Cabor::where('kode', $kode)->first();

        if (! $cabor) {
            throw new \InvalidArgumentException("Cabor dengan kode {$kode} tidak ditemukan.");
        }

        return $cabor->id;
    }

    private function importAtlet(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Nama atlet wajib diisi.');
        }

        $caborId = $this->resolveCaborId($data);
        $gender = $data['gender'] ?? '';

        if ($gender !== '' && ! in_array($gender, ['laki-laki', 'perempuan'], true)) {
            throw new \InvalidArgumentException('Jenis kelamin harus laki-laki atau perempuan.');
        }

        \App\Models\Atlet::create([
            'cabor_id' => $caborId,
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], \App\Models\Atlet::class, ['cabor_id' => $caborId]),
            'birth_date' => $this->parseDate($data['birth_date'] ?? null),
            'gender' => $gender ?: null,
            'phone' => $data['phone'] ?: null,
            'email' => $data['email'] ?: null,
            'address' => $data['address'] ?: null,
            'bio' => $data['bio'] ?: null,
            'is_active' => $this->parseBool($data['is_active'] ?? 'ya'),
        ]);
    }

    private function importPelatih(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Nama pelatih wajib diisi.');
        }

        $caborId = $this->resolveCaborId($data);

        \App\Models\Pelatih::create([
            'cabor_id' => $caborId,
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], \App\Models\Pelatih::class, ['cabor_id' => $caborId]),
            'license_number' => $data['license_number'] ?: null,
            'level' => \App\Models\Prestasi::normalizeLevel($data['level'] ?? null),
            'phone' => $data['phone'] ?: null,
            'email' => $data['email'] ?: null,
            'bio' => $data['bio'] ?: null,
            'is_active' => $this->parseBool($data['is_active'] ?? 'ya'),
        ]);
    }

    private function importWasit(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Nama wasit wajib diisi.');
        }

        $caborId = $this->resolveCaborId($data);

        \App\Models\Wasit::create([
            'cabor_id' => $caborId,
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], \App\Models\Wasit::class, ['cabor_id' => $caborId]),
            'license_number' => $data['license_number'] ?: null,
            'level' => \App\Models\Prestasi::normalizeLevel($data['level'] ?? null),
            'phone' => $data['phone'] ?: null,
            'is_active' => $this->parseBool($data['is_active'] ?? 'ya'),
        ]);
    }

    private function importJuri(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Nama juri wajib diisi.');
        }

        $caborId = $this->resolveCaborId($data);

        \App\Models\Juri::create([
            'cabor_id' => $caborId,
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], \App\Models\Juri::class, ['cabor_id' => $caborId]),
            'license_number' => $data['license_number'] ?: null,
            'level' => \App\Models\Prestasi::normalizeLevel($data['level'] ?? null),
            'phone' => $data['phone'] ?: null,
            'is_active' => $this->parseBool($data['is_active'] ?? 'ya'),
        ]);
    }
}
