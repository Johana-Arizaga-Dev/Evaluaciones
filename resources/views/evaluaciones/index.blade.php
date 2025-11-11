@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Mis Evaluaciones</h1>
                <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Evaluación
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros y Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $evaluaciones->count() }}</h4>
                    <small>Total Evaluaciones</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($evaluaciones->avg('porcentaje_obtenido') ?? 0, 1) }}%</h4>
                    <small>Promedio General</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $evaluaciones->where('porcentaje_obtenido', '>=', 80)->count() }}</h4>
                    <small>Excelentes (≥80%)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $evaluaciones->where('porcentaje_obtenido', '<', 60)->count() }}</h4>
                    <small>Por Mejorar (<60%)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Historial de Evaluaciones
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filtro' => 'todas']) }}">Todas</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filtro' => 'excelentes']) }}">Excelentes (≥80%)</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filtro' => 'buenas']) }}">Buenas (60-79%)</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['filtro' => 'mejorar']) }}">Por Mejorar (<60%)</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @if($evaluaciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Evaluado</th>
                                    <th>Formulario</th>
                                    <th>Fecha</th>
                                    <th>Puntaje</th>
                                    <th>Porcentaje</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluaciones as $evaluacion)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                {{ substr($evaluacion->evaluado->nombre_empleado, 0, 1) }}{{ substr($evaluacion->evaluado->apellidos_empleado, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $evaluacion->evaluado->nombre_empleado }} {{ $evaluacion->evaluado->apellidos_empleado }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $evaluacion->evaluado->puesto->nombre_puesto ?? 'Sin puesto' }}
                                                    | {{ $evaluacion->evaluado->numero_empleado }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $evaluacion->formulario->titulo }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $evaluacion->formulario->tipo === 'evaluacion' ? 'primary' : 'success' }}">
                                                {{ ucfirst($evaluacion->formulario->tipo) }}
                                            </span>
                                            @if($evaluacion->formulario->area)
                                            <span class="badge bg-secondary ms-1">{{ $evaluacion->formulario->area->nombre_area }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        {{ $evaluacion->fecha_evaluacion->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ $evaluacion->fecha_evaluacion->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $evaluacion->puntaje_obtenido }}/{{ $evaluacion->calificacion_total }}</strong>
                                        <br>
                                        <small class="text-muted">puntos</small>
                                    </td>
                                    <td>
                                        @php
                                            $color = 'success';
                                            $icon = 'fa-check-circle';
                                            $estado = 'Excelente';

                                            if ($evaluacion->porcentaje_obtenido < 60) {
                                                $color = 'danger';
                                                $icon = 'fa-exclamation-triangle';
                                                $estado = 'Por mejorar';
                                            } elseif ($evaluacion->porcentaje_obtenido < 80) {
                                                $color = 'warning';
                                                $icon = 'fa-check-circle';
                                                $estado = 'Bueno';
                                            }
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <i class="fas {{ $icon }} text-{{ $color }} me-2"></i>
                                            <div>
                                                <strong class="text-{{ $color }}">{{ number_format($evaluacion->porcentaje_obtenido, 1) }}%</strong>
                                                <br>
                                                <small class="text-muted">{{ $estado }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px; width: 100px;">
                                            <div class="progress-bar bg-{{ $color }}"
                                                 role="progressbar"
                                                 style="width: {{ $evaluacion->porcentaje_obtenido }}%"
                                                 aria-valuenow="{{ $evaluacion->porcentaje_obtenido }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
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
                                            @if(auth()->user()->puedeAdministrar())
                                            <form action="{{ route('evaluaciones.destroy', $evaluacion) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta evaluación?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar evaluación">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>



                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay evaluaciones registradas</h4>
                        <p class="text-muted mb-4">Aún no has realizado ninguna evaluación.</p>
                        <a href="{{ route('evaluaciones.pendientes') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle me-2"></i>Comenzar a Evaluar
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de rendimiento (opcional) -->
    @if($evaluaciones->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Resumen de Rendimiento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Distribución por Calificación:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="badge bg-success me-2">Excelente</span>
                                    <small>{{ $evaluaciones->where('porcentaje_obtenido', '>=', 80)->count() }} evaluaciones</small>
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-warning me-2">Bueno</span>
                                    <small>{{ $evaluaciones->whereBetween('porcentaje_obtenido', [60, 79.9])->count() }} evaluaciones</small>
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-danger me-2">Por Mejorar</span>
                                    <small>{{ $evaluaciones->where('porcentaje_obtenido', '<', 60)->count() }} evaluaciones</small>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Estadísticas Generales:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Promedio General:</strong>
                                    {{ number_format($evaluaciones->avg('porcentaje_obtenido') ?? 0, 1) }}%
                                </li>
                                <li class="mb-2">
                                    <strong>Mejor Evaluación:</strong>
                                    {{ number_format($evaluaciones->max('porcentaje_obtenido') ?? 0, 1) }}%
                                </li>
                                <li class="mb-2">
                                    <strong>Peor Evaluación:</strong>
                                    {{ number_format($evaluaciones->min('porcentaje_obtenido') ?? 0, 1) }}%
                                </li>
                                <li class="mb-2">
                                    <strong>Total Puntos Otorgados:</strong>
                                    {{ $evaluaciones->sum('puntaje_obtenido') }}/{{ $evaluaciones->sum('calificacion_total') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.avatar-placeholder {
    font-weight: bold;
    font-size: 14px;
}
.progress {
    background-color: #e9ecef;
    border-radius: 4px;
}
.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrado por tipo de evaluación
    const urlParams = new URLSearchParams(window.location.search);
    const filtro = urlParams.get('filtro');

    if (filtro) {
        // Resaltar el filtro activo
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            if (item.getAttribute('href')?.includes(`filtro=${filtro}`)) {
                item.classList.add('active');
            }
        });
    }

    // Tooltips para botones de acción
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
