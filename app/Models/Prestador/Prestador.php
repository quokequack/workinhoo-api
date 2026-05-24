<?php

namespace App\Models\Prestador;

use App\Models\Localizacao\Bairro;
use App\Models\Localizacao\Cidade;
use App\Models\Orcamento\PrestadorOrcamento;
use App\Models\Usuario\Usuario;
use App\Support\ValueObjects\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestador extends Model
{
    use HasFactory;

    protected $table = 'prestadores';

    protected $fillable = [
        'uuid',
        'usuario_id',
        'descricao',
        'instagram',
        'cidade_id',
        'atende_cidade_toda',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'atende_cidade_toda' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Prestador $prestador) {
            $prestador->uuid = UUID::cria()->recupera();
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function especialidades(): BelongsToMany
    {
        return $this->belongsToMany(Especialidade::class, 'prestador_especialidades')
            ->withPivot('subcategorias');
    }

    public function bairros(): BelongsToMany
    {
        return $this->belongsToMany(Bairro::class, 'prestador_bairros');
    }

    public function solicitacoesOrcamento() : HasMany{
        return $this->hasMany(PrestadorOrcamento::class, 'prestador_id', 'id');
    }
}
