<?php

namespace App\Models\Orcamento;

use App\Models\Prestador\Prestador;
use App\Models\Prestador\PrestadorEspecialidade;
use App\Models\Usuario\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PrestadorOrcamento extends Model
{
    protected $table = 'prestadores_orcamentos';

    protected $fillable = [
        'id',
        'solicitante_id',
        'prestador_id',
        'especialidade_prestador_id',
        'descricao',
        'valor',
        'observacao_prestador',
        'aceito',
    ];

    public function solicitante(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id', 'solicitante_id');
    }

    public function prestador(): HasOne
    {
        return $this->hasOne(Prestador::class, 'id', 'prestador_id');
    }

    public function usuario(): HasOneThrough
    {
        return $this->hasOneThrough(
            Usuario::class,
            Prestador::class,
            'id',
            'id',
            'prestador_id',
            'usuario_id');
    }

    public function especialidadePrestador(): HasOne
    {
        return $this->hasOne(PrestadorEspecialidade::class, 'id', 'especialidade_prestador_id');
    }
}
