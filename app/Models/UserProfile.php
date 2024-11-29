<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'birth_date', 'gender', 'photo', 'cedula', 'direccion_calle', 'direccion_ciudad', 'direccion_provincia', 'codigo_postal', 'numero_referencia', 'last_login_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}