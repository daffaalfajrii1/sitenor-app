<?php

namespace App\Http\Controllers\Public\Concerns;

use App\Services\Public\PublicStatsService;

trait ResolvesPublicStats
{
    protected function stats(): PublicStatsService
    {
        return app(PublicStatsService::class);
    }
}
