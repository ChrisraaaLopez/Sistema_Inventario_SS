<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RevisionDemoSeeder extends Seeder {
 public function run(): void {
   if (DB::table('periodos')->where('Nombre','Revisión demostración 2026')->exists()) return;
   DB::table('periodos')->insert(['Id_Periodo'=>1,'Nombre'=>'Revisión demostración 2026','Fecha_Inicio'=>'2026-10-01','Estado'=>'Abierto','status'=>'activo','Estatus'=>'Activo']);
   $rows=[]; foreach(range(1,17) as $id) $rows[]=['Id_Articulo'=>$id,'Codigo_Barras'=>'INV-'.str_pad((string)$id,5,'0',STR_PAD_LEFT)];
   foreach($rows as $row) DB::table('articulos')->where('Id_Articulo',$row['Id_Articulo'])->update(['Codigo_Barras'=>$row['Codigo_Barras']]);
 }
}
