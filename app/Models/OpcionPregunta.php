<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionPregunta extends Model
{
    use HasFactory;

    // CORREGIR: Coincidir con migración
    protected $table = 'opciones_preguntas'; // ← Cambiar a plural

    protected $fillable = [
        'pregunta_id',
        'texto_opcion',
        'valor',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}
