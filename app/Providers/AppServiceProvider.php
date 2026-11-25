<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Migrations críticas (alteração de tipo, NOT NULL, DROP, rename) ficam num caminho próprio,
        // sujeito a revisor obrigatório do DBA na política da branch main (equivalente ao CODEOWNERS,
        // seção 2.3.1). O Laravel as executa junto com as demais, em ordem de nome de arquivo.
        $this->loadMigrationsFrom(database_path('migrations/criticas'));
    }
}
