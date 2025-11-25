<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    public $timestamps = false;
    protected $table = 'membresias';
    protected $fillable = [
        'usuario_id', 'tipo', 'fecha_inicio', 'fecha_fin', 'activo'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
