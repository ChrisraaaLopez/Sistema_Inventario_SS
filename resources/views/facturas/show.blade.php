@extends('layouts.app')
@section('title', 'Ver factura')
@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2"><h1>Factura {{ $factura->Folio }}</h1><div><a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver</a>@if($factura->URL_Factura)<a href="{{ route('facturas.archivo', $factura) }}"
   target="_blank" rel="noopener"
   class="btn btn-outline-primary">Abrir PDF en otra pestaña</a><a href="{{ route('facturas.archivo', $factura) }}" download class="btn btn-primary">Descargar PDF</a>@endif</div></div>
<div class="card p-3"><p><strong>Proveedor:</strong> {{ $factura->Proveedor }} &nbsp;|&nbsp; <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($factura->Fecha)->format('d/m/Y') }} &nbsp;|&nbsp; <strong>Monto:</strong> ${{ number_format($factura->Monto, 2) }}</p>
@if($factura->URL_Factura)
<iframe title="PDF de factura {{ $factura->Folio }}" src="{{ route('facturas.archivo', $factura) }}#toolbar=1&navpanes=0" style="width:100%;height:min(78vh,900px);border:1px solid #d8dee9;border-radius:10px" loading="lazy"></iframe>
@else<p class="text-muted">Esta factura no tiene PDF cargado todavía. Entra a editar la factura para adjuntarlo.</p>@endif</div>
@endsection
