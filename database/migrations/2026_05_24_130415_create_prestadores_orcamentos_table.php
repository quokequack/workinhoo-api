<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestadores_orcamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('prestador_id');
            $table->unsignedBigInteger('especialidade_prestador_id');
            $table->longText('descricao');
            $table->decimal('valor', 8, 2)->nullable();
            $table->boolean('aceito')->nullable();
            $table->longText('observacao_prestador')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('usuarios');
            $table->foreign('prestador_id')->references('id')->on('prestadores');
            $table->foreign('especialidade_prestador_id')->references('id')->on('especialidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestadores_orcamentos');
    }
};
