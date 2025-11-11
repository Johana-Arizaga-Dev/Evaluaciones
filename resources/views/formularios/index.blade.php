@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestión de Formularios</h1>
                <a href="{{ route('formularios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Formulario
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Lista de Formularios
                    </h5>
                </div>
                <div class="card-body">
                    @if($formularios->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Área</th>
                                    <th>Preguntas</th>
                                    <th>Creado por</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formularios as $formulario)
                                <tr>
                                    <td>
                                        <strong>{{ $formulario->titulo }}</strong>
                                        @if($formulario->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($formulario->descripcion, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $formulario->tipo === 'evaluacion' ? 'primary' : ($formulario->tipo === 'encuesta' ? 'success' : 'info') }}">
                                            {{ ucfirst($formulario->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $formulario->area->nombre_area ?? 'Todas las áreas' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $formulario->preguntas_count ?? $formulario->preguntas->count() }}</span>
                                    </td>
                                    <td>{{ $formulario->creador->nombre_empleado }}</td>
                                    <td>{{ $formulario->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('formularios.show', $formulario) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('formularios.edit', $formulario) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Editar formulario">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('formularios.destroy', $formulario) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este formulario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar formulario">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-edit fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay formularios registrados</h4>
                        <p class="text-muted mb-4">Comienza creando el primer formulario en el sistema.</p>
                        <a href="{{ route('formularios.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Crear Primer Formulario
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
