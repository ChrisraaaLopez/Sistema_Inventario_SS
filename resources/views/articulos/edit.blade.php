@extends('layouts.app')

@section('title', 'Editar artículo')

@section('content')
<div class="page-header"><h1>Editar artículo</h1></div>

<div class="card-form"><form action="{{ route('articulos.update', $articulo) }}" method="POST" class="mt-3" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Descripción:</label>
        <input type="text" name="Descripcion" value="{{ old('Descripcion', $articulo->Descripcion) }}" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Marca:</label>
            <select id="marca" name="FkId_Marca" class="form-select" required>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->Id_Marca }}" {{ old('FkId_Marca', $articulo->FkId_Marca) == $marca->Id_Marca ? 'selected' : '' }}>{{ $marca->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Modelo:</label>
            <select id="modelo" name="FkId_Modelo" class="form-select" required>
                <option value="{{ $articulo->FkId_Modelo }}" selected>{{ $articulo->modelo->Nombre }}</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de serie:</label>
<label class="form-label" for="Codigo_Barras">Código de barras / QR</label>
            <input id="Codigo_Barras" name="Codigo_Barras" value="{{ old('Codigo_Barras', $articulo->Codigo_Barras ?? '') }}" maxlength="120" class="form-control" autocomplete="off" placeholder="Escribe o genera un código">
            <div class="d-flex flex-wrap gap-2 mt-2"><button type="button" class="btn btn-outline-primary btn-sm" id="generateCode">Generar código</button><button type="button" class="btn btn-outline-secondary btn-sm" id="printLabels">Imprimir etiquetas</button></div>
            <div class="code-preview mt-3"><div><strong>Código de barras</strong><svg id="barcodePreview"></svg></div><div><strong>Código QR</strong><div id="qrPreview"></div></div></div>
            <input type="text" name="N_Serie" value="{{ old('N_Serie', $articulo->N_Serie) }}" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Color:</label>
            <input type="text" name="Color" value="{{ old('Color', $articulo->Color) }}" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Categoría:</label>
            <select name="FkId_Categoria" class="form-select" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->Id_Categoria }}" {{ old('FkId_Categoria', $articulo->FkId_Categoria) == $categoria->Id_Categoria ? 'selected' : '' }}>{{ $categoria->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tipo:</label>
            <select name="FkId_Tipo" class="form-select" required>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->Id_Tipo }}" {{ old('FkId_Tipo', $articulo->FkId_Tipo) == $tipo->Id_Tipo ? 'selected' : '' }}>{{ $tipo->Nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Ubicación:</label>
            <select name="FkId_Ubicacion" class="form-select" required>
                @foreach($ubicaciones as $ubicacion)
                    <option value="{{ $ubicacion->Id_Ubicacion }}" {{ old('FkId_Ubicacion', $articulo->FkId_Ubicacion) == $ubicacion->Id_Ubicacion ? 'selected' : '' }}>{{ $ubicacion->Nombre }} ({{ $ubicacion->Edificio }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Empleado responsable:</label>
            <select name="FkId_Empleado" class="form-select" required>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->Id_Empleado }}" {{ old('FkId_Empleado', $articulo->FkId_Empleado) == $empleado->Id_Empleado ? 'selected' : '' }}>{{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Factura (opcional):</label>
        <select name="FkId_Factura" class="form-select">
            <option value="">-- Sin factura --</option>
            @foreach($facturas as $factura)
                <option value="{{ $factura->Id_Factura }}" {{ old('FkId_Factura', $articulo->FkId_Factura) == $factura->Id_Factura ? 'selected' : '' }}>{{ $factura->Folio }} — {{ $factura->Proveedor }}</option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Estado:</label>
            <select name="Estado" class="form-select" required>
                @foreach(['Bien', 'Reparacion', 'Dañado', 'Obsoleto'] as $e)
                    <option value="{{ $e }}" {{ old('Estado', $articulo->Estado) == $e ? 'selected' : '' }}>{{ $e }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tipo de artículo:</label>
            @if(in_array($articulo->Tipo_Articulo, ['En Proceso de Baja', 'Baja']))
                <input type="text" class="form-control" value="{{ $articulo->Tipo_Articulo }}" disabled>
                <input type="hidden" name="Tipo_Articulo" value="{{ $articulo->Tipo_Articulo }}">
                <small class="text-muted">Este artículo está en proceso de baja. Solo el administrador puede reactivarlo.</small>
            @else
                <select name="Tipo_Articulo" class="form-select" required>
                    @foreach(['Capitalizable', 'No Capitalizable', 'Consumible'] as $t)
                        <option value="{{ $t }}" {{ old('Tipo_Articulo', $articulo->Tipo_Articulo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Notas:</label>
        <input type="text" name="Notas" value="{{ old('Notas', $articulo->Notas) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Comentarios:</label>
        <input type="text" name="Comentarios" value="{{ old('Comentarios', $articulo->Comentarios) }}" class="form-control">
    </div>

    @if($articulo->URL_Imagen)
        <div class="mb-3">
            <label class="form-label d-block">Imagen actual:</label>
            <img src="{{ route('articulos.imagen', $articulo) }}" width="140" class="img-thumbnail">
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Cambiar imagen (opcional):</label>
        <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
            <input type="file" id="imagenArchivo" name="imagen" class="form-control" accept="image/*">
            <button type="button" id="btnTomarFoto" class="btn btn-outline-primary">Tomar foto</button>
            <button type="button" id="btnCancelarFoto" class="btn btn-outline-secondary d-none">Cancelar</button>
        </div>
        <div id="selectorCamaraWrap" class="mb-2 d-none">
            <label class="form-label small mb-1">Selecciona cámara:</label>
            <select id="selectorCamara" class="form-select" style="max-width: 320px;"></select>
        </div>
        <video id="videoCamara" class="d-none border rounded" autoplay playsinline muted style="max-width: 320px; width: 100%;"></video>
        <canvas id="canvasFoto" class="d-none"></canvas>
        <div id="previewFoto" class="d-none mt-2">
            <img id="fotoPreview" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 220px;">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('articulos.index') }}" class="btn btn-secondary">Cancelar</a>
</form></div>

<script>
const inputImagen = document.getElementById('imagenArchivo');
const video = document.getElementById('videoCamara');
const canvas = document.getElementById('canvasFoto');
const preview = document.getElementById('previewFoto');
const previewImg = document.getElementById('fotoPreview');
const btnTomarFoto = document.getElementById('btnTomarFoto');
const btnCancelarFoto = document.getElementById('btnCancelarFoto');
const selectorCamara = document.getElementById('selectorCamara');
const selectorCamaraWrap = document.getElementById('selectorCamaraWrap');
let streamCamera = null;
let cameraDevices = [];

function mostrarPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        preview.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

async function detectarCamaras() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return [];

    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        return devices.filter(device => device.kind === 'videoinput');
    } catch (error) {
        return [];
    }
}

function poblarSelectorCamaras() {
    selectorCamara.innerHTML = '';

    if (cameraDevices.length > 1) {
        selectorCamaraWrap.classList.remove('d-none');
        cameraDevices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.textContent = device.label || `Cámara ${index + 1}`;
            selectorCamara.appendChild(option);
        });
        if (!selectorCamara.value) {
            selectorCamara.value = cameraDevices[0].deviceId;
        }
    } else {
        selectorCamaraWrap.classList.add('d-none');
    }
}

