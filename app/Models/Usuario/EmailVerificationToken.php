<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    protected $table = 'email_verification_tokens';

    protected $primaryKey = 'email';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['email', 'token', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'hashed',
        ];
    }

    public static function porToken(string $token): ?EmailVerificationToken
    {
        return self::query()->where('token', $token)->first();
    }
}
