<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    use HasFactory;

    protected $table = 'cuestionarios';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tiempo_limite',
        'intentos_permitidos',
        'permite_revision',
        'activo',
        'aula_virtual_id',
        'fecha_inicio',
        'fecha_fin',
        'retroalimentacion',
        'config_revision'
    ];

    protected $casts = [
        'permite_revision' => 'boolean',
        'activo' => 'boolean',
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