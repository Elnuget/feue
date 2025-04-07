<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcuerdoConfidencialidad extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'acuerdo_confidencialidad';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'estado',
        'acuerdo',
        'curso_id',
        'user_id',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'string',
    ];

    /**
     * Obtener el usuario al que pertenece el acuerdo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el curso al que pertenece el acuerdo.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
} 