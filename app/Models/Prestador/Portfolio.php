<?php

namespace App\Models\Prestador;

use App\Support\ValueObjects\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// propriedade para phpstan
/**
 * @property Prestador $prestador
 */
class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolios_prestadores';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'uuid',
        'prestador_id',
        'descricao',
        'midia_path',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Portfolio $portfolio) {
            $portfolio->uuid = UUID::cria()->recupera();
        });
    }

    public function prestador(): BelongsTo
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return $this->where('id', $value)
            ->orWhere('uuid', $value)
            ->firstOrFail();
    }
}