async function abrirCamara(deviceId = null) {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        inputImagen.setAttribute('capture', 'environment');
        inputImagen.click();
        return;
    }

    try {
        const permisoInicial = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        permisoInicial.getTracks().forEach(track => track.stop());
    } catch (error) {
        // El navegador bloqueó el acceso; se cae al input de archivo como respaldo.
    }

    cameraDevices = await detectarCamaras();
    poblarSelectorCamaras();

    const selectedDeviceId = deviceId || selectorCamara.value || cameraDevices[0]?.deviceId;
    const constraints = selectedDeviceId
        ? {
            video: { deviceId: { exact: selectedDeviceId } },
            audio: false,
        }
        : {
            video: { facingMode: 'environment' },
            audio: false,
        };

    try {
        streamCamera = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = streamCamera;
        video.classList.remove('d-none');
        btnCancelarFoto.classList.remove('d-none');
        btnTomarFoto.textContent = 'Capturar foto';
    } catch (error) {
        try {
            streamCamera = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false,
            });
            video.srcObject = streamCamera;
            video.classList.remove('d-none');
            btnCancelarFoto.classList.remove('d-none');
            btnTomarFoto.textContent = 'Capturar foto';
        } catch (fallbackError) {
            inputImagen.setAttribute('capture', 'environment');
            inputImagen.click();
        }
    }
}

