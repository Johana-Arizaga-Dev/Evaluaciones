@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Detalles de Evaluación</h1>
                <div>
                    <a href="{{ route('evaluaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Información de la evaluación -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Evaluador:</strong> {{ $evaluacion->evaluador->nombre_empleado }}</p>
                            <p><strong>Evaluado:</strong> {{ $evaluacion->evaluado->nombre_empleado }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Formulario:</strong> {{ $evaluacion->formulario->titulo }}</p>
                            <p><strong>Fecha:</strong> {{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Resultados:</strong>
                                {{ $evaluacion->puntaje_obtenido }}/{{ $evaluacion->calificacion_total }}
                                ({{ number_format($evaluacion->porcentaje_obtenido, 1) }}%)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Respuestas -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Respuestas
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($evaluacion->formulario->preguntas as $index => $pregunta)
                        @php
                            $respuesta = $evaluacion->respuestas->where('pregunta_id', $pregunta->id)->first();
                        @endphp
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    Pregunta {{ $index + 1 }}: {{ $pregunta->texto_pregunta }}
                                </h6>
                                <p class="text-muted">
                                    <small>Tipo: {{ $pregunta->tipoPregunta->nombre_tipo }} | Ponderación: {{ $pregunta->ponderacion }} pts</small>
                                </p>

                                @if($respuesta)
                                    <div class="alert alert-success">
                                        <strong>Respuesta:</strong>
                                        {{ $respuesta->valor_respuesta ?? 'No especificada' }}
                                        @if($respuesta->comentarios)
                                            <br><strong>Comentarios:</strong> {{ $respuesta->comentarios }}
                                        @endif
                                        <br><strong>Puntaje:</strong> {{ $respuesta->puntaje }} pts
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        No se respondió esta pregunta
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Resumen -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Resumen
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2>{{ number_format($evaluacion->porcentaje_obtenido, 1) }}%</h2>
                    <p class="text-muted">Porcentaje obtenido</p>
                    <div class="progress mb-3">
                        <div class="progress-bar
                            @if($evaluacion->porcentaje_obtenido >= 80) bg-success
                            @elseif($evaluacion->porcentaje_obtenido >= 60) bg-warning
                            @else bg-danger
                            @endif"
                            style="width: {{ $evaluacion->porcentaje_obtenido }}%">
                        </div>
                    </div>
                    <p><strong>{{ $evaluacion->puntaje_obtenido }}/{{ $evaluacion->calificacion_total }}</strong> puntos</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
