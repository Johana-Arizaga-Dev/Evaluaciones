@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Detalles del Puesto</h1>
                <div>
                    <a href="{{ route('puestos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    @auth
                        @if(auth()->user()->puedeAdministrar())
                        <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-warning">
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
                @php
                    $color = getColorByNivel($puesto->nivel_jerarquico);
                @endphp
                <div class="card-header bg-{{ $color }} text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>{{ $puesto->nombre_puesto }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información del Puesto</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Nombre:</th>
                                    <td>{{ $puesto->nombre_puesto }}</td>
                                </tr>
                                <tr>
                                    <th>Nivel Jerárquico:</th>
                                    <td>
                                        <span class="badge bg-{{ $color }} fs-6">
                                            Nivel {{ $puesto->nivel_jerarquico }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total Usuarios:</th>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $puesto->usuarios_count }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Capacidades de Evaluación</h6>
                            <div class="alert alert-info">
                                <small>
                                    <strong>Puede evaluar a:</strong><br>
                                    • Niveles {{ $puesto->nivel_jerarquico + 1 }} al 9
                                    @if($puesto->nivel_jerarquico == 1)
                                        <br>• (Todos los niveles inferiores)
                                    @endif
                                </small>
                                <br><br>
                                <small>
                                    <strong>Puede ser evaluado por:</strong><br>
                                    @if($puesto->nivel_jerarquico > 1)
                                        • Niveles 1 al {{ $puesto->nivel_jerarquico - 1 }}
                                    @else
                                        • (Nadie - Máxima autoridad)
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Usuarios con este Puesto -->
            @if($puesto->usuarios->count() > 0)
            <div class="card shadow mt-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Usuarios con este Puesto ({{ $puesto->usuarios->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>N° Empleado</th>
                                    <th>Área</th>
                                    <th>Fecha Ingreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($puesto->usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->nombre_empleado }} {{ $usuario->apellidos_empleado }}</td>
                                    <td>{{ $usuario->numero_empleado }}</td>
                                    <td>{{ $usuario->fecha_ingreso->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Otros puestos del mismo nivel -->
            @if($puestosMismoNivel->count() > 0)
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Otros Puestos Nivel {{ $puesto->nivel_jerarquico }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($puestosMismoNivel as $puestoMismoNivel)
                        <a href="{{ route('puestos.show', $puestoMismoNivel) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $puestoMismoNivel->nombre_puesto }}
                            <span class="badge bg-primary rounded-pill">{{ $puestoMismoNivel->usuarios_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Información del Nivel -->
            <div class="card shadow mt-4">
                @php
                    $colorNivel = getColorByNivel($puesto->nivel_jerarquico);
                @endphp
                <div class="card-header bg-{{ $colorNivel }} text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Nivel {{ $puesto->nivel_jerarquico }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-{{ $colorNivel }}">
                            Nivel {{ $puesto->nivel_jerarquico }}
                        </h3>
                        <p class="mb-2">
                            @if($puesto->nivel_jerarquico == 1)
                                <span class="badge bg-danger">Máxima Autoridad</span>
                            @elseif($puesto->nivel_jerarquico == 9)
                                <span class="badge bg-light text-dark">Nivel Base</span>
                            @else
                                <span class="badge bg-primary">Nivel Intermedio</span>
                            @endif
                        </p>
                        <small class="text-muted">
                            Total de puestos en este nivel:
                            <strong>{{ $puestosMismoNivel->count() + 1 }}</strong>
                        </small>
                    </div>
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
