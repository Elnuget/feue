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

    public function isComplete()
    {
        return $this->phone && $this->birth_date && $this->gender && $this->cedula && $this->direccion_calle && $this->direccion_ciudad && $this->direccion_provincia && $this->codigo_postal && $this->numero_referencia;
    }
}