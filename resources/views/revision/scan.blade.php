@extends('layouts.app')
@section('content')
<div class="page">
  <div class="page-head">
    <div>
      <span class="eyebrow">REVISIÓN ACTIVA</span>
      <h1>{{$resguardo->ubicacion->Nombre}}</h1>
      <p>{{$resguardo->periodo->Nombre}} · Responsable: {{$resguardo->empleado->Nombre}} {{$resguardo->empleado->Apellido_Paterno}}</p>
    </div>
    <a class="btn" href="{{route('revision.close',$resguardo)}}">Ver cierre</a>
  </div>

  <div id="message" class="alert" style="display:none"></div>

  <div class="scan-grid">
    <div class="card scanner">
      <h2>Escanear artículo</h2>
      <div id="reader"></div>
      <p class="muted">Permite el acceso a la cámara. También puedes escribir el código o número de serie.</p>
      <form id="manualForm">
        <div class="input-row">
          <input id="codigo" placeholder="Código de barras / QR / serie">
          <button class="btn primary" type="submit">Buscar</button>
        </div>
      </form>
      <div id="preview" class="scan-result" style="display:none"></div>
    </div>

    <div class="card">
      <div class="section-head">
        <h2>Artículos esperados</h2>
        <span id="counter" class="badge green">0 / {{$items->count()}}</span>
      </div>
      <div class="expected-list">
        @foreach($items as $i)
          <div id="item-{{$i->FkId_Articulo}}" class="expected-item {{$i->Revisado ? 'reviewed' : ''}}">
            <div>
              <strong>{{$i->articulo->Descripcion}}</strong>
              <small>{{$i->articulo->Codigo_Barras ?: $i->articulo->N_Serie ?: 'Sin código'}}</small>
            </div>
            <span class="state">{{$i->Revisado ? '✓ Revisado' : 'Pendiente'}}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script>
const lookupUrl = '{{route('revision.scan.lookup',$resguardo)}}';
const token = '{{csrf_token()}}';
let total = {{$items->count()}};
let reviewed = {{$items->where('Revisado', true)->count()}};
const msg = document.getElementById('message');

function show(type, text) {
  msg.style.display = 'block';
  msg.className = 'alert ' + type;
  msg.textContent = text;
  clearTimeout(window.mt);
  window.mt = setTimeout(() => msg.style.display = 'none', 5000);
}

function update() {
  document.getElementById('counter').textContent = reviewed + ' / ' + total;
}

function render(a) {
  const p = document.getElementById('preview');
  p.style.display = 'block';
  p.replaceChildren();

  let imageNode;
  if (a.imagen) {
    imageNode = document.createElement('img');
    imageNode.src = a.imagen;
    imageNode.alt = 'Imagen de referencia de ' + (a.descripcion || 'artículo');
    imageNode.className = 'model-img';
    imageNode.loading = 'lazy';
    imageNode.onerror = () => {
      imageNode.replaceWith(Object.assign(document.createElement('div'), {
        className: 'model-img model-placeholder',
        textContent: 'Imagen no disponible'
      }));
    };
  } else {
    imageNode = Object.assign(document.createElement('div'), {
      className: 'model-img model-placeholder',
      textContent: a.modelo || 'MODELO'
    });
  }

  const info = document.createElement('div');
  const title = document.createElement('strong');
  title.textContent = a.descripcion || 'Artículo';
  const model = document.createElement('div');
  model.textContent = (a.marca || '') + ' ' + (a.modelo || '');
  const small = document.createElement('small');
  small.textContent = 'Código: ' + (a.codigo || '—') + ' · Serie: ' + (a.serie || '—');

  info.append(title, model, small);
  p.append(imageNode, info);
}

