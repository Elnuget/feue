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
        'tipo_pago',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}