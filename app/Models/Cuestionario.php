<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'tiempo_limite',
        'intentos_permitidos',
        'permite_revision',
        'retroalimentacion',
        'activo',
        'aula_virtual_id',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'config_revision' => 'array',
        'permite_revision' => 'boolean',
        'activo' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime'
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