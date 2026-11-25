<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovimentacaoRequest;
use App\Models\Movimentacao;
use App\Models\Produto;
use App\Services\Estoque;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovimentacaoController extends Controller
{
    public function index(Request $request): View
    {
        $tipo = (string) $request->query('tipo');
        $produtoId = $request->integer('produto') ?: null;
        $de = $request->date('de');
        $ate = $request->date('ate');

        $movimentacoes = Movimentacao::query()
            ->with(['produto', 'fornecedor', 'user'])
            ->when(isset(Movimentacao::TIPOS[$tipo]), fn ($q) => $q->where('tipo', $tipo))
            ->when($produtoId, fn ($q) => $q->where('produto_id', $produtoId))
            ->when($de, fn ($q) => $q->where('created_at', '>=', $de->startOfDay()))
            ->when($ate, fn ($q) => $q->where('created_at', '<=', $ate->endOfDay()))
            ->latest('created_at')
            ->latest('id')
            ->simplePaginate(50)
            ->withQueryString();

        return view('admin.movimentacoes.index', [
            'movimentacoes' => $movimentacoes,
            'produto' => $produtoId ? Produto::withTrashed()->find($produtoId) : null,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.movimentacoes.form', [
            'tipo' => $request->query('tipo') === 'saida' ? 'saida' : 'entrada',
            'produtoId' => $request->integer('produto') ?: null,
        ]);
    }

    public function store(MovimentacaoRequest $request, Estoque $estoque): RedirectResponse
    {
        $movimentacao = $estoque->registrar($request->validated(), $request->user()->id);
        $produto = $movimentacao->produto;

        return redirect()->route('admin.movimentacoes.index')->with('status', sprintf('%s de %d %s de %s registrada. Saldo: %d.',
            Movimentacao::TIPOS[$movimentacao->tipo], $movimentacao->quantidade, $produto->unidade_medida, $produto->nome, $produto->saldo));
    }
}
