@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Realizar Evaluación</h1>
                <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Información de la evaluación -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Información del Evaluado
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $evaluado->nombre_empleado }} {{ $evaluado->apellidos_empleado }}</p>
                    <p><strong>Número de Empleado:</strong> {{ $evaluado->numero_empleado }}</p>
                    <p><strong>Puesto:</strong> {{ $evaluado->puesto->nombre_puesto }}</p>
                    <p><strong>Nivel Jerárquico:</strong>
                        <span class="badge bg-{{ getColorByNivel($evaluado->puesto->nivel_jerarquico) }}">
                            Nivel {{ $evaluado->puesto->nivel_jerarquico }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Información del Formulario
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Formulario:</strong> {{ $formulario->titulo }}</p>
                    <p><strong>Tipo:</strong>
                        <span class="badge bg-{{ $formulario->tipo === 'evaluacion' ? 'primary' : 'success' }}">
                            {{ ucfirst($formulario->tipo) }}
                        </span>
                    </p>
                    <p><strong>Descripción:</strong> {{ $formulario->descripcion ?? 'Sin descripción' }}</p>
                    <p><strong>Total de Preguntas:</strong> {{ $formulario->preguntas->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de evaluación -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Formulario de Evaluación
                    </h5>
                </div>
                <div class="card-body">
                    <form id="evaluacionForm" action="{{ route('evaluaciones.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
                        <input type="hidden" name="evaluado_id" value="{{ $evaluado->id }}">

                        @foreach($formulario->preguntas as $index => $pregunta)
                        <div class="card mb-4 pregunta-item">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    Pregunta {{ $index + 1 }}
                                    <span class="badge bg-secondary ms-1">{{ $pregunta->tipoPregunta->nombre_tipo }}</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text fw-bold">{{ $pregunta->texto_pregunta }}</p>

                                <!-- Campo oculto para pregunta_id -->
                                <input type="hidden" name="respuestas[{{ $index }}][pregunta_id]" value="{{ $pregunta->id }}">

                                @switch($pregunta->tipoPregunta->nombre_tipo)
                                    @case('opcion_multiple')
                                    @case('seleccion_unica')
                                        @if($pregunta->opciones->count() > 0)
                                            <div class="opciones-container">
                                                @foreach($pregunta->opciones as $opcion)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input"
                                                               type="{{ $pregunta->tipoPregunta->nombre_tipo == 'seleccion_unica' ? 'radio' : 'checkbox' }}"
                                                               name="respuestas[{{ $index }}][opcion_id][]"
                                                               value="{{ $opcion->id }}"
                                                               id="opcion_{{ $pregunta->id }}_{{ $opcion->id }}">
                                                        <label class="form-check-label" for="opcion_{{ $pregunta->id }}_{{ $opcion->id }}">
                                                            {{ $opcion->texto_opcion }}
                                                            <span class="badge bg-success ms-2">{{ $opcion->valor }} pts</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                No hay opciones definidas para esta pregunta.
                                            </div>
                                        @endif
                                        @break

                                    @case('escala_numerica')
                                        <div class="escala-numerica-container">
                                            <input type="number"
                                                   class="form-control"
                                                   name="respuestas[{{ $index }}][valor_numerico]"
                                                   min="0"
                                                   max="{{ $pregunta->ponderacion }}"
                                                   placeholder="Ingresa un valor entre 0 y {{ $pregunta->ponderacion }}"
                                                   style="max-width: 200px;">
                                            <small class="form-text text-muted">
                                                Valor máximo: {{ $pregunta->ponderacion }} puntos
                                            </small>
                                        </div>
                                        @break

                                    @case('texto_libre')
                                        <div class="texto-libre-container">
                                            <textarea class="form-control"
                                                      name="respuestas[{{ $index }}][valor_respuesta]"
                                                      rows="3"
                                                      placeholder="Escribe tu respuesta aquí..."></textarea>
                                        </div>
                                        @break

                                    @case('si_no')
                                        <div class="si-no-container">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="respuestas[{{ $index }}][valor_respuesta]"
                                                       value="si" id="si_{{ $pregunta->id }}">
                                                <label class="form-check-label" for="si_{{ $pregunta->id }}">Sí</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="respuestas[{{ $index }}][valor_respuesta]"
                                                       value="no" id="no_{{ $pregunta->id }}">
                                                <label class="form-check-label" for="no_{{ $pregunta->id }}">No</label>
                                            </div>
                                        </div>
                                        @break

                                    @default
                                        <div class="alert alert-warning">
                                            Tipo de pregunta no soportado: {{ $pregunta->tipoPregunta->nombre_tipo }}
                                        </div>
                                @endswitch

                                <!-- Campo para comentarios adicionales -->
                                <div class="mt-3">
                                    <label for="comentarios_{{ $pregunta->id }}" class="form-label">
                                        <small>Comentarios adicionales (opcional):</small>
                                    </label>
                                    <textarea class="form-control form-control-sm"
                                              name="respuestas[{{ $index }}][comentarios]"
                                              id="comentarios_{{ $pregunta->id }}"
                                              rows="2"
                                              placeholder="Agrega comentarios si lo consideras necesario..."></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Resumen y botones -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Resumen de la Evaluación:</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Total de preguntas:</strong> {{ $formulario->preguntas->count() }}</li>
                                            <!-- Puntajes ocultos durante la evaluación: el evaluador no ve puntajes individuales -->
                                            <li><strong>Evaluado:</strong> {{ $evaluado->nombre_empleado }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="d-grid gap-2 d-md-block">
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                                <i class="fas fa-times me-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Enviar Evaluación
                                            </button>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Una vez enviada, no podrás modificar la evaluación.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('evaluacionForm');

    form.addEventListener('submit', function(e) {
        let isValid = true;
        const preguntas = document.querySelectorAll('.pregunta-item');

        preguntas.forEach((pregunta, index) => {
            const tipoPregunta = '{{ $pregunta->tipoPregunta->nombre_tipo }}'; // Esto necesita ajustarse

            // Validar según el tipo de pregunta
            const opcionesSeleccionadas = pregunta.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
            const valorNumerico = pregunta.querySelector('input[type="number"]');
            const textoLibre = pregunta.querySelector('textarea[name*="valor_respuesta"]');

            let tieneRespuesta = false;

            if (opcionesSeleccionadas.length > 0) {
                tieneRespuesta = true;
            } else if (valorNumerico && valorNumerico.value !== '') {
                tieneRespuesta = true;
            } else if (textoLibre && textoLibre.value.trim() !== '') {
                tieneRespuesta = true;
            }

            if (!tieneRespuesta) {
                isValid = false;
                // Resaltar la pregunta sin respuesta
                pregunta.style.border = '2px solid #dc3545';
                pregunta.style.borderRadius = '5px';

                // Agregar mensaje de error
                let errorMsg = pregunta.querySelector('.error-msg');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-danger mt-2 error-msg';
                    errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Esta pregunta requiere una respuesta.';
                    pregunta.querySelector('.card-body').appendChild(errorMsg);
                }
            } else {
                // Remover estilos de error si los tiene
                pregunta.style.border = '';
                const errorMsg = pregunta.querySelector('.error-msg');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.error-msg');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            alert('Por favor, responde todas las preguntas antes de enviar la evaluación.');
        }
    });

    // Validación en tiempo real para campos numéricos
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value) || 0;

            if (value > max) {
                this.value = max;
            } else if (value < 0) {
                this.value = 0;
            }
        });
    });
});
</script>
@endpush

@php
function getColorByNivel($nivel) {
    $colores = [
        1 => 'danger',    2 => 'warning',   3 => 'info',
        4 => 'primary',   5 => 'success',   6 => 'secondary',
        7 => 'dark',      8 => 'secondary', 9 => 'light',
    ];
    return $colores[$nivel] ?? 'secondary';
}
@endphp
