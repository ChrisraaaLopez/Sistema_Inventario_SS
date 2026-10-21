@extends('layouts.app')

@section('title', 'Detalle del artículo')

@section('content')
<h1>{{ $articulo->Descripcion }}</h1>

@if($articulo->URL_Imagen)
    <div class="mb-4">
        <img src="{{ route('articulos.imagen', $articulo) }}" alt="Imagen del artículo" loading="lazy" class="img-fluid rounded border" style="max-width: 320px; max-height: 320px; object-fit: contain;" onerror="this.closest('div').remove();">
    </div>
@endif

<table class="table table-bordered w-auto">
    <tr><th>Marca / Modelo</th><td>{{ $articulo->marca->Nombre }} / {{ $articulo->modelo->Nombre }}</td></tr>
    <tr><th>N° de serie</th><td>{{ $articulo->N_Serie ?? '—' }}</td></tr>
    <tr><th>Color</th><td>{{ $articulo->Color ?? '—' }}</td></tr>
    <tr><th>Categoría</th><td>{{ $articulo->categoria->Nombre }}</td></tr>
    <tr><th>Tipo</th><td>{{ $articulo->tipo->Nombre }}</td></tr>
    <tr><th>Ubicación</th><td>{{ $articulo->ubicacion->Nombre }} ({{ $articulo->ubicacion->Edificio }}, planta {{ $articulo->ubicacion->Planta }})</td></tr>
    <tr><th>Responsable</th><td>{{ $articulo->empleado->Nombre }} {{ $articulo->empleado->Apellido_Paterno }}</td></tr>
    <tr><th>Factura</th><td>{{ $articulo->factura->Folio ?? 'Sin factura asociada' }}</td></tr>
    <tr><th>Estado</th><td>{{ $articulo->Estado }}</td></tr>
    <tr><th>Tipo de artículo</th><td>{{ $articulo->Tipo_Articulo }}</td></tr>
    <tr><th>Notas</th><td>{{ $articulo->Notas ?? '—' }}</td></tr>
    <tr><th>Comentarios</th><td>{{ $articulo->Comentarios ?? '—' }}</td></tr>
</table>

<a href="{{ route('articulos.index') }}" class="btn btn-secondary">Volver</a>
@endsection