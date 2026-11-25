<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Confere as regras de negócio que o banco precisa manter depois de qualquer migration:
 *   1. o saldo de cada produto é a soma das suas movimentações (entradas - saídas);
 *   2. nenhum saldo negativo;
 *   3. nenhuma movimentação apontando para produto inexistente.
 * O pipeline roda depois de aplicar as migrations no ensaio em staging e na produção.
 */
class VerificarIntegridade extends Command
{
    protected $signature = 'almox:verificar-integridade';

    protected $description = 'Confere saldos x movimentações e referências do estoque';

    public function handle(): int
    {
        $divergentes = DB::selectOne(<<<'SQL'
            SELECT COUNT(*) AS total
              FROM produtos p
              LEFT JOIN (SELECT produto_id, SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END) AS soma
                           FROM movimentacoes GROUP BY produto_id) m ON m.produto_id = p.id
             WHERE p.saldo <> COALESCE(m.soma, 0)
            SQL)->total;
        $negativos = DB::table('produtos')->where('saldo', '<', 0)->count();
        $orfas = DB::table('movimentacoes as m')->leftJoin('produtos as p', 'p.id', '=', 'm.produto_id')->whereNull('p.id')->count();

        $this->table(['Regra', 'Problemas'], [
            ['Saldo = soma das movimentações', $divergentes],
            ['Saldo não negativo', $negativos],
            ['Movimentação com produto existente', $orfas],
        ]);

        if ($divergentes + $negativos + $orfas > 0) {
            $this->error('Integridade do estoque violada.');

            return self::FAILURE;
        }

        $this->info(sprintf('Integridade OK: %d produtos, %d movimentações.',
            DB::table('produtos')->count(), DB::table('movimentacoes')->count()));

        return self::SUCCESS;
    }
}
