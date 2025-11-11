<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;

    protected $table = 'formularios';

    protected $fillable = [
        'titulo',
        'descripcion',
        'area_id',
        'tipo',
        'creado_por',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}
