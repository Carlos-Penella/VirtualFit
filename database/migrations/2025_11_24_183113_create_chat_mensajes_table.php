
  <?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_mensajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('entrenador_id')->nullable();
            $table->string('modo', 20)->default('trainer'); // 'assistant' o 'trainer'
            $table->text('mensaje'); // mensaje del usuario
            $table->text('respuesta_entrenador')->nullable(); // respuesta escrita por el entrenador
            $table->timestamp('respondido_en')->nullable(); // cuándo respondió el entrenador
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->cascadeOnDelete();
            $table->foreign('entrenador_id')->references('id')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_mensajes');
    }
};
