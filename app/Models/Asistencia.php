<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asistencia    extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'fecha_hora',
        'hora_entrada',
        'hora_salida',
        'estado'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
    ];

    // Definir los estados posibles como constantes
    const ESTADO_PRESENTE = 'presente';
    const ESTADO_AUSENTE = 'ausente';
    const ESTADO_TARDANZA = 'tardanza';
    const ESTADO_FUGA = 'fuga';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrarEntrada()
    {
        $this->hora_entrada = now();
        $this->estado = self::ESTADO_PRESENTE;
        
        // Si la entrada es después de cierta hora, marcar como tardanza
        $horaLimite = Carbon::createFromTime(8, 15, 0);
        if (Carbon::parse($this->hora_entrada)->gt($horaLimite)) {
            $this->estado = self::ESTADO_TARDANZA;
        }
        
        $this->save();
    }

    public function registrarSalida()
    {
        $this->hora_salida = now();
        $this->save();
    }

    public function marcarComoFuga()
    {
        $this->estado = self::ESTADO_FUGA;
        if (!$this->hora_salida) {
            $this->hora_salida = now();
        }
        $this->save();
    }
}