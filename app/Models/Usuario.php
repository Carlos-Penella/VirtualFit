<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Clase;
use App\Models\RegistroEjercicio;
use App\Models\Racha;
use App\Models\Canjeo;
use App\Models\Seguimiento;
  
class Usuario extends Authenticatable
{
    // Laravel manejará automáticamente created_at y updated_at
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'fecha_registro',
        'tipo_usuario',
        'foto_entrenador'
    ];
    protected $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return 'correo';
    }
    // Ya no es necesario getAuthPassword(), Laravel usará 'password' por defecto

    public function membresias() {
        return $this->hasMany(Membresia::class, 'usuario_id');
    }
    public function pagos() {
        return $this->hasMany(Pago::class, 'usuario_id');
    }
    public function clases() {
        return $this->belongsToMany(Clase::class, 'clases_usuarios', 'usuario_id', 'clase_id');
    }
    public function registrosEjercicio() {
        return $this->hasMany(RegistroEjercicio::class, 'usuario_id');
    }
    public function racha() {
        return $this->hasOne(Racha::class, 'usuario_id');
    }
    public function canjeos() {
        return $this->hasMany(Canjeo::class, 'usuario_id');
    }
    public function seguimiento() {
        return $this->hasMany(Seguimiento::class, 'usuario_id');
    }
    public function entrenador()
    {
        return $this->belongsTo(Usuario::class, 'entrenador_id');
    }

    public function clientes()
    {
        return $this->hasMany(Usuario::class, 'entrenador_id');
    }
}
