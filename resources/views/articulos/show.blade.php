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
    <tr><th>Código</th><td>{{ $articulo->Codigo_Barras ?? '—' }}</td></tr>
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

@if($articulo->Codigo_Barras)
    <div class="mb-4">
        <h3>Etiqueta de inventario</h3>
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <div id="barcodeShow" class="border rounded p-2 bg-white" style="min-width:220px; min-height:120px; display:flex; align-items:center; justify-content:center;"></div>
            <div id="qrShow" class="border rounded p-2 bg-white" style="min-width:120px; min-height:120px; display:flex; align-items:center; justify-content:center;"></div>
        </div>
        <button type="button" id="reprintCodeButton" class="btn btn-outline-primary">Reimprimir código de barras y QR</button>
    </div>
@else
    <div class="mb-4">
        <p class="text-muted">Este artículo no tiene código de barras / QR.</p>
        @if(auth()->user()?->rol?->Nombre === 'Administrador')
            <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-outline-primary">Editar</a>
        @endif
    </div>
@endif

<div id="printLabelsArea" class="print-labels" style="display:none"></div>

<a href="{{ route('articulos.index') }}" class="btn btn-secondary">Volver</a>

<script src="{{ asset('vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
<script src="{{ asset('vendor/qrcodejs/qrcode.min.js') }}"></script>
<script>
(function () {
    const codigoArticulo = @json($articulo->Codigo_Barras);
    const barcodePreview = document.getElementById('barcodeShow');
    const qrPreview = document.getElementById('qrShow');
    const printButton = document.getElementById('reprintCodeButton');
    const area = document.getElementById('printLabelsArea');

    function showPreviewError(element) {
        if (!element) {
            return;
        }

        element.innerHTML = '<div class="text-muted">No se pudo generar la vista previa</div>';
    }

    function drawPreview(value) {
        if (!value || !barcodePreview || !qrPreview) {
            return;
        }

        barcodePreview.innerHTML = '<svg id="barcodePreviewSvg"></svg>';
        qrPreview.innerHTML = '<div id="qrPreviewInner"></div>';

        const barcodeSvg = document.getElementById('barcodePreviewSvg');
        const qrInner = document.getElementById('qrPreviewInner');

        if (!window.JsBarcode) {
            showPreviewError(barcodePreview);
        } else {
            try {
                JsBarcode(barcodeSvg, value, { format: 'CODE128', displayValue: true, fontSize: 14, margin: 8 });
            } catch (error) {
                showPreviewError(barcodePreview);
            }
        }

        if (!window.QRCode) {
            showPreviewError(qrPreview);
        } else {
            try {
                new QRCode(qrInner, { text: value, width: 120, height: 120 });
            } catch (error) {
                showPreviewError(qrPreview);
            }
        }
    }

    function printLabel() {
        if (!codigoArticulo || !area) {
            return;
        }

        area.innerHTML = '<div class="print-label"><strong>ETIQUETA DE INVENTARIO</strong><svg id="printBarcode"></svg><div style="font-size:12px;word-break:break-all">' + codigoArticulo.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])) + '</div><div id="printQr"></div></div>';
        area.style.display = 'flex';

        const barcodePrint = document.getElementById('printBarcode');
        const qrPrint = document.getElementById('printQr');

        if (!window.JsBarcode) {
            if (barcodePrint) {
                barcodePrint.innerHTML = '<div class="text-muted">No se pudo generar la vista previa</div>';
            }
        } else {
            try {
                JsBarcode(barcodePrint, codigoArticulo, { format: 'CODE128', displayValue: false, margin: 5 });
            } catch (error) {
                if (barcodePrint) {
                    barcodePrint.innerHTML = '<div class="text-muted">No se pudo generar la vista previa</div>';
                }
            }
        }

        if (!window.QRCode) {
            if (qrPrint) {
                qrPrint.innerHTML = '<div class="text-muted">No se pudo generar la vista previa</div>';
            }
        } else {
            try {
                new QRCode(qrPrint, { text: codigoArticulo, width: 100, height: 100 });
            } catch (error) {
                if (qrPrint) {
                    qrPrint.innerHTML = '<div class="text-muted">No se pudo generar la vista previa</div>';
                }
            }
        }

        setTimeout(() => {
            try {
                window.print();
            } finally {
                area.style.display = 'none';
            }
        }, 300);

        window.onafterprint = function () {
            area.style.display = 'none';
        };
    }

    drawPreview(codigoArticulo);
    printButton?.addEventListener('click', printLabel);
})();
</script>

<style>
.print-labels{display:none}.print-labels,.print-labels *{visibility:visible}.print-labels{position:absolute;left:0;top:0;display:flex;gap:12px}.print-label{border:1px solid #aaa;padding:12px;text-align:center;width:280px;break-inside:avoid}.print-label svg{max-width:100%}
@media print{
  body * { visibility: hidden; }
  .print-labels, .print-labels * { visibility: visible; }
  .print-labels { position: absolute; left: 0; top: 0; display: flex; gap: 12px; }
  .print-label { border: 1px solid #aaa; padding: 12px; text-align: center; width: 280px; break-inside: avoid; }
  .print-label svg { max-width: 100%; }
}
</style>
@endsection