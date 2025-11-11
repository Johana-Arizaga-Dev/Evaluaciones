<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Formulario;
use App\Models\User;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::with(['formulario', 'evaluador', 'evaluado.puesto'])
            ->where('evaluador_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'formulario_id' => 'required|exists:formularios,id',
            'evaluado_id' => 'required|exists:users,id',
        ]);

        $formulario = Formulario::with(['preguntas.opciones', 'preguntas.tipoPregunta'])
            ->findOrFail($request->formulario_id);

        $evaluado = User::with('puesto')->findOrFail($request->evaluado_id);
        $evaluador = auth()->user();

        // Verificar que el evaluador puede evaluar al evaluado
        if (!$evaluador->puedeEvaluarUsuario($evaluado)) {
            return redirect()->route('evaluaciones.pendientes')
                ->with('error', 'No tienes permisos para evaluar a este usuario.');
        }

        // Verificar que no existe una evaluación previa
        $evaluacionExistente = Evaluacion::where('formulario_id', $request->formulario_id)
            ->where('evaluador_id', $evaluador->id)
            ->where('evaluado_id', $request->evaluado_id)
            ->exists();

        if ($evaluacionExistente) {
            return redirect()->route('evaluaciones.pendientes')
                ->with('error', 'Ya has evaluado a este usuario con este formulario.');
        }

        return view('evaluaciones.create', compact('formulario', 'evaluado', 'evaluador'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'formulario_id' => 'required|exists:formularios,id',
            'evaluado_id' => 'required|exists:users,id',
            'respuestas' => 'required|array',
            'respuestas.*.pregunta_id' => 'required|exists:preguntas,id',
        ]);

        $evaluador = auth()->user();
        $evaluado = User::findOrFail($request->evaluado_id);
        $formulario = Formulario::findOrFail($request->formulario_id);

        // Verificar permisos
        if (!$evaluador->puedeEvaluarUsuario($evaluado)) {
            return back()->with('error', 'No tienes permisos para evaluar a este usuario.');
        }

        try {
            DB::beginTransaction();

            // Crear la evaluación
            $evaluacion = Evaluacion::create([
                'formulario_id' => $request->formulario_id,
                'evaluador_id' => $evaluador->id,
                'evaluado_id' => $request->evaluado_id,
                'fecha_evaluacion' => now(),
                'calificacion_total' => 0,
                'puntaje_obtenido' => 0,
                'porcentaje_obtenido' => 0,
            ]);

            $puntajeTotal = 0;
            $puntajeObtenido = 0;

            // Procesar respuestas
            foreach ($request->respuestas as $respuestaData) {
                $pregunta = Pregunta::with('tipoPregunta', 'opciones')->findOrFail($respuestaData['pregunta_id']);
                $puntajePregunta = $this->calcularPuntajePregunta($pregunta, $respuestaData);

                // Preparar valor_respuesta según el tipo de pregunta
                $valorRespuesta = $this->prepararValorRespuesta($pregunta, $respuestaData);

                $evaluacion->respuestas()->create([
                    'pregunta_id' => $respuestaData['pregunta_id'],
                    'valor_respuesta' => $valorRespuesta,
                    'puntaje' => $puntajePregunta,
                    'comentarios' => $respuestaData['comentarios'] ?? null,
                ]);

                $puntajeTotal += $pregunta->ponderacion;
                $puntajeObtenido += $puntajePregunta;
            }

            // Calcular resultados finales
            $porcentaje = $puntajeTotal > 0 ? ($puntajeObtenido / $puntajeTotal) * 100 : 0;

            $evaluacion->update([
                'calificacion_total' => $puntajeTotal,
                'puntaje_obtenido' => $puntajeObtenido,
                'porcentaje_obtenido' => $porcentaje,
            ]);

            DB::commit();

            return redirect()->route('evaluaciones.show', $evaluacion)
                ->with('success', 'Evaluación completada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la evaluación: ' . $e->getMessage());
        }
    }

    public function show(Evaluacion $evaluacion)
    {
        // Verificar que el usuario puede ver esta evaluación
        if ($evaluacion->evaluador_id !== auth()->id() && !auth()->user()->puedeAdministrar()) {
            abort(403, 'No tienes permisos para ver esta evaluación.');
        }

        $evaluacion->load([
            'formulario.preguntas.opciones',
            'formulario.preguntas.tipoPregunta',
            'evaluador.puesto',
            'evaluado.puesto',
            'respuestas.pregunta'
        ]);

        return view('evaluaciones.show', compact('evaluacion'));
    }

    public function pendientes()
    {
        $evaluador = auth()->user();

        if (!$evaluador->puedeEvaluar()) {
            abort(403, 'No tienes permisos para evaluar.');
        }

        $usuariosPendientes = $evaluador->getUsuariosEvaluables();
        $formularios = Formulario::with('area')->get();

        return view('evaluaciones.pendientes', compact('usuariosPendientes', 'formularios'));
    }



    public function downloadPDF(Evaluacion $evaluacion)
{
    // Verificar permisos
    if ($evaluacion->evaluador_id !== auth()->id() && !auth()->user()->puedeAdministrar()) {
        abort(403, 'No tienes permisos para descargar esta evaluación.');
    }

    $evaluacion->load([
        'formulario.preguntas.opciones',
        'formulario.preguntas.tipoPregunta',
        'evaluador.puesto',
        'evaluado.puesto',
        'respuestas.pregunta'
    ]);

    // SOLUCIÓN TEMPORAL: Usar vista de impresión HTML
    return view('evaluaciones.print', compact('evaluacion'));

    // Cuando DomPDF esté instalado, descomenta esto:

    try {
        $pdf = PDF::loadView('evaluaciones.pdf', compact('evaluacion'));
        return $pdf->download("evaluacion-{$evaluacion->evaluado->numero_empleado}-{$evaluacion->fecha_evaluacion->format('Y-m-d')}.pdf");
    } catch (\Exception $e) {
        // Si falla el PDF, redirigir a la vista de impresión
        return view('evaluaciones.print', compact('evaluacion'));
    }

}




    public function destroy(Evaluacion $evaluacion)
    {
        // Verificar permisos - solo el evaluador o un administrador puede eliminar
        if ($evaluacion->evaluador_id !== auth()->id() && !auth()->user()->puedeAdministrar()) {
            abort(403, 'No tienes permisos para eliminar esta evaluación.');
        }

        // Eliminar las respuestas primero
        $evaluacion->respuestas()->delete();

        // Eliminar la evaluación
        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')
            ->with('success', 'Evaluación eliminada exitosamente.');
    }

    /**
     * Calcula el puntaje de una pregunta según el tipo y respuestas
     */
    private function calcularPuntajePregunta(Pregunta $pregunta, array $respuestaData)
    {
        $tipoPregunta = $pregunta->tipoPregunta->nombre_tipo;

        switch ($tipoPregunta) {
            case 'seleccion_unica':
                return $this->calcularSeleccionUnica($pregunta, $respuestaData);

            case 'opcion_multiple':
                return $this->calcularOpcionMultiple($pregunta, $respuestaData);

            case 'escala_numerica':
                return $this->calcularEscalaNumerica($pregunta, $respuestaData);

            case 'texto_libre':
                return $this->calcularTextoLibre($pregunta, $respuestaData);

            case 'si_no':
                return $this->calcularSiNo($pregunta, $respuestaData);

            default:
                return 0;
        }
    }

    /**
     * Para selección única: puntaje completo si es correcta, 0 si no
     */
    private function calcularSeleccionUnica(Pregunta $pregunta, array $respuestaData)
    {
        if (!isset($respuestaData['opcion_id']) || !is_array($respuestaData['opcion_id']) || empty($respuestaData['opcion_id'])) {
            return 0;
        }

        $opcionId = $respuestaData['opcion_id'][0]; // Solo la primera opción para selección única
        $opcion = $pregunta->opciones->where('id', $opcionId)->first();

        return $opcion ? $opcion->valor : 0;
    }

    /**
     * Para opción múltiple: divide el puntaje entre las opciones correctas
     */
    private function calcularOpcionMultiple(Pregunta $pregunta, array $respuestaData)
    {
        if (!isset($respuestaData['opcion_id']) || !is_array($respuestaData['opcion_id']) || empty($respuestaData['opcion_id'])) {
            return 0;
        }

        // Contar opciones correctas (valor > 0)
        $opcionesCorrectas = $pregunta->opciones->where('valor', '>', 0);
        $totalOpcionesCorrectas = $opcionesCorrectas->count();

        if ($totalOpcionesCorrectas === 0) {
            return 0;
        }

        // Puntaje por opción correcta
        $puntajePorOpcion = $pregunta->ponderacion / $totalOpcionesCorrectas;

        $puntajeTotal = 0;
        foreach ($respuestaData['opcion_id'] as $opcionId) {
            $opcion = $pregunta->opciones->where('id', $opcionId)->first();
            if ($opcion && $opcion->valor > 0) {
                $puntajeTotal += $puntajePorOpcion;
            }
        }

        return min($puntajeTotal, $pregunta->ponderacion);
    }

    /**
     * Para escala numérica: valor directo hasta el máximo permitido
     */
    private function calcularEscalaNumerica(Pregunta $pregunta, array $respuestaData)
    {
        $valor = $respuestaData['valor_numerico'] ?? 0;
        return min(max($valor, 0), $pregunta->ponderacion);
    }

    /**
     * Para texto libre: puntaje completo si se respondió
     */
    private function calcularTextoLibre(Pregunta $pregunta, array $respuestaData)
    {
        return !empty($respuestaData['valor_respuesta']) ? $pregunta->ponderacion : 0;
    }

    /**
     * Para sí/no: puntaje completo por sí, 0 por no
     */
    private function calcularSiNo(Pregunta $pregunta, array $respuestaData)
    {
        return ($respuestaData['valor_respuesta'] ?? '') === 'si' ? $pregunta->ponderacion : 0;
    }

    /**
     * Prepara el valor de respuesta para almacenar en base de datos
     */
    private function prepararValorRespuesta(Pregunta $pregunta, array $respuestaData)
    {
        $tipoPregunta = $pregunta->tipoPregunta->nombre_tipo;

        switch ($tipoPregunta) {
            case 'opcion_multiple':
            case 'seleccion_unica':
                if (isset($respuestaData['opcion_id']) && is_array($respuestaData['opcion_id'])) {
                    // Obtener textos de las opciones seleccionadas
                    $opcionesSeleccionadas = [];
                    foreach ($respuestaData['opcion_id'] as $opcionId) {
                        $opcion = $pregunta->opciones->where('id', $opcionId)->first();
                        if ($opcion) {
                            $opcionesSeleccionadas[] = $opcion->texto_opcion;
                        }
                    }
                    return implode(', ', $opcionesSeleccionadas);
                }
                return null;

            case 'escala_numerica':
                return $respuestaData['valor_numerico'] ?? null;

            case 'texto_libre':
            case 'si_no':
                return $respuestaData['valor_respuesta'] ?? null;

            default:
                return null;
        }
    }

    /**
     * Obtiene el texto de la respuesta para mostrar
     */
    public static function obtenerTextoRespuesta($valorRespuesta, $pregunta)
    {
        if (empty($valorRespuesta)) {
            return 'No respondido';
        }

        // Si ya es un texto (de opción múltiple/selección única procesada)
        if (is_string($valorRespuesta) && !is_numeric($valorRespuesta)) {
            return $valorRespuesta;
        }

        // Para escalas numéricas
        if (is_numeric($valorRespuesta)) {
            return "Valor: {$valorRespuesta}";
        }

        return $valorRespuesta;
    }
}
