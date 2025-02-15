<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    protected $fillable = [
        'aula_virtual_id',
        'titulo',
        'descripcion',
        'tiempo_limite',
        'intentos_permitidos',
        'activo',
        'permite_revision',
        'retroalimentacion',
        'config_revision'
    ];

    protected $casts = [
        'config_revision' => 'array',
        'permite_revision' => 'boolean',
        'activo' => 'boolean'
    ];

    public function aulaVirtual()
    {
        return $this->belongsTo(AulaVirtual::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    public function intentos()
    {
        return $this->hasMany(IntentoCuestionario::class);
    }
} 