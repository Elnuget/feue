<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    protected $table = 'tipos_cursos';

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'tipo_curso_id');
    }
}