<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AulaVirtualContenido extends Model
{
    protected $fillable = ['titulo', 'contenido', 'enlace', 'archivo', 'aula_virtual_id'];

    public function aulaVirtual()
    {
        return $this->belongsTo(AulaVirtual::class);
    }
} 