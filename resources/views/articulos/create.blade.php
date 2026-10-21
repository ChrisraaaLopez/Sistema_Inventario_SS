@extends('layouts.app')

@section('title', 'Nuevo artículo')

@section('content')
<div class="page-header"><h1>Nuevo artículo</h1></div>

<div class="card-form"><form action="{{ route('articulos.store') }}" method="POST" class="mt-3" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Descripción:</label>
        <input type="text" name="Descripcion" value="{{ old('Descripcion') }}" class="form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Marca:</label>
            <select id="marca" name="FkId_Marca" class="form-select" required>
                <option value="">-- Selecciona una marca --</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->Id_Marca }}" {{ old('FkId_Marca') == $marca->Id_Marca ? 'selected' : '' }}>{{ $marca->Nombre }}</option>
                @endforeach
                <option value="__otra__">+ Otra marca (registrar ahora)</option>
            </select>
            <div id="nuevaMarcaWrap" class="mt-2 d-none"><div class="input-group"><input id="nuevaMarcaNombre" class="form-control" placeholder="Escribe el nombre de la nueva marca"><button type="button" id="guardarNuevaMarca" class="btn btn-outline-primary">Guardar marca</button></div><small id="marcaFeedback" class="text-secondary">La marca se guardará sin salir de este formulario.</small></div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Modelo:</label>
            <select id="modelo" name="FkId_Modelo" class="form-select" required>
                <option value="">-- Selecciona primero una marca --</option>
                <option value="__otro_modelo__">+ Otro modelo (registrar ahora)</option>
            </select>
            <div id="nuevoModeloWrap" class="mt-2 d-none"><div class="row g-2"><div class="col-md-6"><input id="nuevoModeloNombre" class="form-control" placeholder="Nombre del modelo"></div><div class="col-md-4"><select id="nuevoModeloTipo" class="form-select"><option value="">Tipo de artículo</option>@foreach($tipos as $tipo)<option value="{{ $tipo->Id_Tipo }}">{{ $tipo->Nombre }}</option>@endforeach</select></div><div class="col-md-2"><button type="button" id="guardarNuevoModelo" class="btn btn-outline-primary w-100">Guardar</button></div></div><small id="modeloFeedback" class="text-secondary">Se guardará sin salir del formulario.</small></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Número de serie:</label>
