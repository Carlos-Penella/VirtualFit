<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    protected $table = 'recompensas';
    protected $fillable = [
        'nombre', 'descripcion', 'costo_fitcoins'
    ];
    public function canjeos() {
        return $this->hasMany(Canjeo::class, 'recompensa_id');
    }
}
