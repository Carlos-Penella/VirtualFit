<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos_ejercicio_diario', function (Blueprint $table) {
            $table->boolean('recompensado')->default(false)->after('comentario');
            $table->integer('fitcoins_otorgados')->nullable()->after('recompensado');
        });
    }

    public function down(): void
    {
        Schema::table('videos_ejercicio_diario', function (Blueprint $table) {
            $table->dropColumn(['recompensado', 'fitcoins_otorgados']);
        });
    }
};
