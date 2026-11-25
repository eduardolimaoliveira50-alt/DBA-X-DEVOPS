<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca'));
        $categoria = $request->integer('categoria') ?: null;
        $situacao = (string) $request->query('situacao');

        $produtos = Produto::query()
            ->with('categoria')
            ->when($busca !== '', fn ($q) => $q->where(fn ($w) => $w
                ->whereLike('codigo', $busca.'%')
                ->orWhereLike('nome', '%'.$busca.'%')))
            ->when($categoria, fn ($q) => $q->where('categoria_id', $categoria))
            ->when($situacao === 'abaixo', fn ($q) => $q->abaixoDoMinimo())
            ->when($situacao === 'inativos', fn ($q) => $q->where('ativo', false))
            ->orderBy('nome')
            ->paginate(50)
            ->withQueryString();

        return view('admin.produtos.index', [
            'produtos' => $produtos,
            'busca' => $busca,
            'categorias' => Categoria::query()->orderBy('nome')->pluck('nome', 'id'),
        ]);
    }

    public function create(): View
    {
        return view('admin.produtos.form', ['produto' => new Produto(['ativo' => true, 'unidade_medida' => 'UN']), 'categorias' => $this->categorias()]);
    }

    public function store(ProdutoRequest $request): RedirectResponse
    {
        Produto::query()->create($request->validated());

        return redirect()->route('admin.produtos.index')->with('status', 'Produto cadastrado.');
    }

    public function edit(Produto $produto): View
    {
        return view('admin.produtos.form', ['produto' => $produto, 'categorias' => $this->categorias()]);
    }

    public function update(ProdutoRequest $request, Produto $produto): RedirectResponse
    {
        $produto->update($request->validated());

        return redirect()->route('admin.produtos.index')->with('status', 'Produto atualizado.');
    }

    /** Exclusão lógica: as movimentações continuam mostrando o produto. */
    public function destroy(Produto $produto): RedirectResponse
    {
        if ($produto->saldo > 0) {
            return back()->with('error', "O produto {$produto->codigo} ainda tem saldo: registre a saída antes de excluir.");
        }

        $produto->delete();

        return redirect()->route('admin.produtos.index')->with('status', "Produto {$produto->codigo} excluído.");
    }

    private function categorias()
    {
        return Categoria::query()->orderBy('nome')->pluck('nome', 'id');
    }
}
