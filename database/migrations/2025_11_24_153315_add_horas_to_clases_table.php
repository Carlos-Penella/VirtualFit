<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::table('clases', function (Blueprint $table) {
        if (!Schema::hasColumn('clases', 'hora_inicio')) {
            $table->time('hora_inicio')->nullable();
        }
        if (!Schema::hasColumn('clases', 'hora_fin')) {
            $table->time('hora_fin')->nullable();
        }
    });
}

public function down()
{
    Schema::table('clases', function (Blueprint $table) {
        if (Schema::hasColumn('clases', 'hora_fin')) {
            $table->dropColumn('hora_fin');
        }
        if (Schema::hasColumn('clases', 'hora_inicio')) {
            $table->dropColumn('hora_inicio');
        }
    });
}
};
