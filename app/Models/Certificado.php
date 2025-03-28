<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $table = 'certificados';

    protected $fillable = [
        'usuario_id',
        'nombre_completo',
        'nombre_curso',
        'horas_curso',
        'sede_curso',
        'fecha_emision',
        'anio_emision',
        'numero_certificado',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'estado' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function matriculas()
    {
        return $this->hasOneThrough(
            \App\Models\Matricula::class,
            \App\Models\User::class,
            'id', // Clave foránea en users
            'usuario_id', // Clave foránea en matriculas
            'usuario_id', // Clave local en certificados
            'id' // Clave local en users
        );
    }
} 