<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * No Render (plano gratuito: uma instância de PostgreSQL e um usuário só), o almoxarifado vive num schema
 * próprio dentro do banco. Este comando é o "criar o banco" do DBA para esse ambiente: idempotente, roda
 * a cada subida e só cria o schema se ele não existir. Nos outros ambientes, o DBA usa scripts/dba/criar-banco.sh.
 */
class ProvisionarSchema extends Command
{
    protected $signature = 'almox:provisionar-schema';

    protected $description = 'Cria o schema do almoxarifado (DB_SCHEMA) se ainda não existir';

    public function handle(): int
    {
        $schema = (string) config('database.connections.pgsql.search_path', 'public');

        if (DB::connection()->getDriverName() !== 'pgsql' || $schema === 'public') {
            $this->info('Schema public: nada a provisionar.');

            return self::SUCCESS;
        }

        if (! preg_match('/^[a-z_][a-z0-9_]*$/', $schema)) {
            $this->error("Nome de schema inválido: {$schema}");

            return self::FAILURE;
        }

        DB::statement("CREATE SCHEMA IF NOT EXISTS {$schema}");
        $this->info("Schema {$schema} pronto.");

        return self::SUCCESS;
    }
}
