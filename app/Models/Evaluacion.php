<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla explícitamente
    protected $table = 'evaluaciones';

    protected $fillable = [
        'formulario_id',
        'evaluador_id',
        'evaluado_id',
        'fecha_evaluacion',
        'calificacion_total',
        'puntaje_obtenido',
        'porcentaje_obtenido',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
        'porcentaje_obtenido' => 'decimal:2',
    ];

    // Relaciones
    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function evaluador()
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }

    public function evaluado()
    {
        return $this->belongsTo(User::class, 'evaluado_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    // Método para calcular resultados
    public function calcularResultados()
    {
        $totalPuntaje = $this->respuestas()->sum('puntaje');
        $totalPosible = $this->formulario->preguntas()->sum('ponderacion');

        $this->puntaje_obtenido = $totalPuntaje;
        $this->calificacion_total = $totalPosible;
        $this->porcentaje_obtenido = $totalPosible > 0 ? ($totalPuntaje / $totalPosible) * 100 : 0;
        $this->save();
    }
}
