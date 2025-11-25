<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMensaje extends Model
{
    protected $table = 'chat_mensajes';

    protected $fillable = [
        'usuario_id',
        'modo',
        'mensaje',
        'entrenador_id',
		'respuesta_entrenador',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function entrenador()
    {
        return $this->belongsTo(Usuario::class, 'entrenador_id');
    }
}
