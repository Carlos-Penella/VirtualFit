<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canjeo extends Model
{
    protected $table = 'canjeos';
    public $timestamps = false;
    protected $fillable = [
        'usuario_id', 'recompensa_id', 'fecha'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    public function recompensa() {
        return $this->belongsTo(Recompensa::class, 'recompensa_id');
    }
}
