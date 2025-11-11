@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Dashboard</h1>
            <div class="text-end">
                <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ now()->format('d/m/Y') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="row">
    <!-- Evaluaciones Realizadas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Evaluaciones Realizadas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['evaluaciones_realizadas'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluaciones Recibidas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Evaluaciones Recibidas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['evaluaciones_recibidas'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promedio General -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Promedio General
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{ number_format($estadisticas['promedio_evaluaciones'], 1) }}%
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                         style="width: {{ $estadisticas['promedio_evaluaciones'] }}%"
                                         aria-valuenow="{{ $estadisticas['promedio_evaluaciones'] }}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendientes por Evaluar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pendientes por Evaluar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $usuariosPendientes }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(auth()->user()->puedeEvaluar() && $usuariosPendientes > 0)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-primary btn-lg w-100 h-100 py-3">
                            <div class="text-center">
                                <i class="fas fa-play-circle fa-2x mb-2"></i>
                                <h5>Iniciar Evaluación</h5>
                                <small class="d-block">{{ $usuariosPendientes }} pendientes</small>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->puedeCrearFormularios())
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('formularios.create') }}" class="btn btn-success btn-lg w-100 h-100 py-3">
                            <div class="text-center">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <h5>Crear Formulario</h5>
                                <small class="d-block">Diseñar nueva evaluación</small>
                            </div>
                        </a>
                    </div>
                    @endif

                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('evaluaciones.index') }}" class="btn btn-info btn-lg w-100 h-100 py-3">
                            <div class="text-center">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <h5>Ver Historial</h5>
                                <small class="d-block">Evaluaciones anteriores</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Evaluaciones Recientes -->
@if($evaluacionesRecientes->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Evaluaciones Recientes</h6>
                <a href="{{ route('evaluaciones.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todas <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Evaluado</th>
                                <th>Formulario</th>
                                <th>Fecha</th>
                                <th>Puntaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluacionesRecientes as $evaluacion)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $evaluacion->evaluado->nombre_empleado }} {{ $evaluacion->evaluado->apellidos_empleado }}</h6>
                                            <small class="text-muted">{{ $evaluacion->evaluado->numero_empleado }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $evaluacion->formulario->titulo }}</span>
                                </td>
                                <td>{{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $color = 'success';
                                        if ($evaluacion->porcentaje_obtenido < 60) {
                                            $color = 'danger';
                                        } elseif ($evaluacion->porcentaje_obtenido < 80) {
                                            $color = 'warning';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }} fs-6">
                                        {{ number_format($evaluacion->porcentaje_obtenido, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('evaluaciones.show', $evaluacion) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('evaluaciones.download', $evaluacion) }}"
                                           class="btn btn-sm btn-outline-success"
                                           title="Descargar PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
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
<!-- Mensaje cuando no hay evaluaciones -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No hay evaluaciones recientes</h3>
                <p class="text-muted mb-4">Aún no has realizado ninguna evaluación.</p>
                @if(auth()->user()->puedeEvaluar())
                <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-play-circle me-2"></i>Comenzar a Evaluar
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Información de Jerarquía -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mi Información de Jerarquía</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="fas fa-user me-2"></i>Información Personal:</strong>
                            <div class="mt-2 ps-4">
                                <div>{{ auth()->user()->nombre_empleado }} {{ auth()->user()->apellidos_empleado }}</div>
                                <small class="text-muted">N° Empleado: {{ auth()->user()->numero_empleado }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-briefcase me-2"></i>Puesto:</strong>
                            <div class="mt-2 ps-4">
                                <span class="badge bg-primary fs-6">
                                    {{ auth()->user()->puesto->nombre_puesto ?? 'No asignado' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="fas fa-sitemap me-2"></i>Jerarquía del Sistema:</strong>
                            <div class="mt-2 ps-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2">Nivel {{ auth()->user()->puesto->nivel_jerarquico ?? 'N/A' }}</span>
                                    <small class="text-muted">
                                        @if(auth()->user()->puesto)
                                            @if(auth()->user()->puesto->nivel_jerarquico == 1)
                                                (Máxima autoridad)
                                            @elseif(auth()->user()->puesto->nivel_jerarquico == 9)
                                                (Nivel base)
                                            @else
                                                (Nivel intermedio)
                                            @endif
                                        @endif
                                    </small>
                                </div>

                                @if(auth()->user()->puesto)
                                <div class="mt-3">
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        Puedo evaluar a: Niveles {{ auth()->user()->puesto->nivel_jerarquico + 1 }} al 9
                                    </small>
                                    <br>
                                    <small class="text-info">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        Me pueden evaluar: Niveles 1 al {{ auth()->user()->puesto->nivel_jerarquico - 1 }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Funciones Disponibles -->
@if(auth()->user()->puedeAdministrar() || auth()->user()->puedeCrearFormularios())
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Funciones Disponibles</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(auth()->user()->puedeAdministrar())
                    <div class="col-md-4 mb-3">
                        <div class="card bg-admin text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-cog fa-2x mb-2"></i>
                                <h6>Administración</h6>
                                <small>Gestión completa del sistema</small>
                                <div class="mt-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm me-1">Usuarios</a>
                                    <a href="{{ route('puestos.index') }}" class="btn btn-light btn-sm">Puestos</a>
                                </div>
                            </div>
                        </div>
                        <style>.bg-admin { background: linear-gradient(45deg, #dc3545, #e35d6a); }</style>
                    </div>
                    @endif

                    @if(auth()->user()->puedeCrearFormularios())
                    <div class="col-md-4 mb-3">
                        <div class="card bg-creador text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-edit fa-2x mb-2"></i>
                                <h6>Crear Formularios</h6>
                                <small>Diseñar evaluaciones</small>
                                <div class="mt-2">
                                    <a href="{{ route('formularios.create') }}" class="btn btn-light btn-sm">Nuevo Formulario</a>
                                </div>
                            </div>
                        </div>
                        <style>.bg-creador { background: linear-gradient(45deg, #28a745, #5cb85c); }</style>
                    </div>
                    @endif

                    @if(auth()->user()->puedeEvaluar())
                    <div class="col-md-4 mb-3">
                        <div class="card bg-evaluador text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                                <h6>Evaluar</h6>
                                <small>Realizar evaluaciones</small>
                                <div class="mt-2">
                                    <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-light btn-sm">Evaluar Colaboradores</a>
                                </div>
                            </div>
                        </div>
                        <style>.bg-evaluador { background: linear-gradient(45deg, #007bff, #5a9bd4); }</style>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .progress {
        height: 0.5rem;
    }
</style>
@endpush
