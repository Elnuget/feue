<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'metodo_pago_id',
        'comprobante_pago',
        'monto',
        'fecha_pago',
        'valor_pendiente',
        'fecha_proximo_pago',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }
}