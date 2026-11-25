<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $desde = now()->subDays(30);

        return view('home', [
            'stats' => [
                'produtos' => Produto::query()->where('ativo', true)->count(),
                'abaixo' => Produto::query()->abaixoDoMinimo()->count(),
                'entradas' => (int) Movimentacao::query()->where('tipo', 'entrada')->where('created_at', '>=', $desde)->sum('quantidade'),
                'saidas' => (int) Movimentacao::query()->where('tipo', 'saida')->where('created_at', '>=', $desde)->sum('quantidade'),
            ],
            'criticos' => Produto::query()->abaixoDoMinimo()->with('categoria')
                ->orderByRaw('saldo - estoque_minimo')->limit(8)->get(),
            'ultimas' => Movimentacao::query()->with(['produto', 'user'])->latest('id')->limit(8)->get(),
        ]);
    }
}
