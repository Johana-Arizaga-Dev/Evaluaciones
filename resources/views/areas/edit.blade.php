@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Área: {{ $area->nombre_area }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('areas.update', $area) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nombre_area" class="form-label">Nombre del Área *</label>
                            <input type="text"
                                   class="form-control @error('nombre_area') is-invalid @enderror"
                                   id="nombre_area"
                                   name="nombre_area"
                                   value="{{ old('nombre_area', $area->nombre_area) }}"
                                   required>
                            @error('nombre_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('areas.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Actualizar Área
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
