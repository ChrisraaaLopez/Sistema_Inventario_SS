@extends('layouts.app')

@section('title', 'Editar marca')

@section('content')
<div class="page-header"><h1>Editar marca</h1></div>

<div class="card-form"><form action="{{ route('marcas.update', $marca) }}" method="POST" class="mt-3">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre', $marca->Nombre) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            @foreach(['Activo', 'Inactivo'] as $opcion)
                <option value="{{ $opcion }}" {{ old('Estatus', $marca->Estatus) == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('marcas.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection