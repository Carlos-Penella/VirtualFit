<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimiento';
    protected $fillable = [
        'usuario_id', 'peso', 'calorias_diarias', 'pasos_diarios', 'fecha'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
