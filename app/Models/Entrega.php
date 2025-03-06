<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = [
        'archivo',
        'enlace',
        'calificacion',
        'comentarios',
        'tarea_id',
        'user_id',
        'fecha_entrega'
    ];

    protected $casts = [
        'calificacion' => 'decimal:2',
        'fecha_entrega' => 'datetime'
    ];

    // Relación con la tarea
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    // Relación con el usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Método para verificar si está calificada
    public function estaCalificada(): bool
    {
        return !is_null($this->calificacion);
    }

    // Método para verificar si está entregada a tiempo
    public function entregadaATiempo(): bool
    {
        if (!$this->fecha_entrega || !$this->tarea) {
            return false;
        }

        return $this->fecha_entrega <= $this->tarea->fecha_limite;
    }
} 