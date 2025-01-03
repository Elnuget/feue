<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'estado', 'tipo_curso_id', 'imagen', 'horario'];

    public function tipoCurso()
    {
        return $this->belongsTo(TipoCurso::class, 'tipo_curso_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}