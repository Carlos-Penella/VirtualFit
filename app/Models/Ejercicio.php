<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    protected $table = 'ejercicios';
    protected $fillable = [
        'nombre', 'tipo', 'descripcion'
    ];
    public function registros() {
        return $this->hasMany(RegistroEjercicio::class, 'ejercicio_id');
    }
}
