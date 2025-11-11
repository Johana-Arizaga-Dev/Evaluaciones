@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Puesto
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('puestos.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre_puesto" class="form-label">Nombre del Puesto *</label>
                            <input type="text" class="form-control @error('nombre_puesto') is-invalid @enderror"
                                   id="nombre_puesto" name="nombre_puesto" value="{{ old('nombre_puesto') }}"
                                   placeholder="Ej: Gerente General, Desarrollador Senior, etc." required>
                            @error('nombre_puesto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nivel_jerarquico" class="form-label">Nivel de Jerarquía *</label>
                            <select class="form-select @error('nivel_jerarquico') is-invalid @enderror"
                                    id="nivel_jerarquico" name="nivel_jerarquico" required>
                                <option value="">Selecciona un nivel</option>
                                @foreach($nivelesJerarquia as $nivel)
                                <option value="{{ $nivel }}" {{ old('nivel_jerarquico') == $nivel ? 'selected' : '' }}>
                                    Nivel {{ $nivel }}
                                    @if($nivel == 1)
                                        - (Máxima autoridad - Puede evaluar a todos)
                                    @elseif($nivel == 9)
                                        - (Nivel base - Solo puede ser evaluado)
                                    @else
                                        - (Puede evaluar niveles {{ $nivel + 1 }} al 9)
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <small>Pueden existir múltiples puestos con el mismo nivel de jerarquía.</small>
                            </div>
                            @error('nivel_jerarquico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('puestos.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Puesto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
