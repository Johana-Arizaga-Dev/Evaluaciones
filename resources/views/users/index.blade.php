@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Gestión de Usuarios</h1>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Lista de Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>N° Empleado</th>
                                    <th>Puesto</th>
                                    <th>Nivel</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->nombre_empleado }} {{ $user->apellidos_empleado }}</strong>
                                    </td>
                                    <td>
                                        <code>{{ $user->numero_empleado }}</code>
                                    </td>
                                    <td>
                                        {{ $user->puesto->nombre_puesto ?? 'Sin puesto' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ getColorByNivel($user->puesto->nivel_jerarquico ?? 0) }}">
                                            Nivel {{ $user->puesto->nivel_jerarquico ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $user->fecha_ingreso->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Editar usuario">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar usuario"
                                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
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
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay usuarios registrados</h4>
                        <p class="text-muted mb-4">Comienza creando el primer usuario en el sistema.</p>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Crear Primer Usuario
                        </a>
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
