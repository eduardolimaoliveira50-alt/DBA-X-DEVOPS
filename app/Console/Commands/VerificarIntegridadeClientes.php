<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Validação do backfill (seção 2.3.4): os dois resultados devem ser zero.
 * O pipeline de CD roda este comando depois de cada implantação; falha = implantação reprovada.
 */
class VerificarIntegridadeClientes extends Command
{
    protected $signature = 'clientes:verificar-integridade';

    protected $description = 'Confere se email_corporativo está preenchido e igual a email';

    public function handle(): int
    {
        $nulos = DB::table('clientes')->whereNull('email_corporativo')->count();
        $divergentes = DB::table('clientes')->whereColumn('email', '<>', 'email_corporativo')->count();

        $this->table(['Verificação', 'Quantidade', 'Esperado'], [
            ['email_corporativo nulo', $nulos, 0],
            ['email diferente de email_corporativo', $divergentes, 0],
        ]);

        if ($nulos || $divergentes) {
            $this->error('Integridade reprovada.');

            return self::FAILURE;
        }

        $this->info('Integridade aprovada.');

        return self::SUCCESS;
    }
}
