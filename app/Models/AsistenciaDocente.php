<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsistenciaDocente extends Model
{
    protected $table = 'asistencias_docentes';

    protected $fillable = [
        'user_id',
        'fecha',
        'hora_entrada',
        'estado',
        'sesion_docente_id',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime',
    ];

    // Relación con el usuario (docente)
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con la sesión del docente
    public function sesion(): BelongsTo
    {
        return $this->belongsTo(SesionDocente::class, 'sesion_docente_id');
    }
} 