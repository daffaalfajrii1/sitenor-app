<?php

namespace App\Http\Controllers\Cabor\Concerns;

use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Juri;
use App\Models\Pelatih;
use App\Models\Prestasi;
use App\Models\Wasit;
use Illuminate\Database\Eloquent\Model;

trait ScopesToCabor
{
    protected function caborId(): int
    {
        $id = auth()->user()?->cabor_id;

        if (! $id) {
            abort(403, 'Akun belum terhubung ke cabang olahraga. Hubungi administrator.');
        }

        return (int) $id;
    }

    protected function assertModelBelongsToCabor(Model $model): void
    {
        if ($model instanceof Prestasi) {
            $model->loadMissing('atlet');
            if ((int) $model->atlet->cabor_id !== $this->caborId()) {
                abort(404);
            }

            return;
        }

        if ($model instanceof Artikel) {
            if ((int) $model->cabor_id !== $this->caborId()) {
                abort(404);
            }

            return;
        }

        if (isset($model->cabor_id) && (int) $model->cabor_id !== $this->caborId()) {
            abort(404);
        }
    }

    protected function assertAtletIdBelongsToCabor(int $atletId): void
    {
        $exists = Atlet::query()
            ->where('id', $atletId)
            ->where('cabor_id', $this->caborId())
            ->exists();

        if (! $exists) {
            abort(404);
        }
    }
}
