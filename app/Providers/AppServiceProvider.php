<?php

namespace App\Providers;

use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->forceHttpsForTunnel();

        Route::bind('cabor', function (string $value): Cabor {
            return Cabor::query()
                ->where('slug', $value)
                ->orWhere('id', $value)
                ->firstOrFail();
        });

        Route::bind('atlet', function (string $value): Atlet {
            return Atlet::query()
                ->where('slug', $value)
                ->orWhere('id', $value)
                ->firstOrFail();
        });

        Route::bind('artikel', function (string $value): Artikel {
            return Artikel::query()
                ->where('slug', $value)
                ->orWhere('id', $value)
                ->firstOrFail();
        });

        try {
            View::share('siteSettings', SiteSetting::current());
        } catch (\Throwable) {
            View::share('siteSettings', null);
        }
    }

    /**
     * Paksa HTTPS untuk URL asset/route (ngrok, tunnel, reverse proxy).
     */
    protected function forceHttpsForTunnel(): void
    {
        if ($this->app->runningInConsole() && ! filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        if (filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN)) {
            URL::forceScheme('https');

            return;
        }

        if (! $this->app->bound('request')) {
            return;
        }

        $host = request()->getHost();

        $isTunnel = str_ends_with($host, '.ngrok-free.app')
            || str_ends_with($host, '.ngrok.io')
            || str_ends_with($host, '.ngrok.app')
            || str_contains($host, 'ngrok');

        if ($isTunnel || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
