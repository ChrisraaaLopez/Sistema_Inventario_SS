@extends('layouts.app')

@section('title', 'Editar tipo')

@section('content')
<div class="page-header"><h1>Editar tipo</h1></div>

<div class="card-form"><form action="{{ route('tipos.update', $tipo) }}" method="POST" class="mt-3">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="Nombre" value="{{ old('Nombre', $tipo->Nombre) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Categoría:</label>
        <select name="FkId_Categoria" class="form-select" required>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->Id_Categoria }}" {{ old('FkId_Categoria', $tipo->FkId_Categoria) == $categoria->Id_Categoria ? 'selected' : '' }}>
                    {{ $categoria->Nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Estatus:</label>
        <select name="Estatus" class="form-select" required>
            @foreach(['Activo', 'Inactivo'] as $opcion)
                <option value="{{ $opcion }}" {{ old('Estatus', $tipo->Estatus) == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('tipos.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection