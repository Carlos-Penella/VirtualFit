<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    public $timestamps = false;
    protected $table = 'clases';
    protected $fillable = [
        'nombre', 'descripcion', 'fecha', 'hora_inicio','horario', 'hora_fin',  'aforo_max','entrenador_id'
    ];
    public function entrenador() {
        return $this->belongsTo(Usuario::class, 'entrenador_id');
    }
    public function usuarios() {
        return $this->belongsToMany(Usuario::class, 'clases_usuarios', 'clase_id', 'usuario_id');
    }

    public function plazasRestantes(): int
    {
        $inscritos = $this->usuarios()->count();
        return max(0, ($this->aforo_max ?? 20) - $inscritos);
    }
}
