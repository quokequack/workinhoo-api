<?php

namespace App\Models\Orcamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recibo extends Model
{
    use HasFactory;

    protected $table = 'recibos';

    protected $fillable = ['id', 'acordo_id', 'data_servico'];

    public function acordo(): BelongsTo
    {
        return $this->belongsTo(Acordo::class, 'acordo_id', 'id');
    }
}
