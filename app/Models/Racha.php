<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Racha extends Model
{
    protected $table = 'rachas';
    public $timestamps = false;
    protected $fillable = [
        'usuario_id', 'dias_consecutivos', 'ultima_actividad', 'fitcoins_ganados'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
