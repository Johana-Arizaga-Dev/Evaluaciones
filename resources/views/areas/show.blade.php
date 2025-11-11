@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Detalles del Área</h1>
                <div>
                    <a href="{{ route('areas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    @auth
                        @if(auth()->user()->puedeAdministrar())
                        <a href="{{ route('areas.edit', $area) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>{{ $area->nombre_area }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información General</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Nombre:</th>
                                    <td>{{ $area->nombre_area }}</td>
                                </tr>
                                <tr>
                                    <th>Descripción:</th>
                                    <td>{{ $area->descripcion ?? 'Sin descripción' }}</td>
                                </tr>
                                <tr>
                                    <th>Total Formularios:</th>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $area->formularios_count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación:</th>
                                    <td>{{ $area->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Resumen
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-edit fa-3x text-primary mb-2"></i>
                        <h3>{{ $area->formularios_count }}</h3>
                        <p class="text-muted mb-0">Formularios en esta área</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Formularios del Área -->
    @if($area->formularios->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Formularios de esta Área
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Creado por</th>
                                    <th>Preguntas</th>
                                    <th>Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($area->formularios as $formulario)
                                <tr>
                                    <td>
                                        <strong>{{ $formulario->titulo }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $formulario->tipo === 'evaluacion' ? 'primary' : 'success' }}">
                                            {{ ucfirst($formulario->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $formulario->creador->nombre_empleado }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $formulario->preguntas->count() }}</span>
                                    </td>
                                    <td>{{ $formulario->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-4">
                    <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay formularios en esta área</h4>
                    <p class="text-muted">Los formularios pueden ser asignados a esta área desde su creación.</p>
                    @if(auth()->user()->puedeCrearFormularios())
                    <a href="{{ route('formularios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Crear Formulario
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