async function lookup(code) {
  if (!code) return;

  try {
    const r = await fetch(lookupUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ codigo: code })
    });

    const d = await r.json();
    if (d.article) render(d.article);

    if (d.type === 'reviewed') {
      reviewed = Math.min(reviewed + 1, total);
      const el = document.getElementById('item-' + d.article.id);
      if (el) {
        el.classList.add('reviewed');
        el.querySelector('.state').textContent = '✓ Revisado';
      }
      show('ok', d.message || 'Artículo revisado correctamente.');
      update();
      return;
    }

    if (d.type === 'already_reviewed') {
      show('warning', d.message);
      return;
    }

    if (d.type === 'wrong_location') {
      show('warning', d.message + ' Ubicación registrada: ' + d.actual_location + '.');
      const ok = confirm('El artículo está registrado en ' + d.actual_location + '. ¿Reasignarlo a ' + d.expected_location + ' y marcarlo como revisado?');
      if (ok) {
        const rr = await fetch('{{url('/revision/resguardos')}}/' + {{$resguardo->Id_Resguardo}} + '/reasignar/' + d.article.id, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ confirmacion: true })
        });
        const rd = await rr.json();
        if (rd.ok) {
          if (rd.created) {
            total += 1;
          }
          reviewed = Math.min(reviewed + 1, total);
          const item = document.getElementById('item-' + d.article.id);
          if (item) {
            item.classList.add('reviewed');
            item.querySelector('.state').textContent = '✓ Revisado';
          } else {
            const list = document.querySelector('.expected-list');
            const row = document.createElement('div');
            row.id = 'item-' + d.article.id;
            row.className = 'expected-item reviewed';
            row.innerHTML = '<div><strong>' + (d.article.descripcion || 'Artículo') + '</strong><small>' + (d.article.codigo || d.article.serie || 'Sin código') + '</small></div><span class="state">✓ Revisado</span>';
            list.appendChild(row);
          }
          show('ok', rd.message || 'Ubicación reasignada correctamente.');
          update();
        } else {
          show('danger', rd.message || 'No se pudo reasignar.');
        }
      }
      return;
    }

    if (d.type === 'not_expected') {
      const assigned = (d.assigned_employee || d.employee || 'sin empleado asignado');
      if ((d.actual_location || d.expected_location) && (d.actual_location === d.expected_location || !d.actual_location || !d.expected_location)) {
        show('warning', 'Este artículo está asignado a ' + assigned + ' y no pertenece a este resguardo');
      } else {
        show('warning', d.message + ' Ubicación registrada: ' + (d.actual_location || 'sin ubicación') + '.');
        const ok = confirm('El artículo está registrado en ' + (d.actual_location || 'otra ubicación') + '. ¿Reasignarlo a ' + d.expected_location + ' y marcarlo como revisado?');
        if (ok) {
          const rr = await fetch('{{url('/revision/resguardos')}}/' + {{$resguardo->Id_Resguardo}} + '/reasignar/' + d.article.id, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ confirmacion: true })
          });
          const rd = await rr.json();
          if (rd.ok) {
            if (rd.created) {
              total += 1;
            }
            reviewed = Math.min(reviewed + 1, total);
            const item = document.getElementById('item-' + d.article.id);
            if (item) {
              item.classList.add('reviewed');
              item.querySelector('.state').textContent = '✓ Revisado';
            } else {
              const list = document.querySelector('.expected-list');
              const row = document.createElement('div');
              row.id = 'item-' + d.article.id;
              row.className = 'expected-item reviewed';
              row.innerHTML = '<div><strong>' + (d.article.descripcion || 'Artículo') + '</strong><small>' + (d.article.codigo || d.article.serie || 'Sin código') + '</small></div><span class="state">✓ Revisado</span>';
              list.appendChild(row);
            }
            show('ok', rd.message || 'Ubicación reasignada correctamente.');
            update();
          } else {
            show('danger', rd.message || 'No se pudo reasignar.');
          }
        }
      }
      return;
    }

    if (d.type === 'not_found') {
      show('danger', d.message);
      return;
    }

    if (!r.ok) {
      show('danger', d.message || 'Error al consultar');
    }
  } catch (e) {
    show('danger', 'No fue posible consultar el artículo.');
  }
}

document.getElementById('manualForm').addEventListener('submit', (e) => {
  e.preventDefault();
  const input = document.getElementById('codigo');
  lookup(input.value.trim());
  input.value = '';
});

update();

if (window.Html5Qrcode) {
  const scanner = new Html5Qrcode('reader', {
    formatsToSupport: [
      Html5QrcodeSupportedFormats.QR_CODE,
      Html5QrcodeSupportedFormats.CODE_128,
      Html5QrcodeSupportedFormats.CODE_39,
      Html5QrcodeSupportedFormats.EAN_13,
      Html5QrcodeSupportedFormats.EAN_8,
      Html5QrcodeSupportedFormats.UPC_A,
      Html5QrcodeSupportedFormats.UPC_E,
      Html5QrcodeSupportedFormats.ITF
    ]
  });

  scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 280, height: 180 } }, (text) => {
    scanner.pause(true);
    lookup(text).finally(() => setTimeout(() => scanner.resume(), 1200));
  }, () => {}).catch(() => {
    show('warning', 'No se pudo abrir la cámara. Usa localhost o HTTPS y concede permiso.');
  });
} else {
  show('warning', 'No se cargó el lector de cámara; usa la captura manual.');
}
</script>
@endsection