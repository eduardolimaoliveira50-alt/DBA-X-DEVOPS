<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // o painel usa Bootstrap 4 (AdminLTE); o padrão do Laravel 12 é Tailwind
        Paginator::useBootstrapFour();

        // migrations de risco ficam em pasta própria: o caminho tem revisor obrigatório do DBA na política da main
        $this->loadMigrationsFrom(database_path('migrations/criticas'));

        // no Render o HTTPS termina no balanceador: sem isto, links e assets sairiam "http://" dentro da página https
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
