<?php

use App\Models\Atlet;
use App\Models\Artikel;
use App\Models\Cabor;
use App\Models\Juri;
use App\Models\Pelatih;
use App\Models\Prestasi;
use App\Models\Wasit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (! function_exists('panel_cabor')) {
    function panel_cabor(): ?Cabor
    {
        $cabor = request()->route('cabor');

        if ($cabor instanceof Cabor) {
            return $cabor;
        }

        return auth()->user()?->cabor;
    }
}

if (! function_exists('cabor_route_model_param')) {
    /**
     * Nama parameter route untuk model tertentu.
     */
    function cabor_route_model_param(Model $model): ?string
    {
        return match ($model::class) {
            Atlet::class => 'atlet',
            Pelatih::class => 'pelatih',
            Wasit::class => 'wasit',
            Juri::class => 'juri',
            Prestasi::class => 'prestasi',
            Artikel::class => 'artikel',
            default => null,
        };
    }
}

if (! function_exists('cabor_route')) {
    /**
     * @param  array<string, mixed>|mixed  $parameters
     */
    function cabor_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $params = [];

        if (is_array($parameters)) {
            $params = $parameters;
        } elseif ($parameters instanceof Model) {
            $paramName = cabor_route_model_param($parameters);
            $route = Route::getRoutes()->getByName($name);

            if ($paramName) {
                $params[$paramName] = $parameters;
            } elseif ($route) {
                foreach ($route->parameterNames() as $paramName) {
                    if ($paramName !== 'cabor') {
                        $params[$paramName] = $parameters;
                        break;
                    }
                }
            }
        } elseif ($parameters !== null && $parameters !== '') {
            if (str_contains($name, '.excel.')) {
                $params['module'] = $parameters;
            } else {
                $route = Route::getRoutes()->getByName($name);
                if ($route) {
                    foreach ($route->parameterNames() as $paramName) {
                        if ($paramName !== 'cabor') {
                            $params[$paramName] = $parameters;
                            break;
                        }
                    }
                }
            }
        }

        if (! isset($params['cabor'])) {
            $cabor = panel_cabor();
            if ($cabor) {
                $params['cabor'] = $cabor;
            }
        }

        return route($name, $params, $absolute);
    }
}

if (! function_exists('set_cabor_route_defaults')) {
    function set_cabor_route_defaults(?Cabor $cabor): void
    {
        if ($cabor) {
            URL::defaults(['cabor' => $cabor->getRouteKey()]);
        }
    }
}

if (! function_exists('auth_home_redirect')) {
    /**
     * URL dashboard sesuai peran setelah login.
     */
    function auth_home_redirect(): string
    {
        $user = auth()->user();

        if (! $user) {
            return route('public.home');
        }

        if ($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN)) {
            return route('admin.dashboard');
        }

        if ($user->hasRole(\App\Models\User::ROLE_ADMIN_CABOR)) {
            $user->loadMissing('cabor');

            if ($user->cabor) {
                return cabor_route('cabor.dashboard', $user->cabor);
            }
        }

        return route('public.home');
    }
}

if (! function_exists('auth_should_use_intended_url')) {
    function auth_should_use_intended_url(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';

        foreach (['/dashboard', '/login', '/register', '/forgot-password'] as $blocked) {
            if ($path === $blocked || str_starts_with($path, $blocked.'/')) {
                return false;
            }
        }

        return true;
    }
}
