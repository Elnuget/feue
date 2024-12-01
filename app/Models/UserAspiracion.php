<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAspiracion extends Model
{
    use HasFactory;

    protected $table = 'user_aspiraciones';

    protected $fillable = [
        'user_id',
        'universidad_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function universidad()
    {
        return $this->belongsTo(Universidad::class);
    }
}