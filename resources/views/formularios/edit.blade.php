@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Editar Formulario</h1>
                <div>
                    <a href="{{ route('formularios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('formularios.show', $formulario) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>Ver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar: {{ $formulario->titulo }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('formularios.update', $formulario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título del Formulario *</label>
                                <input type="text"
                                       class="form-control @error('titulo') is-invalid @enderror"
                                       id="titulo"
                                       name="titulo"
                                       value="{{ old('titulo', $formulario->titulo) }}"
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
                                    <option value="evaluacion" {{ old('tipo', $formulario->tipo) == 'evaluacion' ? 'selected' : '' }}>Evaluación</option>
                                    <option value="encuesta" {{ old('tipo', $formulario->tipo) == 'encuesta' ? 'selected' : '' }}>Encuesta</option>
                                    <option value="diagnostico" {{ old('tipo', $formulario->tipo) == 'diagnostico' ? 'selected' : '' }}>Diagnóstico</option>
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
                                    <option value="{{ $area->id }}" {{ old('area_id', $formulario->area_id) == $area->id ? 'selected' : '' }}>
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
                                      rows="3">{{ old('descripcion', $formulario->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Actualizar Formulario
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información de Preguntas -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>Preguntas del Formulario ({{ $formulario->preguntas->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($formulario->preguntas->count() > 0)
                        @foreach($formulario->preguntas as $index => $pregunta)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">
                                        Pregunta {{ $index + 1 }}
                                        <span class="badge bg-primary ms-2">{{ $pregunta->ponderacion }} pts</span>
                                    </h6>
                                    <span class="badge bg-secondary">{{ $pregunta->tipoPregunta->nombre_tipo }}</span>
                                </div>

                                <p class="card-text">{{ $pregunta->texto_pregunta }}</p>

                                @if($pregunta->opciones->count() > 0)
                                <div class="mt-3">
                                    <strong>Opciones:</strong>
                                    <ul class="list-group list-group-flush mt-2">
                                        @foreach($pregunta->opciones as $opcion)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $opcion->texto_opcion }}
                                            <span class="badge bg-{{ $opcion->valor > 0 ? 'success' : 'secondary' }}">
                                                {{ $opcion->valor }} pts
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay preguntas en este formulario</h5>
                        <p class="text-muted">Las preguntas no se pueden editar una vez creado el formulario.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