<label class="form-label" for="Codigo_Barras">Código de barras / QR</label>
            <input id="Codigo_Barras" name="Codigo_Barras" value="{{ old('Codigo_Barras') }}" maxlength="120" class="form-control" autocomplete="off" placeholder="Escribe o genera un código">
            <div class="d-flex flex-wrap gap-2 mt-2"><button type="button" class="btn btn-outline-primary btn-sm" id="generateCode">Generar código</button><button type="button" class="btn btn-outline-secondary btn-sm" id="printLabels">Imprimir etiquetas</button></div>
            <div class="code-preview mt-3"><div><strong>Código de barras</strong><svg id="barcodePreview"></svg></div><div><strong>Código QR</strong><div id="qrPreview"></div></div></div>
            <input type="text" name="N_Serie" value="{{ old('N_Serie') }}" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Color:</label>
            <input type="text" name="Color" value="{{ old('Color') }}" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Categoría:</label>
            <select name="FkId_Categoria" class="form-select" required>
                <option value="">-- Selecciona una categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->Id_Categoria }}" {{ old('FkId_Categoria') == $categoria->Id_Categoria ? 'selected' : '' }}>{{ $categoria->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tipo:</label>
            <select name="FkId_Tipo" class="form-select" required>
                <option value="">-- Selecciona un tipo --</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->Id_Tipo }}" {{ old('FkId_Tipo') == $tipo->Id_Tipo ? 'selected' : '' }}>{{ $tipo->Nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Ubicación:</label>
            <select name="FkId_Ubicacion" class="form-select" required>
                <option value="">-- Selecciona una ubicación --</option>
                @foreach($ubicaciones as $ubicacion)
                    <option value="{{ $ubicacion->Id_Ubicacion }}" {{ old('FkId_Ubicacion') == $ubicacion->Id_Ubicacion ? 'selected' : '' }}>{{ $ubicacion->Nombre }} ({{ $ubicacion->Edificio }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Empleado responsable:</label>
            <select name="FkId_Empleado" class="form-select" required>
                <option value="">-- Selecciona un empleado --</option>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->Id_Empleado }}" {{ old('FkId_Empleado') == $empleado->Id_Empleado ? 'selected' : '' }}>{{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Factura (opcional):</label>
        <div class="d-flex gap-2 align-items-start"><select id="facturaSelect" name="FkId_Factura" class="form-select">
            <option value="">-- Sin factura --</option>
            @foreach($facturas as $factura)
                <option value="{{ $factura->Id_Factura }}" {{ old('FkId_Factura') == $factura->Id_Factura ? 'selected' : '' }}>{{ $factura->Folio }} — {{ $factura->Proveedor }}</option>
            @endforeach
        </select><button type="button" class="btn btn-outline-primary text-nowrap" id="toggleFacturaRapida">+ Nueva factura</button></div>
        <div id="facturaRapida" class="card-panel p-3 mt-2 d-none"><div class="row g-2"><div class="col-md-3"><label class="form-label">Folio *</label><input id="facturaFolio" class="form-control"></div><div class="col-md-3"><label class="form-label">Fecha *</label><input id="facturaFecha" type="date" class="form-control" value="{{ date('Y-m-d') }}"></div><div class="col-md-3"><label class="form-label">Proveedor *</label><input id="facturaProveedor" class="form-control"></div><div class="col-md-3"><label class="form-label">Monto *</label><input id="facturaMonto" type="number" min="0" step="0.01" class="form-control"></div><div class="col-12"><label class="form-label">Descripción</label><input id="facturaDescripcion" class="form-control"></div><div class="col-12"><button type="button" id="guardarFacturaRapida" class="btn btn-primary">Guardar factura y seleccionarla</button> <small id="facturaFeedback" class="text-secondary"></small></div></div></div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Estado:</label>
            <select name="Estado" class="form-select" required>
                <option value="Bien" {{ old('Estado') == 'Bien' ? 'selected' : '' }}>Bien</option>
                <option value="Reparacion" {{ old('Estado') == 'Reparacion' ? 'selected' : '' }}>Reparación</option>
                <option value="Dañado" {{ old('Estado') == 'Dañado' ? 'selected' : '' }}>Dañado</option>
                <option value="Obsoleto" {{ old('Estado') == 'Obsoleto' ? 'selected' : '' }}>Obsoleto</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tipo de artículo:</label>
            <select name="Tipo_Articulo" class="form-select" required>
                <option value="Capitalizable" {{ old('Tipo_Articulo') == 'Capitalizable' ? 'selected' : '' }}>Capitalizable</option>
                <option value="No Capitalizable" {{ old('Tipo_Articulo') == 'No Capitalizable' ? 'selected' : '' }}>No Capitalizable</option>
                <option value="Consumible" {{ old('Tipo_Articulo') == 'Consumible' ? 'selected' : '' }}>Consumible</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Notas:</label>
        <input type="text" name="Notas" value="{{ old('Notas') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Comentarios:</label>
        <input type="text" name="Comentarios" value="{{ old('Comentarios') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen del artículo (opcional):</label>
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

    <button type="submit" class="btn btn-primary">Guardar</button>
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

    if (!marcaId) {
        modeloSelect.innerHTML = '<option value="">-- Selecciona primero una marca --</option>';
        return;
    }

    if (marcaId === '__otra__') { document.getElementById('nuevaMarcaWrap').classList.remove('d-none'); modeloSelect.innerHTML = '<option value="">Guarda primero la marca nueva</option>'; return; }
    document.getElementById('nuevaMarcaWrap').classList.add('d-none');
    document.getElementById('nuevoModeloWrap').classList.add('d-none');
    fetch(`/modelos-por-marca/${marcaId}`, {headers: {'Accept':'application/json'}})
        .then(res => { if (!res.ok) throw new Error('No se pudieron cargar los modelos ('+res.status+').'); return res.json(); })
        .then(modelos => {
            modeloSelect.innerHTML = '<option value="">-- Selecciona un modelo --</option>'; 
            modelos.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.Id_Modelo;
                opt.textContent = m.Nombre;
                modeloSelect.appendChild(opt);
            });
            const other = new Option('+ Otro modelo (registrar ahora)', '__otro_modelo__'); modeloSelect.appendChild(other);
        }).catch(error => { modeloSelect.innerHTML = '<option value="">Error al cargar modelos</option>'; alert(error.message+' Verifica la conexión y vuelve a elegir la marca.'); });
});

