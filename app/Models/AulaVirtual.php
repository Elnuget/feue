<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaVirtual extends Model
{
    use HasFactory;

    protected $table = 'aulas_virtuales';

    protected $fillable = [
        'curso_id',
        'nombre',
        'descripcion'
    ];

    /**
     * Obtener los cursos asociados al aula virtual.
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'aula_virtual_curso');
    }

    /**
     * Obtener los usuarios (docentes/administradores) asociados al aula virtual.
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'aula_virtual_usuario')
                    ->where(function($query) {
                        $query->whereHas('roles', function($q) {
                            $q->where('name', 'Docente');
                        })
                        ->orWhereHas('roles', function($q) {
                            $q->where('id', 1); // Administrador
                        });
                    });
    }

    public function contenidos()
    {
        return $this->hasMany(AulaVirtualContenido::class);
    }

    public function cuestionarios()
    {
        return $this->hasMany(Cuestionario::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class)->orderBy('id', 'desc');
    }
}
