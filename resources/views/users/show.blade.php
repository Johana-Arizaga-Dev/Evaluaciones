@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Detalles del Usuario</h1>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
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
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="140">Nombre:</th>
                                    <td>{{ $user->nombre_empleado }}</td>
                                </tr>
                                <tr>
                                    <th>Apellidos:</th>
                                    <td>{{ $user->apellidos_empleado }}</td>
                                </tr>
                                <tr>
                                    <th>N° Empleado:</th>
                                    <td><code>{{ $user->numero_empleado }}</code></td>
                                </tr>
                                <tr>
                                    <th>Fecha Ingreso:</th>
                                    <td>{{ $user->fecha_ingreso->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Puesto:</th>
                                    <td>
                                        {{ $user->puesto->nombre_puesto ?? 'Sin puesto' }}
                                        @if($user->puesto)
                                        <span class="badge bg-{{ getColorByNivel($user->puesto->nivel_jerarquico) }}">
                                            Nivel {{ $user->puesto->nivel_jerarquico }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Registro:</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas del Usuario -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Evaluaciones Realizadas
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-info">{{ $user->evaluacionesComoEvaluador->count() }}</h2>
                            <p class="text-muted mb-0">Evaluaciones realizadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-line me-2"></i>Evaluaciones Recibidas
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-success">{{ $user->evaluacionesComoEvaluado->count() }}</h2>
                            <p class="text-muted mb-0">Evaluaciones recibidas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Información de Jerarquía -->
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>Jerarquía del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    @if($user->puesto)
                    <div class="text-center">
                        <h4 class="text-{{ getColorByNivel($user->puesto->nivel_jerarquico) }}">
                            Nivel {{ $user->puesto->nivel_jerarquico }}
                        </h4>
                        <div class="mt-3">
                            <p class="mb-2">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    <strong>Puede evaluar a:</strong><br>
                                    Niveles {{ $user->puesto->nivel_jerarquico + 1 }} al 9
                                </small>
                            </p>
                            <p class="mb-0">
                                <small class="text-info">
                                    <i class="fas fa-arrow-down me-1"></i>
                                    <strong>Le pueden evaluar:</strong><br>
                                    @if($user->puesto->nivel_jerarquico > 1)
                                        Niveles 1 al {{ $user->puesto->nivel_jerarquico - 1 }}
                                    @else
                                        Nadie
                                    @endif
                                </small>
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="text-center text-muted">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>El usuario no tiene puesto asignado</p>
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
