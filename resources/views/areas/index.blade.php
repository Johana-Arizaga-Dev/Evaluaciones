@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestión de Áreas</h1>
                @auth
                    @if(auth()->user()->puedeAdministrar())
                    <a href="{{ route('areas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Área
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Lista de Áreas
                    </h5>
                </div>
                <div class="card-body">
                    @if($areas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Área</th>
                                    <th>Descripción</th>
                                    <th>Formularios Asignados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($areas as $area)
                                <tr>
                                    <td>
                                        <strong>{{ $area->nombre_area }}</strong>
                                    </td>
                                    <td>
                                        {{ $area->descripcion ?? 'Sin descripción' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $area->formularios_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('areas.show', $area) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @auth
                                            @if(auth()->user()->puedeAdministrar())
                                            <a href="{{ route('areas.edit', $area) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Editar área">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('areas.destroy', $area) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta área?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar área">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay áreas registradas</h4>
                        <p class="text-muted mb-4">Comienza creando la primera área en el sistema.</p>
                        @auth
                            @if(auth()->user()->puedeAdministrar())
                            <a href="{{ route('areas.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Crear Primera Área
                            </a>
                            @endif
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
