<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaUsuario extends Model
{
    protected $table = 'respuestas_usuario';
    
    protected $fillable = [
        'intento_id',
        'pregunta_id',
        'opcion_id'
    ];

    public function intento()
    {
        return $this->belongsTo(IntentoCuestionario::class, 'intento_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function opcion()
    {
        return $this->belongsTo(Opcion::class);
    }
} 