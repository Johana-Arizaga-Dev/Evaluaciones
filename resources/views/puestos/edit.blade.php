@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Puesto: {{ $puesto->nombre_puesto }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('puestos.update', $puesto) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nombre_puesto" class="form-label">Nombre del Puesto *</label>
                            <input type="text"
                                   class="form-control @error('nombre_puesto') is-invalid @enderror"
                                   id="nombre_puesto"
                                   name="nombre_puesto"
                                   value="{{ old('nombre_puesto', $puesto->nombre_puesto) }}"
                                   required>
                            @error('nombre_puesto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nivel_jerarquico" class="form-label">Nivel de Jerarquía *</label>
                            <select class="form-select @error('nivel_jerarquico') is-invalid @enderror"
                                    id="nivel_jerarquico"
                                    name="nivel_jerarquico"
                                    required>
                                <option value="">Selecciona un nivel</option>
                                @foreach($nivelesJerarquia as $nivel)
                                <option value="{{ $nivel }}"
                                        {{ old('nivel_jerarquico', $puesto->nivel_jerarquico) == $nivel ? 'selected' : '' }}>
                                    Nivel {{ $nivel }}
                                    @if($nivel == 1)
                                        - (Máxima autoridad - Puede evaluar a todos)
                                    @elseif($nivel == 9)
                                        - (Nivel base - Solo puede ser evaluado)
                                    @else
                                        - (Puede evaluar niveles {{ $nivel + 1 }} al 9)
                                    @endif
                                    @if($nivel == $puesto->nivel_jerarquico)
                                        - (Actual)
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
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Actualizar Puesto
                            </button>
                        </div>
                    </form>
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
