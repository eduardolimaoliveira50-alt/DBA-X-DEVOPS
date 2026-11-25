<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FornecedorRequest;
use App\Models\Fornecedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FornecedorController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca'));
        $digitos = preg_replace('/\D/', '', $busca);
        $semCnpj = $request->boolean('sem_cnpj');

        $fornecedores = Fornecedor::query()
            ->withCount('movimentacoes')
            ->when($busca !== '', fn ($q) => $q->where(fn ($w) => $w
                ->whereLike('razao_social', '%'.$busca.'%')
                ->when($digitos !== '', fn ($c) => $c->orWhere('cnpj', 'like', $digitos.'%'))))
            ->when($semCnpj, fn ($q) => $q->whereNull('cnpj'))
            ->orderBy('razao_social')
            ->paginate(50)
            ->withQueryString();

        return view('admin.fornecedores.index', [
            'fornecedores' => $fornecedores,
            'busca' => $busca,
            'semCnpj' => $semCnpj,
            'totalSemCnpj' => Fornecedor::query()->whereNull('cnpj')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.fornecedores.form', ['fornecedor' => new Fornecedor]);
    }

    public function store(FornecedorRequest $request): RedirectResponse
    {
        Fornecedor::query()->create($request->validated());

        return redirect()->route('admin.fornecedores.index')->with('status', 'Fornecedor cadastrado.');
    }

    public function edit(Fornecedor $fornecedor): View
    {
        return view('admin.fornecedores.form', ['fornecedor' => $fornecedor]);
    }

    public function update(FornecedorRequest $request, Fornecedor $fornecedor): RedirectResponse
    {
        $fornecedor->update($request->validated());

        return redirect()->route('admin.fornecedores.index')->with('status', 'Fornecedor atualizado.');
    }

    public function destroy(Fornecedor $fornecedor): RedirectResponse
    {
        $fornecedor->delete();

        return redirect()->route('admin.fornecedores.index')->with('status', "Fornecedor {$fornecedor->razao_social} excluído.");
    }
}
