<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/**
 * "Banco de dados": o que está aplicado no banco desta instalação. Na apresentação, mostra a migration recém-
 * implantada, o índice criado sem travar a tabela, a restrição NOT VALID etc. Só leitura do catálogo.
 */
class BancoController extends Controller
{
    private const TABELAS = ['produtos', 'fornecedores', 'movimentacoes', 'categorias'];

    public function __invoke(): View
    {
        $pg = DB::connection()->getDriverName() === 'pgsql';

        return view('admin.banco.index', [
            'pg' => $pg,
            'servidor' => $pg ? DB::selectOne('SHOW server_version')->server_version : DB::connection()->getDriverName(),
            'migrations' => DB::table('migrations')->orderByDesc('id')->get(),
            'tabelas' => collect(self::TABELAS)->map(fn ($t) => [
                'nome' => $t,
                'linhas' => DB::table($t)->count(),
                'tamanho' => $pg ? DB::selectOne('SELECT pg_size_pretty(pg_total_relation_size(?::regclass)) AS t', [$t])->t : null,
            ]),
            'colunas' => collect(['produtos', 'fornecedores'])->mapWithKeys(fn ($t) => [$t => Schema::getColumns($t)]),
            'indices' => $pg ? DB::select(<<<'SQL'
                SELECT tablename AS tabela, indexname AS nome, indexdef AS definicao
                  FROM pg_indexes WHERE schemaname = current_schema() AND tablename = ANY(?)
                 ORDER BY tablename, indexname
                SQL, ['{'.implode(',', self::TABELAS).'}']) : [],
            'restricoes' => $pg ? DB::select(<<<'SQL'
                SELECT conrelid::regclass::text AS tabela, conname AS nome, contype AS tipo, convalidated AS validada,
                       pg_get_constraintdef(oid) AS definicao
                  FROM pg_constraint
                 WHERE connamespace = current_schema()::regnamespace AND conrelid::regclass::text = ANY(?) AND contype IN ('c', 'u', 'f')
                 ORDER BY 1, 2
                SQL, ['{'.implode(',', self::TABELAS).'}']) : [],
        ]);
    }
}