document.getElementById('guardarNuevaMarca')?.addEventListener('click', async function(){
 const nombre=document.getElementById('nuevaMarcaNombre').value.trim(); const feedback=document.getElementById('marcaFeedback'); if(!nombre){feedback.textContent='Escribe un nombre de marca.';return;} this.disabled=true; feedback.textContent='Guardando marca…';
 try { const response=await fetch('{{ route('marcas.quick-store') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({Nombre:nombre})}); const data=await response.json(); if(!response.ok) throw new Error(data.message||Object.values(data.errors||{}).flat().join(' ' )||'No se pudo guardar la marca.'); const option=new Option(data.Nombre,data.Id_Marca,true,true); const sentinel=document.querySelector('#marca option[value="__otra__"]'); sentinel.before(option); document.getElementById('marca').value=data.Id_Marca; document.getElementById('nuevaMarcaWrap').classList.add('d-none'); document.getElementById('nuevaMarcaNombre').value=''; feedback.textContent='Marca guardada.'; document.getElementById('marca').dispatchEvent(new Event('change')); } catch(e){feedback.textContent=e.message;} finally{this.disabled=false;}
});

document.getElementById('modelo').addEventListener('change',function(){document.getElementById('nuevoModeloWrap').classList.toggle('d-none',this.value!=='__otro_modelo__');});
document.getElementById('guardarNuevoModelo')?.addEventListener('click',async function(){const nombre=document.getElementById('nuevoModeloNombre').value.trim(),marca=document.getElementById('marca').value,tipo=document.getElementById('nuevoModeloTipo').value,fb=document.getElementById('modeloFeedback');if(!nombre||!marca||marca==='__otra__'||!tipo){fb.textContent='Selecciona una marca y tipo, y escribe el nombre del modelo.';return;}this.disabled=true;try{const r=await fetch('{{ route('modelos.quick-store') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({Nombre:nombre,FkId_Marca:marca,FkId_Tipo:tipo})});const d=await r.json();if(!r.ok)throw new Error(d.message||Object.values(d.errors||{}).flat().join(' ')||'No se pudo guardar.');const opt=new Option(d.Nombre,d.Id_Modelo,true,true);document.getElementById('modelo').add(opt);document.getElementById('modelo').value=d.Id_Modelo;document.getElementById('nuevoModeloWrap').classList.add('d-none');fb.textContent='Modelo guardado y seleccionado.';}catch(e){fb.textContent=e.message;}finally{this.disabled=false;}});
document.getElementById('toggleFacturaRapida')?.addEventListener('click',()=>document.getElementById('facturaRapida').classList.toggle('d-none'));
document.getElementById('guardarFacturaRapida')?.addEventListener('click',async function(){const fb=document.getElementById('facturaFeedback');const data={Folio:document.getElementById('facturaFolio').value.trim(),Fecha:document.getElementById('facturaFecha').value,Proveedor:document.getElementById('facturaProveedor').value.trim(),Monto:document.getElementById('facturaMonto').value,Descripcion:document.getElementById('facturaDescripcion').value.trim()};if(!data.Folio||!data.Fecha||!data.Proveedor||data.Monto===''){fb.textContent='Completa folio, fecha, proveedor y monto.';return;}this.disabled=true;try{const r=await fetch('{{ route('facturas.quick-store') }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify(data)});const d=await r.json();if(!r.ok)throw new Error(d.message||Object.values(d.errors||{}).flat().join(' ')||'No se pudo guardar.');const sel=document.getElementById('facturaSelect');sel.add(new Option(d.Folio+' — '+d.Proveedor,d.Id_Factura,true,true));sel.value=d.Id_Factura;fb.textContent='Factura guardada y seleccionada.';document.getElementById('facturaRapida').classList.add('d-none');}catch(e){fb.textContent=e.message;}finally{this.disabled=false;}});
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