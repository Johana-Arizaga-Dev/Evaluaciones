<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_area',
        'descripcion', // ← Agregar este campo que falta
    ];

    // Relación con formularios
    public function formularios()
    {
        return $this->hasMany(Formulario::class);
    }

    // Agregar relación con usuarios si es necesaria
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
