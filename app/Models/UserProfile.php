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
        $requiredFields = [
            'phone',
            'birth_date',
            'gender',
            'cedula',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($this->$field) || empty($this->$field)) {
                \Log::info("Campo vacío o no definido: {$field}");
                return false;
            }
        }

        if (!$this->user) {
            \Log::info("Usuario no encontrado para el perfil ID: {$this->id}");
            return false;
        }

        \Log::info("Perfil completo para el usuario ID: {$this->user_id}");
        return true;
    }
}