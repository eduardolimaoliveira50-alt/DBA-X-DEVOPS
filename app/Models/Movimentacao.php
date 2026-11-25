<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    public const TIPOS = [
        'entrada' => 'Entrada',
        'saida' => 'Saída',
    ];

    protected $table = 'movimentacoes';

    protected $fillable = ['produto_id', 'fornecedor_id', 'user_id', 'tipo', 'quantidade', 'documento', 'observacao'];

    protected function casts(): array
    {
        return ['quantidade' => 'integer'];
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class)->withTrashed();
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function isEntrada(): bool
    {
        return $this->tipo === 'entrada';
    }
}
