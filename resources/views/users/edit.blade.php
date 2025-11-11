@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Usuario: {{ $user->nombre_empleado }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_empleado" class="form-label">Nombre *</label>
                                <input type="text"
                                       class="form-control @error('nombre_empleado') is-invalid @enderror"
                                       id="nombre_empleado"
                                       name="nombre_empleado"
                                       value="{{ old('nombre_empleado', $user->nombre_empleado) }}"
                                       required>
                                @error('nombre_empleado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidos_empleado" class="form-label">Apellidos *</label>
                                <input type="text"
                                       class="form-control @error('apellidos_empleado') is-invalid @enderror"
                                       id="apellidos_empleado"
                                       name="apellidos_empleado"
                                       value="{{ old('apellidos_empleado', $user->apellidos_empleado) }}"
                                       required>
                                @error('apellidos_empleado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_empleado" class="form-label">Número de Empleado *</label>
                                <input type="text"
                                       class="form-control @error('numero_empleado') is-invalid @enderror"
                                       id="numero_empleado"
                                       name="numero_empleado"
                                       value="{{ old('numero_empleado', $user->numero_empleado) }}"
                                       required>
                                @error('numero_empleado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input type="date"
                                       class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                       id="fecha_ingreso"
                                       name="fecha_ingreso"
                                       value="{{ old('fecha_ingreso', $user->fecha_ingreso->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_ingreso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="puesto_id" class="form-label">Puesto *</label>
                                <select class="form-select @error('puesto_id') is-invalid @enderror"
                                        id="puesto_id"
                                        name="puesto_id"
                                        required>
                                    <option value="">Selecciona un puesto</option>
                                    @foreach($puestos as $puesto)
                                    <option value="{{ $puesto->id }}"
                                            {{ old('puesto_id', $user->puesto_id) == $puesto->id ? 'selected' : '' }}>
                                        {{ $puesto->nombre_puesto }} (Nivel {{ $puesto->nivel_jerarquico }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('puesto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <small>Dejar en blanco para mantener la contraseña actual</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
