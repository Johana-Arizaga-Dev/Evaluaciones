@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestión de Puestos</h1>
                @auth
                    @if(auth()->user()->puedeAdministrar())
                    <a href="{{ route('puestos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Puesto
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Resumen por Niveles -->
    <div class="row mb-4">
        @foreach($puestosPorNivel as $nivel => $puestosDelNivel)
        @php
            $color = getColorByNivel($nivel);
        @endphp
        <div class="col-md-4 col-lg-3 mb-3">
            <div class="card border-left-{{ $color }} shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-{{ $color }}">Nivel {{ $nivel }}</h4>
                        <h2 class="mb-0">{{ $puestosDelNivel->count() }}</h2>
                        <small class="text-muted">puestos</small>
                        <div class="mt-2">
                            <a href="{{ route('puestos.por-nivel', $nivel) }}"
                               class="btn btn-sm btn-outline-{{ $color }}">
                                Ver todos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>Lista de Todos los Puestos
                    </h5>
                </div>
                <div class="card-body">
                    @if($puestos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Puesto</th>
                                    <th>Nivel de Jerarquía</th>
                                    <th>Usuarios Asignados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($puestos as $puesto)
                                @php
                                    $colorPuesto = getColorByNivel($puesto->nivel_jerarquico);
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $puesto->nombre_puesto }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $colorPuesto }} fs-6">
                                            Nivel {{ $puesto->nivel_jerarquico }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $puesto->usuarios_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('puestos.show', $puesto) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @auth
                                            @if(auth()->user()->puedeAdministrar())
                                            <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('puestos.destroy', $puesto) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar este puesto?')">
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
                        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay puestos registrados</h4>
                        <p class="text-muted mb-4">Comienza creando el primer puesto en el sistema.</p>
                        @auth
                            @if(auth()->user()->puedeAdministrar())
                            <a href="{{ route('puestos.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Crear Primer Puesto
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

@push('styles')
<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
    .border-left-secondary { border-left: 0.25rem solid #858796 !important; }
    .border-left-dark { border-left: 0.25rem solid #5a5c69 !important; }
    .border-left-light { border-left: 0.25rem solid #f8f9fc !important; }
</style>
@endpush

@php
function getColorByNivel($nivel) {
    $colores = [
        1 => 'danger',    // Máxima autoridad - Rojo
        2 => 'warning',   // Alta dirección - Amarillo/Naranja
        3 => 'info',      // Gerencia - Azul claro
        4 => 'primary',   // Supervisión - Azul
        5 => 'success',   // Liderazgo - Verde
        6 => 'secondary', // Senior - Gris
        7 => 'dark',      // Intermedio - Negro
        8 => 'secondary', // Junior - Gris
        9 => 'light',     // Base - Gris claro
    ];
    return $colores[$nivel] ?? 'secondary';
}
@endphp
