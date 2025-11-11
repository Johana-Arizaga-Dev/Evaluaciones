<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    // CORREGIR: Coincidir con migración
    protected $fillable = [
        'formulario_id',
        'texto_pregunta', // ← Cambiar de 'pregunta' a 'texto_pregunta'
        'tipo_pregunta_id', // ← Cambiar de 'tipo_pregunta' a 'tipo_pregunta_id'
        'ponderacion',
    ];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function tipoPregunta() // ← CORREGIR nombre de relación
    {
        return $this->belongsTo(TipoPregunta::class, 'tipo_pregunta_id');
    }

    public function opciones()
    {
        return $this->hasMany(OpcionPregunta::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
