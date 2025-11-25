<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // La tabla 'clases' ya existe en la base de datos,
        // por lo que esta migración no debe volver a crearla.
        // Si en el futuro necesitas añadir columnas (fecha, hora_inicio, etc.)
        // hazlo con una migración de "alter table" separada.
    }

    public function down(): void
    {
        // No eliminamos la tabla existente para no perder datos.
        // Deja vacío o gestiona una reversión específica si lo necesitas.
    }
};
