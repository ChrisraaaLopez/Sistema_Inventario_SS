<?php
namespace App\Http\Controllers;
use App\Models\Articulo; use App\Models\Empleado; use App\Models\Periodo; use App\Models\Resguardo; use App\Models\ResguardoArticulo; use App\Models\Ubicacion; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Auth;
class RevisionController extends Controller {
 public function periodos(){ $periodos=Periodo::withCount('resguardos')->orderByDesc('Id_Periodo')->paginate(15); foreach($periodos as $p){ $p->avance=$p->Estado==='Abierto' ? $this->avancePeriodo($p)['resumen'] : null; } return view('periodos.index',compact('periodos')); }
 public function crearPeriodo(){ return view('periodos.create'); }
 public function guardarPeriodo(Request $r){
    $d=$r->validate([
        'Nombre'=>'required|string|max:50|unique:periodos,Nombre',
        'Fecha_Inicio'=>'required|date'
    ],[
        'Nombre.unique' => 'Ya existe un periodo con ese nombre, elige otro.'
    ]);
    Periodo::create($d+['Estado'=>'Abierto','status'=>'activo','Estatus'=>'Activo']);
    return redirect()->route('revision.periodos')->with('ok','Periodo creado correctamente.');
}

public function seleccionar($periodo){
    $periodo=Periodo::findOrFail($periodo);
    abort_if($periodo->Estado==='Cerrado',403,'El periodo está cerrado.');

    $avance=$this->avancePeriodo($periodo);
    $resguardos=Resguardo::where('FkId_Periodo',$periodo->Id_Periodo)->with(['ubicacion','empleado'])->get();

    return view('revision.select',[
        'periodo'=>$periodo,
        'ubicaciones'=>$avance['ubicaciones'],      // solo ubicaciones con artículos activos
        'empleadosPorUbicacion'=>$avance['empleadosPorUbicacion'],
        'resumen'=>$avance['resumen'],
        'resguardos'=>$resguardos,
    ]);
}

/** Condición de "artículo activo" usada en todo el módulo de revisión. */
private function soloActivos($q){
    return $q->where(function($q){$q->where('status','activo')->orWhere(fn($n)=>$n->whereNull('status')->where('Estatus','Activo'));});
}

/**
 * Calcula el avance de un periodo a nivel artículo.
 * Un artículo cuenta como verificado si en ESTE periodo existe un resguardo_articulo con Revisado=true
 * (así una reasignación durante la revisión no deja pendientes fantasma).
 */
private function avancePeriodo(Periodo $periodo): array {
    $articulos=$this->soloActivos(Articulo::query())
        ->whereHas('ubicacion',fn($q)=>$this->soloActivos($q))
        ->whereHas('empleado',fn($q)=>$this->soloActivos($q))
        ->with(['empleado','ubicacion'])->get();

    $verificados=ResguardoArticulo::where('Revisado',true)
        ->whereHas('resguardo',fn($q)=>$q->where('FkId_Periodo',$periodo->Id_Periodo))
        ->pluck('FkId_Articulo')->unique()->flip();

    $resguardos=Resguardo::where('FkId_Periodo',$periodo->Id_Periodo)->get();

    $ubicaciones=collect(); $empleadosPorUbicacion=[]; $resumen=['total'=>0,'verificados'=>0,'pendientes'=>0,'abiertos'=>$resguardos->where('Cerrado',false)->count()];

    foreach($articulos->groupBy('FkId_Ubicacion') as $ubId=>$delaUbicacion){
        $u=$delaUbicacion->first()->ubicacion;
        $tot=$delaUbicacion->count();
        $ver=$delaUbicacion->filter(fn($a)=>$verificados->has($a->Id_Articulo))->count();
        $u->total=$tot; $u->verificados=$ver; $u->pendientes=$tot-$ver;
        $u->abierto=$resguardos->where('FkId_Ubicacion',$ubId)->where('Cerrado',false)->isNotEmpty();
        $ubicaciones->push($u);

        // Solo empleados que tienen artículos en ESTA ubicación
        $empleadosPorUbicacion[$ubId]=$delaUbicacion->groupBy('FkId_Empleado')->map(function($g,$eId)use($verificados){
            $e=$g->first()->empleado; $v=$g->filter(fn($a)=>$verificados->has($a->Id_Articulo))->count();
            return ['id'=>(int)$eId,'nombre'=>trim($e->Nombre.' '.$e->Apellido_Paterno),'trabajador'=>$e->N_Trabajador,'total'=>$g->count(),'verificados'=>$v,'pendientes'=>$g->count()-$v];
        })->sortBy('nombre')->values()->all();

        $resumen['total']+=$tot; $resumen['verificados']+=$ver; $resumen['pendientes']+=$tot-$ver;
    }
    return ['ubicaciones'=>$ubicaciones->sortBy([['Edificio','asc'],['Nombre','asc']])->values(),'empleadosPorUbicacion'=>$empleadosPorUbicacion,'resumen'=>$resumen];
}

/** Artículos de un resguardo que todavía no se verifican en el periodo (ignora los que ya no están en esa ubicación o se dieron de baja). */
private function pendientesDeResguardo(Resguardo $resguardo){
    $verificados=ResguardoArticulo::where('Revisado',true)->whereHas('resguardo',fn($q)=>$q->where('FkId_Periodo',$resguardo->FkId_Periodo))->pluck('FkId_Articulo');
    return $resguardo->articulos()->where('Revisado',false)->whereNotIn('FkId_Articulo',$verificados)
        ->whereHas('articulo',fn($q)=>$this->soloActivos($q)->where('FkId_Ubicacion',$resguardo->FkId_Ubicacion))->count();
}
public function iniciar(Request $r,$periodo){ $periodo=Periodo::findOrFail($periodo); abort_if($periodo->Estado==='Cerrado',403,'El periodo está cerrado.'); $d=$r->validate(['FkId_Ubicacion'=>'required|exists:ubicaciones,Id_Ubicacion','FkId_Empleado'=>'required|exists:empleados,Id_Empleado']); $existing=Resguardo::where('FkId_Periodo',$periodo->Id_Periodo)->where('FkId_Ubicacion',$d['FkId_Ubicacion'])->where('FkId_Empleado',$d['FkId_Empleado'])->first(); if($existing){ if(!$existing->Cerrado){ return redirect()->route('revision.scan',$existing)->with('ok','Ya existe un resguardo abierto para esta ubicación y empleado.'); } return back()->withErrors(['FkId_Empleado'=>'Ya existe un resguardo cerrado para este periodo, ubicación y empleado.']); }
 $articulos=Articulo::where('FkId_Ubicacion',$d['FkId_Ubicacion'])->where('FkId_Empleado',$d['FkId_Empleado'])->where(function($q){$q->where('status','activo')->orWhere(fn($n)=>$n->whereNull('status')->where('Estatus','Activo'));})->get(); if($articulos->isEmpty()){ return back()->withErrors(['FkId_Empleado'=>'No hay artículos activos asignados a este empleado en la ubicación elegida.']); }
 $res=Resguardo::create($d+['FkId_Periodo'=>$periodo->Id_Periodo,'Fecha_Firma'=>now()->toDateString()]); foreach($articulos as $a){ ResguardoArticulo::updateOrCreate(['FkId_Resguardo'=>$res->Id_Resguardo,'FkId_Articulo'=>$a->Id_Articulo],['FkId_Ubicacion'=>$a->FkId_Ubicacion,'Revisado'=>false,'Fecha_Revision'=>null,'Comentarios'=>null]); } return redirect()->route('revision.scan',$res); }
 public function scan(Resguardo $resguardo){ $resguardo->load(['periodo','empleado','ubicacion']); abort_if($resguardo->Cerrado || $resguardo->periodo->Estado==='Cerrado',403,'La revisión está cerrada.'); $items=$resguardo->articulos()->with(['articulo.modelo','articulo.marca','articulo.ubicacion'])->orderBy('Revisado')->get(); return view('revision.scan',compact('resguardo','items')); }
 public function buscar(Request $r,Resguardo $resguardo){ $r->validate(['codigo'=>'required|string|max:120']); if($resguardo->Cerrado || $resguardo->periodo?->Estado==='Cerrado'){ return response()->json(['ok'=>false,'type'=>'closed','message'=>'La revisión está cerrada.'],403); } $codigo=trim($r->codigo); $a=Articulo::with(['modelo','marca','ubicacion','empleado'])->where(fn($q)=>$q->where('Codigo_Barras',$codigo)->orWhere('N_Serie',$codigo))->where(function($q){ $q->where('status','activo')->orWhere(fn($n)=>$n->whereNull('status')->where('Estatus','Activo')); })->first(); if(!$a){ return response()->json(['ok'=>false,'type'=>'not_found','message'=>'Artículo no encontrado.'],404); } $expected=ResguardoArticulo::where('FkId_Resguardo',$resguardo->Id_Resguardo)->where('FkId_Articulo',$a->Id_Articulo)->first(); if(!$expected){ $assigned=$a->empleado?->Nombre.' '.$a->empleado?->Apellido_Paterno; $employeeName=$assigned ? trim($assigned) : 'sin empleado asignado'; return response()->json(['ok'=>false,'type'=>'not_expected','article'=>$this->articleJson($a),'actual_location'=>$a->ubicacion?->Nombre,'expected_location'=>$resguardo->ubicacion?->Nombre,'employee'=>$employeeName,'assigned_employee'=>$employeeName,'message'=>'El artículo no pertenece a la carga esperada de esta ubicación.']); } if($expected->Revisado){ return response()->json(['ok'=>true,'type'=>'already_reviewed','article'=>$this->articleJson($a),'message'=>'Este artículo ya había sido revisado.']); } if((int)$a->FkId_Ubicacion !== (int)$resguardo->FkId_Ubicacion){ return response()->json(['ok'=>false,'type'=>'wrong_location','article'=>$this->articleJson($a),'actual_location'=>$a->ubicacion?->Nombre,'expected_location'=>$resguardo->ubicacion?->Nombre,'employee'=>$a->empleado?->Nombre.' '.$a->empleado?->Apellido_Paterno,'message'=>'Artículo encontrado, pero está registrado en otra ubicación.']); } $expected->update(['Revisado'=>true,'Fecha_Revision'=>now()]); return response()->json(['ok'=>true,'type'=>'reviewed','article'=>$this->articleJson($a),'message'=>'Artículo revisado correctamente.']); }
 private function articleJson(Articulo $a){ return ['id'=>$a->Id_Articulo,'codigo'=>$a->Codigo_Barras,'serie'=>$a->N_Serie,'descripcion'=>$a->Descripcion,'modelo'=>$a->modelo?->Nombre,'marca'=>$a->marca?->Nombre,'ubicacion'=>$a->ubicacion?->Nombre,'imagen'=>($a->modelo?->URL_Imagen ?: $a->URL_Imagen) ? \Illuminate\Support\Facades\Storage::disk('public')->url($a->modelo?->URL_Imagen ?: $a->URL_Imagen) : null,'employee'=>$a->empleado?->Nombre.' '.$a->empleado?->Apellido_Paterno]; }
 public function reasignar(Request $r,Resguardo $resguardo,Articulo $articulo){ $r->validate(['confirmacion'=>'accepted']); abort_if($resguardo->Cerrado,403,'La revisión está cerrada.'); abort_if($resguardo->periodo?->Estado==='Cerrado',403,'El periodo está cerrado.'); $created=false; DB::transaction(function() use($resguardo,$articulo,&$created){ $previousLocation=$articulo->FkId_Ubicacion; $articulo->update(['FkId_Ubicacion'=>$resguardo->FkId_Ubicacion]); $item=ResguardoArticulo::updateOrCreate(['FkId_Resguardo'=>$resguardo->Id_Resguardo,'FkId_Articulo'=>$articulo->Id_Articulo],['FkId_Ubicacion'=>$resguardo->FkId_Ubicacion,'Revisado'=>true,'Fecha_Revision'=>now(),'Comentarios'=>'Se reasignó desde la ubicación anterior durante la revisión.']); $created = $item->wasRecentlyCreated; $articulo->refresh(); }); return response()->json(['ok'=>true,'created'=>$created,'message'=>'Ubicación reasignada y artículo marcado como revisado.']); }
 public function cierre(Resguardo $resguardo){ $resguardo->load(['periodo','empleado','ubicacion']); $items=$resguardo->articulos()->with(['articulo.modelo','articulo.ubicacion'])->get(); return view('revision.close',compact('resguardo','items')); }
 public function cerrarResguardo(Request $r,Resguardo $resguardo){ $r->validate(['Observaciones'=>'nullable|string|max:500']); abort_if($resguardo->Cerrado,403,'El resguardo ya está cerrado.'); abort_if($resguardo->periodo?->Estado==='Cerrado',403,'El periodo está cerrado.'); $faltan=$this->pendientesDeResguardo($resguardo); if($faltan>0) return back()->withErrors(['Observaciones'=>"No se puede cerrar: faltan {$faltan} artículo(s) por verificar."]); $resguardo->update(['Cerrado'=>true,'Fecha_Cierre'=>now(),'Observaciones'=>$r->Observaciones]); return redirect()->route('resguardo.print',$resguardo)->with('ok','Resguardo cerrado correctamente.'); }
 public function imprimir(Resguardo $resguardo){ $resguardo->load(['periodo','empleado.area','empleado.puesto','ubicacion','articulos.articulo.modelo','articulos.articulo.ubicacion']); return view('resguardos.print',compact('resguardo')); }
 public function cerrarPeriodo(Request $r,Periodo $periodo){ abort_unless(Auth::user()?->rol?->Nombre === 'Administrador',403,'Solo el Administrador puede cerrar periodos.'); abort_if($periodo->Estado==='Cerrado',403); $pendientes=$periodo->resguardos()->where('Cerrado',false)->count(); if($pendientes>0) return back()->withErrors(['clave'=>'No se puede cerrar: todavía hay resguardos abiertos.']); $av=$this->avancePeriodo($periodo); if($av['resumen']['pendientes']>0){ $n=$av['ubicaciones']->where('pendientes','>',0)->count(); return back()->withErrors(['clave'=>"No se puede cerrar: faltan {$av['resumen']['pendientes']} artículo(s) por verificar en {$n} ubicación(es)."]); } $periodo->update(['Estado'=>'Cerrado','Fecha_Cierre'=>now()->toDateString()]); return redirect()->route('revision.periodos')->with('ok','Periodo cerrado y bloqueado.'); }
 public function reabrirPeriodo(Periodo $periodo){ abort_unless(Auth::user()?->rol?->Nombre === 'Administrador',403,'Solo el Administrador puede reabrir periodos.'); $periodo->update(['Estado'=>'Abierto','Fecha_Cierre'=>null]); return redirect()->route('revision.periodos')->with('ok','Periodo reabierto correctamente.'); }
 public function reabrirResguardo(Resguardo $resguardo){ abort_unless(Auth::user()?->rol?->Nombre === 'Administrador',403,'Solo el Administrador puede reabrir resguardos.'); abort_if(!$resguardo->Cerrado,403,'El resguardo no está cerrado.'); $resguardo->update(['Cerrado'=>false,'Fecha_Cierre'=>null,'Observaciones'=>null]); return redirect()->route('revision.history')->with('ok','Resguardo reabierto correctamente.'); }
 public function historico(Request $r){ $resguardos=Resguardo::with(['periodo','empleado','ubicacion'])->when($r->ubicacion,fn($q)=>$q->where('FkId_Ubicacion',$r->ubicacion))->when($r->empleado,fn($q)=>$q->where('FkId_Empleado',$r->empleado))->when($r->area,fn($q)=>$q->whereHas('empleado',fn($q)=>$q->where('FkId_Area',$r->area)))->orderByDesc('Id_Resguardo')->paginate(20)->withQueryString(); $ubicaciones=Ubicacion::orderBy('Nombre')->get(); $empleados=Empleado::orderBy('Nombre')->get(); $areas=\App\Models\Area::orderBy('Nombre')->get(); return view('revision.history',compact('resguardos','ubicaciones','empleados','areas')); }
}