function cerrarCamara() {
    if (streamCamera) {
        streamCamera.getTracks().forEach(track => track.stop());
        streamCamera = null;
    }

    video.srcObject = null;
    video.classList.add('d-none');
    btnCancelarFoto.classList.add('d-none');
    btnTomarFoto.textContent = 'Tomar foto';
}

selectorCamara.addEventListener('change', async () => {
    if (streamCamera) {
        cerrarCamara();
    }
    await abrirCamara(selectorCamara.value);
});

btnTomarFoto.addEventListener('click', async () => {
    if (!streamCamera) {
        await abrirCamara();
        return;
    }

    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth || 1280;
    canvas.height = video.videoHeight || 720;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    canvas.toBlob((blob) => {
        if (!blob) return;

        const file = new File([blob], `articulo-${Date.now()}.png`, { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputImagen.files = dataTransfer.files;
        mostrarPreview(file);
        cerrarCamara();
    }, 'image/png');
});

btnCancelarFoto.addEventListener('click', () => {
    cerrarCamara();
});

inputImagen.addEventListener('change', () => {
    if (inputImagen.files && inputImagen.files[0]) {
        mostrarPreview(inputImagen.files[0]);
    }
});

document.getElementById('marca').addEventListener('change', function () {
    const marcaId = this.value;
    const modeloSelect = document.getElementById('modelo');
    modeloSelect.innerHTML = '<option value="">Cargando...</option>';

    fetch(`/modelos-por-marca/${marcaId}`)
        .then(res => res.json())
        .then(modelos => {
            modeloSelect.innerHTML = '<option value="">-- Selecciona un modelo --</option>';
            modelos.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.Id_Modelo;
                opt.textContent = m.Nombre;
                modeloSelect.appendChild(opt);
            });
        });
});
</script>

<style>.code-preview{display:flex;gap:18px;flex-wrap:wrap;padding:14px;border:1px solid #dbe3ef;border-radius:12px;background:#fff}.code-preview>div{min-width:180px}.code-preview svg{max-width:100%;height:auto}.code-preview strong{display:block;font-size:.85rem;margin-bottom:8px}#qrPreview img{max-width:150px;height:auto}@media print{body *{visibility:hidden}.print-labels,.print-labels *{visibility:visible}.print-labels{position:absolute;left:0;top:0;display:flex;gap:12px}.print-label{border:1px solid #aaa;padding:12px;text-align:center;width:280px;break-inside:avoid}.print-label svg{max-width:100%}}</style>
<div id="printLabelsArea" class="print-labels" style="display:none"></div>
<script src="{{ asset('vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
<script src="{{ asset('vendor/qrcodejs/qrcode.min.js') }}"></script>
<script>
(function(){const input=document.getElementById('Codigo_Barras');if(!input)return;const barcode=document.getElementById('barcodePreview'),qr=document.getElementById('qrPreview');
function draw(){const val=input.value.trim();barcode.innerHTML='';qr.innerHTML='';if(!val)return;if(window.JsBarcode){try{JsBarcode(barcode,val,{format:'CODE128',displayValue:true,fontSize:14,margin:8});}catch(e){barcode.innerHTML='';}}if(window.QRCode)new QRCode(qr,{text:val,width:150,height:150,correctLevel:QRCode.CorrectLevel.M});}
input.addEventListener('input',draw);document.getElementById('generateCode')?.addEventListener('click',()=>{if(!input.value.trim())input.value='INV-'+Date.now().toString().slice(-10);draw();});document.getElementById('printLabels')?.addEventListener('click',()=>{draw();const val=input.value.trim();if(!val){alert('Escribe o genera primero un código.');return;}const area=document.getElementById('printLabelsArea');area.innerHTML='<div class="print-label"><strong>ETIQUETA DE INVENTARIO</strong><svg id="printBarcode"></svg><div style="font-size:12px;word-break:break-all">'+val.replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))+'</div><div id="printQr"></div></div>';area.style.display='flex';try{JsBarcode(document.getElementById('printBarcode'),val,{format:'CODE128',displayValue:false,margin:5});}catch(e){}new QRCode(document.getElementById('printQr'),{text:val,width:100,height:100});setTimeout(()=>{window.print();area.style.display='none';},300);});draw();})();
</script>

@endsection