<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionDocente extends Model
{
    protected $table = 'sesiones_docentes';

    protected $fillable = [
        'user_id',
        'curso_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'aula',
        'materia',
        'tema_impartido',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'string',
        'hora_fin' => 'string'
    ];

    // Método para obtener la hora de inicio formateada
    public function getHoraInicioAttribute($value)
    {
        return substr($value, 0, 5);
    }

    // Método para obtener la hora de fin formateada
    public function getHoraFinAttribute($value)
    {
        return substr($value, 0, 5);
    }

    // Relación con el usuario (docente)
    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
} 