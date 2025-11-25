<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   
public function up(): void
{
    Schema::table('chat_mensajes', function (Blueprint $table) {
        $table->unsignedBigInteger('entrenador_id')->nullable()->after('usuario_id');
        // $table->string('modo', 20)->default('assistant')->change(); // quitar esta línea
        $table->foreign('entrenador_id')
            ->references('id')
            ->on('usuarios')
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('chat_mensajes', function (Blueprint $table) {
        $table->dropForeign(['entrenador_id']);
        $table->dropColumn('entrenador_id');
    });
}
};
