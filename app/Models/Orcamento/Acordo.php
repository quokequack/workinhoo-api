<?php

namespace App\Models\Orcamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read PrestadorOrcamento|null $orcamento
 */
class Acordo extends Model
{
    use HasFactory;

    protected $table = 'acordos';

    protected $fillable = ['id', 'orcamento_id', 'finalizado'];

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(PrestadorOrcamento::class, 'orcamento_id', 'id');
    }
}
