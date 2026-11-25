<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/**
 * Painel do laboratório: mostra em que fase do Expand-Contract o banco publicado está,
 * quais migrations já rodaram e como a versão atual da aplicação lê os clientes.
 * Funciona em todas as fases, porque só consulta o catálogo e o ClienteService da versão publicada.
 */
class PainelController extends Controller
{
    public function __invoke(ClienteService $clientes): View
    {
        $colunas = collect(Schema::getColumns('clientes'))->keyBy('name');
        $temEmail = $colunas->has('email');
        $temCorporativo = $colunas->has('email_corporativo');

        $fase = match (true) {
            ! $temCorporativo => ['Estado inicial', 'Somente a coluna email (NOT NULL).'],
            $temEmail && ! $colunas['email']['nullable'] => ['Expand + Migrate (coexistência)', 'As duas colunas existem; a aplicação grava nas duas e lê email.'],
            $temEmail => ['Contract: preparação', 'email virou anulável; a aplicação usa só email_corporativo.'],
            default => ['Contract concluído', 'A coluna antiga foi removida; só email_corporativo existe.'],
        };

        $indicadores = ['Clientes' => DB::table('clientes')->count()];
        if ($temCorporativo) {
            $indicadores['email_corporativo nulo'] = DB::table('clientes')->whereNull('email_corporativo')->count();
        }
        if ($temEmail && $temCorporativo) {
            $indicadores['email ≠ email_corporativo'] = DB::table('clientes')->whereNotNull('email')
                ->whereColumn('email', '<>', 'email_corporativo')->count();
        }

        return view('painel', [
            'fase' => $fase,
            'colunas' => $colunas->only(['id', 'email', 'email_corporativo'])->values(),
            'indicadores' => $indicadores,
            'migrations' => DB::table('migrations')->orderBy('id')->get(['migration', 'batch']),
            'amostra' => array_slice($clientes->emails(), 0, 8),
            'banco' => DB::selectOne('select version() as v')->v,
            'versao' => substr((string) env('RENDER_GIT_COMMIT', 'local'), 0, 7),
        ]);
    }
}
