@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Detalles del Formulario</h1>
                <div>
                    <a href="{{ route('formularios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('formularios.edit', $formulario) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>{{ $formulario->titulo }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Título:</th>
                                    <td>{{ $formulario->titulo }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <span class="badge bg-{{ $formulario->tipo === 'evaluacion' ? 'primary' : ($formulario->tipo === 'encuesta' ? 'success' : 'info') }}">
                                            {{ ucfirst($formulario->tipo) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Área:</th>
                                    <td>{{ $formulario->area->nombre_area ?? 'Todas las áreas' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Creado por:</th>
                                    <td>{{ $formulario->creador->nombre_empleado }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha creación:</th>
                                    <td>{{ $formulario->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Total preguntas:</th>
                                    <td>
                                        <span class="badge bg-secondary">{{ $formulario->preguntas->count() }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($formulario->descripcion)
                    <div class="mt-3">
                        <strong>Descripción:</strong>
                        <p class="mb-0">{{ $formulario->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lista de Preguntas -->
            <div class="card shadow mt-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>Preguntas ({{ $formulario->preguntas->count() }})
                    </h6>
                </div>
                <div class="card-body">
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
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Estadísticas -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-info">{{ $formulario->preguntas->count() }}</h4>
                        <p class="text-muted mb-0">Total Preguntas</p>
                    </div>

                    <div class="text-center mb-3">
                        <h4 class="text-success">{{ $formulario->preguntas->sum('ponderacion') }}</h4>
                        <p class="text-muted mb-0">Puntaje Total</p>
                    </div>

                    <div class="text-center">
                        <h4 class="text-warning">{{ $formulario->evaluaciones->count() }}</h4>
                        <p class="text-muted mb-0">Evaluaciones Realizadas</p>
                    </div>
                </div>
            </div>

            <!-- Información del Área -->
            @if($formulario->area)
            <div class="card shadow mt-4">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Área Destinada
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h5 class="text-success">{{ $formulario->area->nombre_area }}</h5>
                    <p class="text-muted mb-0">Formulario específico para esta área</p>
                </div>
            </div>
            @else
            <div class="card shadow mt-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-globe me-2"></i>Ámbito del Formulario
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h5 class="text-secondary">Todas las Áreas</h5>
                    <p class="text-muted mb-0">Formulario disponible para todas las áreas</p>
                </div>
            </div>
            @endif

            <!-- Acciones Rápidas -->
            <div class="card shadow mt-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('formularios.edit', $formulario) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar Formulario
                        </a>
                        <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-success">
                            <i class="fas fa-play-circle me-2"></i>Usar para Evaluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
