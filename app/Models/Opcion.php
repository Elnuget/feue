<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    protected $table = 'opciones';
    
    protected $fillable = [
        'pregunta_id',
        'texto',
        'es_correcta'
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
} 