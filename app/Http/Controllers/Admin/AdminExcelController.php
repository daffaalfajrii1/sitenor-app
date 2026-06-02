<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ExcelImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExcelController extends Controller
{
    public function __construct(
        private ExcelImportService $excel
    ) {}

    public function template(string $module): StreamedResponse
    {
        abort_unless(in_array($module, $this->excel->modules(), true), 404);

        return $this->excel->downloadTemplate($module);
    }

    public function import(Request $request, string $module): RedirectResponse
    {
        abort_unless(in_array($module, $this->excel->modules(), true), 404);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $result = $this->excel->import($module, $request->file('file'));
        $config = $this->excel->config($module);
        $route = $config['route_index'];

        if ($result['imported'] > 0 && $result['errors'] === []) {
            return redirect()->route($route)->with('success', "{$result['imported']} data {$config['label']} berhasil diimpor.");
        }

        if ($result['imported'] > 0) {
            return redirect()->route($route)->with('success', "{$result['imported']} data berhasil diimpor.")
                ->with('import_errors', $result['errors']);
        }

        return redirect()->route($route)
            ->with('error', 'Impor gagal.')
            ->with('import_errors', $result['errors']);
    }
}
