<?php
namespace App\Http\Controllers;
use App\Models\Vehiculo; use Illuminate\Http\Request; use Illuminate\Validation\Rule;
class VehiculoController extends Controller {
 public function index(Request $r){$vehiculos=Vehiculo::when($r->tipo,fn($q)=>$q->where('Tipo_Vehiculo',$r->tipo))->when($r->buscar,fn($q)=>$q->where(fn($s)=>$s->where('Nombre','like','%'.$r->buscar.'%')->orWhere('Placas','like','%'.$r->buscar.'%')->orWhere('Numero_Serie','like','%'.$r->buscar.'%')))->orderBy('Nombre')->paginate(15)->withQueryString();return view('vehiculos.index',compact('vehiculos'));}
 public function create(){return view('vehiculos.form',['vehiculo'=>new Vehiculo,'editando'=>false]);}
 public function store(Request $r){$d=$this->validar($r);Vehiculo::create($d);return redirect()->route('vehiculos.index')->with('ok','Vehículo registrado correctamente.');}
 public function edit(Vehiculo $vehiculo){return view('vehiculos.form',compact('vehiculo')+['editando'=>true]);}
 public function update(Request $r,Vehiculo $vehiculo){$vehiculo->update($this->validar($r,$vehiculo));return redirect()->route('vehiculos.index')->with('ok','Vehículo actualizado.');}
 public function destroy(Vehiculo $vehiculo){$vehiculo->update(['Estatus'=>'Inactivo']);return back()->with('ok','Vehículo desactivado; se conservó su registro.');}
 private function validar(Request $r,?Vehiculo $v=null):array{return $r->validate(['Tipo_Vehiculo'=>'required|in:Autobús (Toro),Camioneta de transporte,Otro','Nombre'=>'required|string|max:100','Marca'=>'nullable|string|max:80','Modelo'=>'nullable|string|max:80','Anio'=>'nullable|integer|min:1980|max:'.(date('Y')+1),'Placas'=>['nullable','string','max:20',Rule::unique('vehiculos','Placas')->ignore($v?->Id_Vehiculo,'Id_Vehiculo')],'Numero_Serie'=>'nullable|string|max:100','Capacidad'=>'nullable|integer|min:1|max:1000','Estatus'=>'required|in:Activo,Inactivo','Observaciones'=>'nullable|string|max:1000']);}
}
