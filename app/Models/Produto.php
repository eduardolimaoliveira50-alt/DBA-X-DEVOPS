<?php

namespace App\Models;

use App\Contracts\HasSelect2List;
use App\Http\Resources\Select2Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Produto extends Model implements HasSelect2List
{
    use LogsActivity, SoftDeletes;

    public const UNIDADES = [
        'UN' => 'Unidade',
        'CX' => 'Caixa',
        'PCT' => 'Pacote',
        'RESMA' => 'Resma',
        'L' => 'Litro',
        'KG' => 'Quilo',
        'PAR' => 'Par',
    ];

    protected $table = 'produtos';

    protected $fillable = ['codigo', 'nome', 'categoria_id', 'unidade_medida', 'estoque_minimo', 'ativo'];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'saldo' => 'integer',
            'estoque_minimo' => 'integer',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }

    /** Abaixo do estoque mínimo (só os ativos). */
    public function scopeAbaixoDoMinimo(Builder $query): Builder
    {
        return $query->where('ativo', true)->whereColumn('saldo', '<', 'estoque_minimo');
    }

    public function abaixoDoMinimo(): bool
    {
        return $this->ativo && $this->saldo < $this->estoque_minimo;
    }

    public function getNameAttribute(): string
    {
        return $this->codigo.' · '.$this->nome;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('produto')->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function getSelectList(?string $search = null, array $context = []): AnonymousResourceCollection
    {
        $search = trim((string) $search);

        $query = static::query()
            ->where('ativo', true)
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->whereLike('codigo', $search.'%')
                ->orWhereLike('nome', '%'.$search.'%')))
            ->orderBy('nome');

        return Select2Resource::collection($query->paginate(20));
    }
}
