@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Registro de Usuario
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_empleado" class="form-label">Nombre *</label>
                                <input id="nombre_empleado" type="text"
                                       class="form-control @error('nombre_empleado') is-invalid @enderror"
                                       name="nombre_empleado" value="{{ old('nombre_empleado') }}"
                                       required placeholder="Ej: Juan">
                                @error('nombre_empleado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidos_empleado" class="form-label">Apellidos *</label>
                                <input id="apellidos_empleado" type="text"
                                       class="form-control @error('apellidos_empleado') is-invalid @enderror"
                                       name="apellidos_empleado" value="{{ old('apellidos_empleado') }}"
                                       required placeholder="Ej: Pérez García">
                                @error('apellidos_empleado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_empleado" class="form-label">Número de Empleado *</label>
                                <input id="numero_empleado" type="text"
                                       class="form-control @error('numero_empleado') is-invalid @enderror"
                                       name="numero_empleado" value="{{ old('numero_empleado') }}"
                                       required placeholder="Ej: EMP001">
                                @error('numero_empleado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    <small>Este será tu usuario para iniciar sesión</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="puesto_id" class="form-label">Puesto *</label>
                                <select id="puesto_id" name="puesto_id"
                                        class="form-select @error('puesto_id') is-invalid @enderror" required>
                                    <option value="">Selecciona un puesto</option>
                                    @foreach($puestos as $puesto)
                                        <option value="{{ $puesto->id }}"
                                                {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                                            {{ $puesto->nombre_puesto }} (Nivel {{ $puesto->nivel_jerarquico }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('puesto_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso *</label>
                                <input id="fecha_ingreso" type="date"
                                       class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                       name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
                                @error('fecha_ingreso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required placeholder="Mínimo 8 caracteres">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">Confirmar Contraseña *</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required placeholder="Repite tu contraseña">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Registrarse
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
