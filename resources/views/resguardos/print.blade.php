<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Resguardo {{$resguardo->Id_Resguardo}}</title><style>
*{box-sizing:border-box}
body{font-family:Arial,sans-serif;margin:32px;color:#172033;background:#eef1f5}
.print-bar{position:sticky;top:0;display:flex;justify-content:flex-end;padding:12px 0;margin-bottom:16px}
.btn-print{display:inline-flex;align-items:center;gap:8px;background:#172033;color:#fff;border:none;border-radius:8px;padding:12px 20px;font-size:14px;font-weight:600;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.15);transition:background .15s ease,transform .1s ease}
.btn-print:hover{background:#2a3a5c}
.btn-print:active{transform:scale(.97)}
.btn-print svg{width:16px;height:16px}
.sheet{background:#fff;max-width:900px;margin:0 auto;padding:32px;border-radius:10px;box-shadow:0 1px 4px rgba(0,0,0,.08)}
header{display:flex;justify-content:space-between;border-bottom:3px solid #172033;padding-bottom:16px}
h1{margin:0;font-size:22px}
.meta{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:20px 0}
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #9aa3b2;padding:8px;font-size:12px}
th{background:#eef1f5}
.ok{color:#16784b;font-weight:600}
.bad{color:#b42318;font-weight:600}
.sign{display:grid;grid-template-columns:1fr 1fr;gap:80px;margin-top:70px}
.line{border-top:1px solid #333;text-align:center;padding-top:8px}
@media print{
  body{margin:0;background:#fff}
  .print-bar{display:none}
  .sheet{box-shadow:none;border-radius:0;max-width:100%;margin:15mm}
}
</style></head><body>

<div class="print-bar no-print">
  <button class="btn-print" onclick="window.print()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
    Imprimir / Guardar PDF
  </button>
</div>

<div class="sheet">
<header><div><h1>RESGUARDO DE REVISIÓN DE INVENTARIO</h1><div>Sistema de Control de Inventario</div></div><strong>No. {{$resguardo->Id_Resguardo}}</strong></header><div class="meta"><div><b>Periodo:</b> {{$resguardo->periodo->Nombre}}</div><div><b>Estado:</b> {{$resguardo->Cerrado?'CERRADO':'ABIERTO'}}</div><div><b>Ubicación:</b> {{$resguardo->ubicacion?->Nombre}} / Edificio {{$resguardo->ubicacion?->Edificio}} / Planta {{$resguardo->ubicacion?->Planta}}</div><div><b>Empleado responsable:</b> {{$resguardo->empleado->Nombre}} {{$resguardo->empleado->Apellido_Paterno}} {{$resguardo->empleado->Apellido_Materno}}</div><div><b>Fecha:</b> {{$resguardo->Fecha_Cierre?->format('d/m/Y H:i') ?: now()->format('d/m/Y')}}</div></div><table><thead><tr><th>#</th><th>Artículo</th><th>Código</th><th>Ubicación</th><th>Resultado</th></tr></thead><tbody>@foreach($resguardo->articulos as $n=>$i)<tr><td>{{$n+1}}</td><td>{{$i->articulo->Descripcion}}</td><td>{{$i->articulo->Codigo_Barras ?: $i->articulo->N_Serie ?: '—'}}</td><td>{{$i->articulo->ubicacion?->Nombre ?: $i->ubicacion?->Nombre}}</td><td class="{{$i->Revisado?'ok':'bad'}}">{{$i->Revisado?'REVISADO':'NO ESCANEADO'}}</td></tr>@endforeach</tbody></table><p><b>Observaciones:</b> {{$resguardo->Observaciones ?: 'Sin observaciones.'}}</p><div class="sign"><div class="line">Empleado / responsable</div><div class="line">Revisor</div></div>
</div>

</body></html>