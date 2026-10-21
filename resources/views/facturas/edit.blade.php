@extends('layouts.app')

@section('title', 'Editar factura')

@section('content')
<div class="page-header"><h1>Editar factura</h1></div>

<div class="card-form"><form action="{{ route('facturas.update', $factura) }}" method="POST" enctype="multipart/form-data" class="mt-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Folio:</label>
        <input type="text" name="Folio" value="{{ old('Folio', $factura->Folio) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha:</label>
        <input type="date" name="Fecha" value="{{ old('Fecha', $factura->Fecha) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Proveedor:</label>
        <input type="text" name="Proveedor" value="{{ old('Proveedor', $factura->Proveedor) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Monto:</label>
        <input type="number" step="0.01" name="Monto" value="{{ old('Monto', $factura->Monto) }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción (opcional):</label>
        <textarea name="Descripcion" class="form-control">{{ old('Descripcion', $factura->Descripcion) }}</textarea>
    </div>

    @if($factura->URL_Factura)
        <div class="mb-3">
            <label class="form-label d-block">PDF actual:</label>
            <a href="{{ route('facturas.show', $factura) }}"
   class="btn btn-outline-primary btn-sm">Ver PDF actual</a>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">{{ $factura->URL_Factura ? 'Reemplazar PDF (opcional):' : 'Subir PDF (opcional):' }}</label>
        <input type="file" name="pdf" class="form-control" accept="application/pdf">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>
@endsection