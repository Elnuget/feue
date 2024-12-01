<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'curso_id',
        'fecha_matricula',
        'monto_total',
        'valor_pendiente',
        'estado_matricula',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}