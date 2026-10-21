@extends('layouts.app')

@section('title', 'Editar ubicación')

@section('content')
<div class="page-header"><h1>Editar ubicación</h1></div>

<div class="card-form"><form action="{{ route('ubicaciones.update', $ubicacion) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre', $ubicacion->Nombre) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Edificio:</label>
        <input type="text" name="Edificio" value="{{ old('Edificio', $ubicacion->Edificio) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Planta:</label>
        <input type="text" name="Planta" value="{{ old('Planta', $ubicacion->Planta) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            @foreach(['Activo', 'Inactivo'] as $opcion)
                <option value="{{ $opcion }}" {{ old('Estatus', $ubicacion->Estatus) == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection