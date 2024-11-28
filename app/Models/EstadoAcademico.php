<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoAcademico extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    protected $table = 'estados_academicos';
}