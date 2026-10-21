<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsuariosRolesSeeder extends Seeder {
    public function run(): void {
        foreach (['Administrador','Revisor','Consulta'] as $nombre) {
            DB::table('roles')->updateOrInsert(['Nombre' => $nombre], ['status' => 'activo', 'Estatus' => 'Activo']);
        }

        $roles = DB::table('roles')->pluck('Id_Rol','Nombre');
        foreach ([['Osvaldo','Administrador'],['Emiliano','Revisor'],['Jhonatan','Consulta']] as [$usuario,$rol]) {
            DB::table('usuarios')->updateOrInsert(['Nombre_Usuario'=>$usuario], [
                'Password_Hash'=>Hash::make('Os1234'), 'FkId_Rol'=>$roles[$rol], 'Estatus'=>'Activo', 'status'=>'activo',
                'Fecha_Creacion'=>now(), 'Ultimo_Acceso'=>null,
            ]);
            DB::table('usuarios')->where('Nombre_Usuario',$usuario)->update(['Estatus'=>'Activo','status'=>'activo','FkId_Rol'=>$roles[$rol]]);
        }
    }
}
