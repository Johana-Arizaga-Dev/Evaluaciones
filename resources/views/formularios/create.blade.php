@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Crear Nuevo Formulario</h1>
                <a href="{{ route('formularios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Información del Formulario
                    </h5>
                </div>
                <div class="card-body">
                    <form id="formularioForm" action="{{ route('formularios.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título del Formulario *</label>
                                <input type="text"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       id="titulo"
                                       name="titulo"
                                       value="{{ old('titulo') }}"
                                       required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo de Formulario *</label>
                                <select class="form-select @error('tipo') is-invalid @enderror"
                                        id="tipo"
                                        name="tipo"
                                        required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="evaluacion" {{ old('tipo') == 'evaluacion' ? 'selected' : '' }}>Evaluación</option>
                                    <option value="encuesta" {{ old('tipo') == 'encuesta' ? 'selected' : '' }}>Encuesta</option>
                                    <option value="diagnostico" {{ old('tipo') == 'diagnostico' ? 'selected' : '' }}>Diagnóstico</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="area_id" class="form-label">Área Destinada</label>
                                <select class="form-select @error('area_id') is-invalid @enderror"
                                        id="area_id"
                                        name="area_id">
                                    <option value="">Todas las áreas</option>
                                    @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->nombre_area }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                      id="descripcion"
                                      name="descripcion"
                                      rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Sección de Preguntas -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-question-circle me-2"></i>Preguntas del Formulario
                            </h5>
                            <button type="button" class="btn btn-success btn-sm" onclick="agregarPregunta()">
                                <i class="fas fa-plus me-1"></i>Agregar Pregunta
                            </button>
                        </div>

                        <div id="preguntas-container">
                            <!-- Las preguntas se agregarán aquí dinámicamente -->
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Formulario
                            </button>
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
let preguntaCount = 0;

function agregarPregunta() {
    preguntaCount++;

    const html = `
    <div class="card mb-3 pregunta-item" id="pregunta-${preguntaCount}">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Pregunta #${preguntaCount}</h6>
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarPregunta(${preguntaCount})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Texto de la Pregunta *</label>
                    <input type="text"
                           class="form-control"
                           name="preguntas[${preguntaCount}][texto_pregunta]"
                           required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Pregunta *</label>
                    <select class="form-select tipo-pregunta"
                            name="preguntas[${preguntaCount}][tipo_pregunta_id]"
                            onchange="cambiarTipoPregunta(${preguntaCount})"
                            required>
                        <option value="">Selecciona un tipo</option>
                        @foreach($tiposPregunta as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="opciones-container-${preguntaCount}" class="mt-3" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Opciones de Respuesta</label>
                    <button type="button" class="btn btn-success btn-sm" onclick="agregarOpcion(${preguntaCount})">
                        <i class="fas fa-plus me-1"></i>Agregar Opción
                    </button>
                </div>
                <div class="alert alert-info small mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> La ponderación se calculará automáticamente según los valores de las opciones.
                    Para opción múltiple: se usará el valor máximo. Para casillas: se sumarán todos los valores positivos.
                </div>
                <div id="opciones-${preguntaCount}">
                    <!-- Las opciones se agregarán aquí -->
                </div>
            </div>
        </div>
    </div>
    `;

    document.getElementById('preguntas-container').insertAdjacentHTML('beforeend', html);
}

function eliminarPregunta(id) {
    document.getElementById(`pregunta-${id}`).remove();
    // Reordenar números de pregunta
    actualizarNumerosPreguntas();
}

function cambiarTipoPregunta(preguntaId) {
    const select = document.querySelector(`#pregunta-${preguntaId} .tipo-pregunta`);
    const opcionesContainer = document.getElementById(`opciones-container-${preguntaId}`);
    const tiposConOpciones = [1, 2]; // IDs de tipos que requieren opciones (opcion_multiple, casillas)

    if (tiposConOpciones.includes(parseInt(select.value))) {
        opcionesContainer.style.display = 'block';
    } else {
        opcionesContainer.style.display = 'none';
    }
}

function agregarOpcion(preguntaId) {
    const opcionesCount = document.querySelectorAll(`#opciones-${preguntaId} .opcion-item`).length;
    const html = `
    <div class="row opcion-item mb-2 align-items-center">
        <div class="col-md-6">
            <input type="text"
                   class="form-control"
                   name="preguntas[${preguntaId}][opciones][${opcionesCount}][texto_opcion]"
                   placeholder="Texto de la opción"
                   required>
        </div>
        <div class="col-md-4">
            <input type="number"
                   class="form-control"
                   name="preguntas[${preguntaId}][opciones][${opcionesCount}][valor]"
                   placeholder="Valor"
                   value="0"
                   min="0"
                   required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    `;

    document.getElementById(`opciones-${preguntaId}`).insertAdjacentHTML('beforeend', html);
}

function actualizarNumerosPreguntas() {
    const preguntas = document.querySelectorAll('.pregunta-item');
    preguntas.forEach((pregunta, index) => {
        const header = pregunta.querySelector('.card-header h6');
        header.textContent = `Pregunta #${index + 1}`;
    });
}

// Agregar una pregunta inicial al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    agregarPregunta();
});
</script>
@endpush
