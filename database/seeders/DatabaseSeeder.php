<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Puesto;
use App\Models\TipoPregunta;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear puestos
        $puestos = [
            ['nombre_puesto' => 'Director General', 'nivel_jerarquico' => 1],
            ['nombre_puesto' => 'Gerente', 'nivel_jerarquico' => 2],
            ['nombre_puesto' => 'Supervisor', 'nivel_jerarquico' => 3],
            ['nombre_puesto' => 'Líder de Equipo', 'nivel_jerarquico' => 4],
            ['nombre_puesto' => 'Especialista Senior', 'nivel_jerarquico' => 5],
            ['nombre_puesto' => 'Especialista', 'nivel_jerarquico' => 6],
            ['nombre_puesto' => 'Analista', 'nivel_jerarquico' => 7],
            ['nombre_puesto' => 'Asistente', 'nivel_jerarquico' => 8],
            ['nombre_puesto' => 'Practicante', 'nivel_jerarquico' => 9],
        ];

        foreach ($puestos as $puesto) {
            Puesto::create($puesto);
        }

        // Crear tipos de pregunta
        $tiposPregunta = [
            ['nombre_tipo' => 'opcion_multiple', 'descripcion' => 'Selección múltiple'],
            ['nombre_tipo' => 'seleccion_unica', 'descripcion' => 'Selección única'],
            ['nombre_tipo' => 'texto_libre', 'descripcion' => 'Respuesta de texto libre'],
            ['nombre_tipo' => 'escala_numerica', 'descripcion' => 'Escala numérica'],
            ['nombre_tipo' => 'si_no', 'descripcion' => 'Si/No'],
        ];

        foreach ($tiposPregunta as $tipo) {
            TipoPregunta::create($tipo);
        }
    }
}
