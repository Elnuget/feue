<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_documento',
        'ruta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}