<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoEjercicioDiario extends Model
{
    protected $table = 'videos_ejercicio_diario';

    protected $fillable = [
        'usuario_id',
        'ejercicio_id',
        'fecha',
        'ruta_video',
        'comentario',
        'recompensado',
        'fitcoins_otorgados',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function ejercicio()
    {
        return $this->belongsTo(Ejercicio::class, 'ejercicio_id');
    }
}
