<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos_ejercicio_diario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('ejercicio_id');
            $table->date('fecha');
            $table->string('ruta_video');
            $table->string('comentario')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('ejercicio_id')->references('id')->on('ejercicios')->onDelete('cascade');
            $table->unique(['usuario_id', 'ejercicio_id', 'fecha'], 'video_unico_por_dia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos_ejercicio_diario');
    }
};
