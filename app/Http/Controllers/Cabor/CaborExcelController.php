<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Services\Admin\ExcelImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CaborExcelController extends Controller
{
    use ScopesToCabor;

    public function __construct(
        private ExcelImportService $excel
    ) {}

    public function template(string $module): StreamedResponse
    {
        abort_unless(in_array($module, $this->excel->caborModules(), true), 404);

        return $this->excel->downloadTemplate($module, $this->caborId());
    }

    public function import(Request $request, string $module): RedirectResponse
    {
        abort_unless(in_array($module, $this->excel->caborModules(), true), 404);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $result = $this->excel->import($module, $request->file('file'), $this->caborId());
        $config = $this->excel->config($module, true);

        if ($result['imported'] > 0 && $result['errors'] === []) {
            return redirect(cabor_route($config['route_index']))
                ->with('success', "{$result['imported']} data {$config['label']} berhasil diimpor.");
        }

        if ($result['imported'] > 0) {
            return redirect(cabor_route($config['route_index']))
                ->with('success', "{$result['imported']} data berhasil diimpor.")
                ->with('import_errors', $result['errors']);
        }

        return redirect(cabor_route($config['route_index']))
            ->with('error', 'Impor gagal.')
            ->with('import_errors', $result['errors']);
    }
}
