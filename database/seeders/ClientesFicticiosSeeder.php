<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Massa de dados fictícia para o "ambiente de produção" do laboratório (estágio de CD).
 *
 * Grava apenas nas colunas que existem no esquema atual, para funcionar em qualquer fase do
 * Expand-Contract (antes da expansão, durante a coexistência e depois da contração).
 */
class ClientesFicticiosSeeder extends Seeder
{
    public function run(): void
    {
        $total = (int) env('LAB_CLIENTES', 5000);
        $temEmail = Schema::hasColumn('clientes', 'email');
        $temCorporativo = Schema::hasColumn('clientes', 'email_corporativo');

        for ($inicio = 1; $inicio <= $total; $inicio += 1000) {
            $lote = [];
            foreach (range($inicio, min($inicio + 999, $total)) as $n) {
                $email = sprintf('cliente%05d@exemplo.com', $n);
                $linha = [];
                if ($temEmail) {
                    $linha['email'] = $email;
                }
                // Na produção simulada, metade dos registros antigos ainda não tem a coluna nova preenchida.
                if ($temCorporativo && (! $temEmail || $n % 2 === 0)) {
                    $linha['email_corporativo'] = $email;
                }
                $lote[] = $linha;
            }
            DB::table('clientes')->insert($lote);
        }
    }
}
