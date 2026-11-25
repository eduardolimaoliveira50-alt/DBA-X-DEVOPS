<?php

namespace App\Models;

use App\Contracts\HasSelect2List;
use App\Http\Resources\Select2Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fornecedor extends Model implements HasSelect2List
{
    use LogsActivity, SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = ['razao_social', 'cnpj', 'email', 'telefone'];

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }

    public function getNameAttribute(): string
    {
        return $this->razao_social;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('fornecedor')->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public static function getSelectList(?string $search = null, array $context = []): AnonymousResourceCollection
    {
        $search = trim((string) $search);
        $digitos = preg_replace('/\D/', '', $search);

        $query = static::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->whereLike('razao_social', '%'.$search.'%')
                ->when($digitos !== '', fn ($c) => $c->orWhere('cnpj', 'like', $digitos.'%'))))
            ->orderBy('razao_social');

        return Select2Resource::collection($query->paginate(20));
    }
}
