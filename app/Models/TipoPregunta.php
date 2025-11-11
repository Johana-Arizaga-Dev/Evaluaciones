<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPregunta extends Model
{
    use HasFactory;

    protected $table = 'tipo_preguntas';

    protected $fillable = [
        'nombre_tipo',
        
    ];

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'tipo_pregunta_id');
    }
}
