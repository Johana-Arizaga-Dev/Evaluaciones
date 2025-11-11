@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-layer-group me-2"></i>
                    Puestos del Nivel {{ $nivel }}
                </h1>
                <a href="{{ route('puestos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Todos los Puestos
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @php
                $color = getColorByNivel($nivel);
            @endphp
            <div class="card shadow">
                <div class="card-header bg-{{ $color }} text-white">
                    <h5 class="card-title mb-0">
                        Puestos del Nivel {{ $nivel }}
                        <span class="badge bg-light text-{{ $color }}">{{ $puestos->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($puestos->count() > 0)
                    <div class="row">
                        @foreach($puestos as $puesto)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-left-{{ $color }} shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $puesto->nombre_puesto }}</h5>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">
                                            {{ $puesto->usuarios_count }} usuarios
                                        </span>
                                        <div>
                                            <a href="{{ route('puestos.show', $puesto) }}"
                                               class="btn btn-sm btn-outline-{{ $color }}">
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay puestos en este nivel</h4>
                        <p class="text-muted">Puedes crear el primer puesto de este nivel.</p>
                        @auth
                            @if(auth()->user()->puedeAdministrar())
                            <a href="{{ route('puestos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Crear Puesto
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
