<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $fillable = [
        'cuestionario_id',
        'pregunta',
        'tipo'
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class);
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }
} 