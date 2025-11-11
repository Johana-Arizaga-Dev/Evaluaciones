<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_puesto',
        'nivel_jerarquico',
    ];

    // Relaciones
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    // Método para verificar si puede evaluar a otro puesto
    public function puedeEvaluar(Puesto $puestoEvaluado)
    {
        return $this->nivel_jerarquico < $puestoEvaluado->nivel_jerarquico;
    }
}
