@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<div class="page-header"><div><h1>Facturas</h1></div><div>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
<a href="{{ route('facturas.create') }}" class="btn btn-primary mb-3">+ Nueva factura</a>
@endif

</div></div>

<div class="table-wrap">
<table class="table table-bordered table-striped align-middle">
    <tr>
        <th>Folio</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Monto</th>
        <th>PDF</th>
        <th>Acciones</th>
    </tr>
    @foreach($facturas as $factura)
    <tr>
        <td>{{ $factura->Folio }}</td>
        <td>{{ \Carbon\Carbon::parse($factura->Fecha)->format('d/m/Y') }}</td>
        <td>{{ $factura->Proveedor }}</td>
        <td>${{ number_format($factura->Monto, 2) }}</td>
        <td>
            @if($factura->URL_Factura)
                <a href="{{ route('facturas.show', $factura) }}"
   class="btn btn-sm btn-outline-primary">Ver PDF</a>
            @else
                <span class="text-muted">Sin PDF</span>
            @endif
        </td>
        <td>
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-sm btn-warning">Editar</a>
@endif
@if(auth()->user()?->rol?->Nombre === 'Administrador')
            <form action="{{ route('facturas.destroy', $factura) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta factura?')">Eliminar</button>
            </form>
@endif
        </td>
    </tr>
    @endforeach
</table>
</div>

{{ $facturas->links() }}
@endsection
