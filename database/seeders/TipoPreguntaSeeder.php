<?php

namespace Database\Seeders;

use App\Models\TipoPregunta;
use Illuminate\Database\Seeder;

class TipoPreguntaSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            [
                'nombre_tipo' => 'opcion_multiple',
                'descripcion' => 'Pregunta de opción múltiple con una sola respuesta'
            ],
            [
                'nombre_tipo' => 'casillas',
                'descripcion' => 'Pregunta con múltiples opciones seleccionables'
            ],
            [
                'nombre_tipo' => 'escala_numerica',
                'descripcion' => 'Pregunta con respuesta numérica en escala'
            ],
            [
                'nombre_tipo' => 'texto_libre',
                'descripcion' => 'Pregunta abierta con respuesta de texto libre'
            ],
            [
                'nombre_tipo' => 'si_no',
                'descripcion' => 'Pregunta con respuesta Sí/No'
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoPregunta::create($tipo);
        }
    }
}
