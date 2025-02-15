<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntentoCuestionario extends Model
{
    protected $table = 'intentos_cuestionario';
    
    protected $fillable = [
        'cuestionario_id',
        'usuario_id',
        'inicio',
        'fin',
        'calificacion',
        'numero_intento'
    ];

    protected $dates = [
        'inicio',
        'fin'
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaUsuario::class, 'intento_id');
    }
} 