<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAcademico extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'estado_academico_id',
        'acta_grado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estadoAcademico()
    {
        return $this->belongsTo(EstadoAcademico::class, 'estado_academico_id');
    }
}