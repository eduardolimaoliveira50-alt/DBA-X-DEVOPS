<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Backfill controlado em lotes (seção 2.3.4, fase Migrate).
 *
 * Só deve rodar depois que todos os escritores gravam nas duas colunas. Usa a chave primária
 * (chunkById) e não sobrescreve valores já preenchidos, para poder ser repetido com segurança.
 * Precisa apenas de DML: roda com a conta da aplicação, não com a de migração.
 */
class BackfillEmailCorporativo extends Command
{
    protected $signature = 'clientes:backfill-email-corporativo {--lote=1000 : registros por lote}';

    protected $description = 'Preenche email_corporativo a partir de email, em lotes, sem sobrescrever';

    public function handle(): int
    {
        $lote = max(1, (int) $this->option('lote'));
        $lotes = 0;
        $atualizados = 0;
        $inicio = microtime(true);

        DB::table('clientes')->select('id')->whereNull('email_corporativo')
            ->chunkById($lote, function ($rows) use (&$lotes, &$atualizados) {
                $atualizados += DB::table('clientes')->whereIn('id', $rows->pluck('id'))
                    ->whereNull('email_corporativo')
                    ->update(['email_corporativo' => DB::raw('email')]);
                $lotes++;
            });

        $this->info(sprintf('Backfill: %d registro(s) em %d lote(s) de até %d, %.2fs.', $atualizados, $lotes, $lote, microtime(true) - $inicio));

        return self::SUCCESS;
    }
}
