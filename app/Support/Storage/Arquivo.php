<?php

namespace App\Support\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class Arquivo
{
    public function persiste(UploadedFile $arquivo, string $path, string $nome, array $options = []): string
    {
        return $arquivo->storeAs($path, $nome, $options);
    }

    public function remove(string $path, string $nome, ?string $disco = null): bool
    {
        return Storage::disk($disco)->delete("{$path}/{$nome}");
    }
}
