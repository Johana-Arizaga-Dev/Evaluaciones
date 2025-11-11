<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use App\Models\Area;
use App\Models\TipoPregunta;
use App\Models\Pregunta;
use App\Models\OpcionPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ← AGREGAR ESTA LÍNEA

class FormularioController extends Controller
{
    public function __construct()
    {
        // Solo usuarios que pueden crear formularios pueden acceder
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->puedeCrearFormularios()) {
                abort(403, 'No tienes permisos para gestionar formularios.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $formularios = Formulario::with(['area', 'creador', 'preguntas'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('formularios.index', compact('formularios'));
    }

    public function create()
    {
        $areas = Area::orderBy('nombre_area')->get();
        $tiposPregunta = TipoPregunta::all();

        return view('formularios.create', compact('areas', 'tiposPregunta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:evaluacion,encuesta,diagnostico',
            'area_id' => 'nullable|exists:areas,id',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto_pregunta' => 'required|string',
            'preguntas.*.tipo_pregunta_id' => 'required|exists:tipo_preguntas,id',
        ]);

        try {
            DB::beginTransaction(); // ← Ahora funcionará

            // Crear el formulario
            $formulario = Formulario::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'area_id' => $request->area_id,
                'creado_por' => auth()->id(),
            ]);

            // Crear las preguntas
            foreach ($request->preguntas as $preguntaData) {
                // Determinar la ponderación automáticamente
                $ponderacion = $this->determinarPonderacion($preguntaData);

                $pregunta = $formulario->preguntas()->create([
                    'texto_pregunta' => $preguntaData['texto_pregunta'],
                    'tipo_pregunta_id' => $preguntaData['tipo_pregunta_id'],
                    'ponderacion' => $ponderacion,
                ]);

                // Crear opciones si el tipo de pregunta las requiere
                if (isset($preguntaData['opciones']) && is_array($preguntaData['opciones'])) {
                    foreach ($preguntaData['opciones'] as $opcionData) {
                        if (!empty($opcionData['texto_opcion'])) {
                            $pregunta->opciones()->create([
                                'texto_opcion' => $opcionData['texto_opcion'],
                                'valor' => $opcionData['valor'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('formularios.index')
                ->with('success', 'Formulario creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al crear el formulario: ' . $e->getMessage());
        }
    }

    public function show(Formulario $formulario)
    {
        $formulario->load(['area', 'creador', 'preguntas.opciones', 'preguntas.tipoPregunta']);

        return view('formularios.show', compact('formulario'));
    }

    public function edit(Formulario $formulario)
    {
        $areas = Area::orderBy('nombre_area')->get();
        $tiposPregunta = TipoPregunta::all();
        $formulario->load(['preguntas.opciones', 'preguntas.tipoPregunta']);

        return view('formularios.edit', compact('formulario', 'areas', 'tiposPregunta'));
    }

    public function update(Request $request, Formulario $formulario)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:evaluacion,encuesta,diagnostico',
            'area_id' => 'nullable|exists:areas,id',
        ]);

        $formulario->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'area_id' => $request->area_id,
        ]);

        return redirect()->route('formularios.index')
            ->with('success', 'Formulario actualizado exitosamente.');
    }

    public function destroy(Formulario $formulario)
    {
        // Verificar si el formulario tiene evaluaciones
        if ($formulario->evaluaciones()->exists()) {
            return redirect()->route('formularios.index')
                ->with('error', 'No se puede eliminar el formulario porque tiene evaluaciones relacionadas.');
        }

        $formulario->delete();

        return redirect()->route('formularios.index')
            ->with('success', 'Formulario eliminado exitosamente.');
    }

    /**
     * Determina la ponderación automáticamente según el tipo de pregunta
     */
    /**
 * Determina la ponderación automáticamente según el tipo de pregunta
 */
private function determinarPonderacion($preguntaData)
{
    $tipoPregunta = TipoPregunta::find($preguntaData['tipo_pregunta_id']);

    if (!$tipoPregunta) {
        return 1; // Valor por defecto
    }

    switch ($tipoPregunta->nombre_tipo) {
        case 'seleccion_unica':
            // Para selección única, usar el valor máximo de las opciones correctas
            if (isset($preguntaData['opciones']) && is_array($preguntaData['opciones'])) {
                $valores = array_column($preguntaData['opciones'], 'valor');
                $valoresPositivos = array_filter($valores, function($v) { return $v > 0; });
                return !empty($valoresPositivos) ? max($valoresPositivos) : 1;
            }
            return 1;

        case 'opcion_multiple':
            // Para opción múltiple, suma de todos los valores positivos
            if (isset($preguntaData['opciones']) && is_array($preguntaData['opciones'])) {
                $valores = array_column($preguntaData['opciones'], 'valor');
                $suma = array_sum(array_filter($valores, function($v) { return $v > 0; }));
                return $suma > 0 ? $suma : 1;
            }
            return 1;

        case 'escala_numerica':
            return 10; // Valor fijo más alto para escala numérica

        case 'texto_libre':
            return 5; // Valor fijo para texto libre

        case 'si_no':
            return 2; // Valor fijo para sí/no

        default:
            return 1;
    }
}

    /**
     * Recalcula la ponderación basada en las opciones reales creadas
     */
    private function recalcularPonderacion(Pregunta $pregunta)
    {
        $pregunta->calcularPonderacionAutomatica();
    }
}
