<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroEjercicio extends Model
{
    protected $table = 'registros_ejercicio';
    protected $fillable = [
        'usuario_id', 'ejercicio_id', 'fecha', 'valor', 'fitcoins'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    public function ejercicio() {
        return $this->belongsTo(Ejercicio::class, 'ejercicio_id');
    }
}
