@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Evaluaciones Pendientes</h1>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    @if($usuariosPendientes->count() > 0)
    <div class="row">
        @foreach($usuariosPendientes as $usuario)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">{{ $usuario->nombre_empleado }} {{ $usuario->apellidos_empleado }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Puesto:</strong> {{ $usuario->puesto->nombre_puesto }}<br>
                        <strong>Nivel:</strong>
                        <span class="badge bg-{{ getColorByNivel($usuario->puesto->nivel_jerarquico) }}">
                            Nivel {{ $usuario->puesto->nivel_jerarquico }}
                        </span><br>
                        <strong>Número Empleado:</strong> {{ $usuario->numero_empleado }}
                    </p>

                    <form action="{{ route('evaluaciones.create') }}" method="GET">
                        <div class="mb-3">
                            <label for="formulario_id_{{ $usuario->id }}" class="form-label">Seleccionar Formulario:</label>
                            <select name="formulario_id" class="form-select" id="formulario_id_{{ $usuario->id }}" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($formularios as $formulario)
                                <option value="{{ $formulario->id }}">
                                    {{ $formulario->titulo }}
                                    @if($formulario->area)
                                    - {{ $formulario->area->nombre_area }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="evaluado_id" value="{{ $usuario->id }}">

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-play-circle me-2"></i>Iniciar Evaluación
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h3>¡No hay evaluaciones pendientes!</h3>
                    <p class="text-muted">No tienes usuarios pendientes por evaluar en este momento.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Volver al Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

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
