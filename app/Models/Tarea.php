<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite',
        'puntos_maximos',
        'aula_virtual_id',
        'archivos',
        'imagenes'
    ];

    protected $casts = [
        'fecha_limite' => 'datetime',
        'archivos' => 'array',
        'imagenes' => 'array'
    ];

    public function aulaVirtual()
    {
        return $this->belongsTo(AulaVirtual::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
} 