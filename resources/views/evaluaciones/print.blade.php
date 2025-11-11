<!DOCTYPE html>
<html>
<head>
    <title>Evaluación - {{ $evaluacion->evaluado->nombre_empleado }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .resultado-final {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .pregunta-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .bg-success { background-color: #28a745; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-danger { background-color: #dc3545; color: white; }
        .no-print { display: none; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <!-- Botón de impresión (solo visible en navegador) -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Imprimir Evaluación
        </button>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ↩️ Volver
        </button>
    </div>

    <div class="header">
        <h1>Evaluación de Desempeño</h1>
        <p><strong>Sistema de Evaluaciones</strong></p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Información General -->
    <div class="section">
        <h2>Información General</h2>
        <table class="table">
            <tr>
                <th width="30%">Evaluador</th>
                <td>{{ $evaluacion->evaluador->nombre_empleado }} {{ $evaluacion->evaluador->apellidos_empleado }}</td>
            </tr>
            <tr>
                <th>Evaluado</th>
                <td>{{ $evaluacion->evaluado->nombre_empleado }} {{ $evaluacion->evaluado->apellidos_empleado }}</td>
            </tr>
            <tr>
                <th>Número de Empleado</th>
                <td>{{ $evaluacion->evaluado->numero_empleado }}</td>
            </tr>
            <tr>
                <th>Puesto</th>
                <td>{{ $evaluacion->evaluado->puesto->nombre_puesto ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <th>Formulario</th>
                <td>{{ $evaluacion->formulario->titulo }}</td>
            </tr>
            <tr>
                <th>Fecha de Evaluación</th>
                <td>{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Resultados Finales -->
    <div class="resultado-final">
        <h2>Resultado Final</h2>
        <div style="font-size: 24px; font-weight: bold; margin: 10px 0;">
            {{ number_format($evaluacion->porcentaje_obtenido, 1) }}%
        </div>
        <div style="font-size: 18px;">
            {{ $evaluacion->puntaje_obtenido }}/{{ $evaluacion->calificacion_total }} puntos
        </div>
        <div style="margin-top: 10px;">
            @if($evaluacion->porcentaje_obtenido >= 80)
                <span class="badge bg-success">EXCELENTE</span>
            @elseif($evaluacion->porcentaje_obtenido >= 60)
                <span class="badge bg-warning">SATISFACTORIO</span>
            @else
                <span class="badge bg-danger">POR MEJORAR</span>
            @endif
        </div>
    </div>

    <!-- Detalle de Respuestas -->
    <div class="section">
        <h2>Detalle de Respuestas</h2>

        @foreach($evaluacion->formulario->preguntas as $index => $pregunta)
            @php
                $respuesta = $evaluacion->respuestas->where('pregunta_id', $pregunta->id)->first();
                $puntajeObtenido = $respuesta ? $respuesta->puntaje : 0;
            @endphp

            <div class="pregunta-item">
                <h4>Pregunta {{ $index + 1 }}</h4>
                <p><strong>{{ $pregunta->texto_pregunta }}</strong></p>

                <div style="margin: 10px 0;">
                    <small>
                        <strong>Tipo:</strong> {{ $pregunta->tipoPregunta->nombre_tipo }} |
                        <strong>Ponderación:</strong> {{ $pregunta->ponderacion }} puntos
                    </small>
                </div>

                <!-- Respuesta del evaluador -->
                <div style="background-color: #f8f9fa; padding: 10px; border-radius: 4px; margin: 10px 0;">
                    <strong>Respuesta:</strong><br>
                    {{ $respuesta ? \App\Http\Controllers\EvaluacionController::obtenerTextoRespuesta($respuesta->valor_respuesta, $pregunta) : 'No respondido' }}
                </div>

                @if($respuesta && $respuesta->comentarios)
                <div style="background-color: #e9ecef; padding: 10px; border-radius: 4px; margin: 10px 0;">
                    <strong>Comentarios:</strong><br>
                    {{ $respuesta->comentarios }}
                </div>
                @endif

                <!-- Resultado de la pregunta -->
                <div style="text-align: center; margin: 10px 0;">
                    <small>
                        Puntaje obtenido: {{ $puntajeObtenido }}/{{ $pregunta->ponderacion }} puntos
                    </small>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Resumen Estadístico -->
    <div class="section">
        <h2>Resumen Estadístico</h2>
        <table class="table">
            <tr>
                <th>Total de Preguntas</th>
                <td>{{ $evaluacion->formulario->preguntas->count() }}</td>
            </tr>
            <tr>
                <th>Preguntas Respondidas</th>
                <td>{{ $evaluacion->respuestas->count() }}</td>
            </tr>
            <tr>
                <th>Puntaje Máximo Posible</th>
                <td>{{ $evaluacion->calificacion_total }} puntos</td>
            </tr>
            <tr>
                <th>Puntaje Obtenido</th>
                <td>{{ $evaluacion->puntaje_obtenido }} puntos</td>
            </tr>
            <tr>
                <th>Porcentaje de Acierto</th>
                <td>{{ number_format($evaluacion->porcentaje_obtenido, 1) }}%</td>
            </tr>
            <tr>
                <th>Nivel de Desempeño</th>
                <td>
                    @if($evaluacion->porcentaje_obtenido >= 80)
                        <span class="badge bg-success">EXCELENTE</span>
                    @elseif($evaluacion->porcentaje_obtenido >= 60)
                        <span class="badge bg-warning">SATISFACTORIO</span>
                    @else
                        <span class="badge bg-danger">POR MEJORAR</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #6c757d;">
        <p>Documento generado automáticamente por el Sistema de Evaluaciones</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        window.onload = function() {
            // Descomenta la siguiente línea si quieres que se imprima automáticamente
            // window.print();
        };
    </script>
</body>
</html>
