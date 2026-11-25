<?php

namespace App\Services;

use App\Models\Movimentacao;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Entradas e saídas de material. O saldo do produto e a movimentação mudam juntos, numa transação, com a linha
 * do produto travada (SELECT ... FOR UPDATE): duas saídas simultâneas não deixam o saldo negativo.
 */
class Estoque
{
    /**
     * @param  array{produto_id:int, tipo:string, quantidade:int, fornecedor_id?:int|null, documento?:string|null, observacao?:string|null}  $dados
     */
    public function registrar(array $dados, ?int $userId): Movimentacao
    {
        return DB::transaction(function () use ($dados, $userId) {
            /** @var Produto $produto */
            $produto = Produto::query()->lockForUpdate()->findOrFail($dados['produto_id']);
            $quantidade = (int) $dados['quantidade'];

            if ($dados['tipo'] === 'saida' && $quantidade > $produto->saldo) {
                throw ValidationException::withMessages([
                    'quantidade' => "Saldo insuficiente: há {$produto->saldo} {$produto->unidade_medida} de {$produto->nome}.",
                ]);
            }

            $produto->saldo += $dados['tipo'] === 'entrada' ? $quantidade : -$quantidade;
            $produto->save();

            return Movimentacao::query()->create([
                'produto_id' => $produto->id,
                'fornecedor_id' => $dados['tipo'] === 'entrada' ? ($dados['fornecedor_id'] ?? null) : null,
                'user_id' => $userId,
                'tipo' => $dados['tipo'],
                'quantidade' => $quantidade,
                'documento' => $dados['documento'] ?? null,
                'observacao' => $dados['observacao'] ?? null,
            ]);
        });
    }
}
