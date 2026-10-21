<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtendedDemoSeeder extends Seeder
{
    public function run(): void
    {
        $catalogs = [
            'marcas' => [
                'id_key' => 'Id_Marca',
                'records' => [
                    ['Id_Marca' => 11, 'Nombre' => 'Acer', 'Estatus' => 'Activo', 'status' => 'activo'],
                    ['Id_Marca' => 12, 'Nombre' => 'Brother', 'Estatus' => 'Inactivo', 'status' => 'inactivo'],
                    ['Id_Marca' => 13, 'Nombre' => 'Xiaomi', 'Estatus' => 'Activo', 'status' => 'activo'],
                ],
            ],
            'tipos' => [
                'id_key' => 'Id_Tipo',
                'records' => [
                    ['Id_Tipo' => 11, 'Nombre' => 'Tablet', 'FkId_Categoria' => 2, 'Estatus' => 'Activo', 'status' => 'activo'],
                    ['Id_Tipo' => 12, 'Nombre' => 'Scanner', 'FkId_Categoria' => 2, 'Estatus' => 'Inactivo', 'status' => 'inactivo'],
                    ['Id_Tipo' => 13, 'Nombre' => 'Cámara de seguridad', 'FkId_Categoria' => 3, 'Estatus' => 'Activo', 'status' => 'activo'],
                ],
            ],
            'ubicaciones' => [
                'id_key' => 'Id_Ubicacion',
                'records' => [
                    ['Id_Ubicacion' => 11, 'Nombre' => 'Auditorio', 'Edificio' => 'A', 'Planta' => '3', 'Estatus' => 'Activo', 'status' => 'activo'],
                    ['Id_Ubicacion' => 12, 'Nombre' => 'Bodega de impresión', 'Edificio' => 'B', 'Planta' => '1', 'Estatus' => 'Inactivo', 'status' => 'inactivo'],
                ],
            ],
        ];

        foreach ($catalogs as $table => $config) {
            $idKey = $config['id_key'];
            foreach ($config['records'] as $record) {
                DB::table($table)->updateOrInsert([$idKey => $record[$idKey]], $record);
            }
        }

        $empleados = [
            ['Id_Empleado' => 11, 'N_Trabajador' => 'TSJ-011', 'Nombre' => 'René', 'Apellido_Paterno' => 'Pérez', 'Apellido_Materno' => 'Canto', 'FkId_Puesto' => 4, 'FkId_Area' => 2, 'Estatus' => 'Activo', 'status' => 'activo'],
            ['Id_Empleado' => 12, 'N_Trabajador' => 'TSJ-012', 'Nombre' => 'Elena', 'Apellido_Paterno' => 'Gómez', 'Apellido_Materno' => 'Serrano', 'FkId_Puesto' => 5, 'FkId_Area' => 5, 'Estatus' => 'Inactivo', 'status' => 'inactivo'],
        ];

        foreach ($empleados as $empleado) {
            DB::table('empleados')->updateOrInsert(['Id_Empleado' => $empleado['Id_Empleado']], $empleado);
        }

        $modelos = [
            ['Id_Modelo' => 13, 'Nombre' => 'Aspire 5', 'FkId_Marca' => 11, 'FkId_Tipo' => 11, 'URL_Imagen' => null, 'Estatus' => 'Activo', 'status' => 'activo'],
            ['Id_Modelo' => 14, 'Nombre' => 'DCP-L2540DW', 'FkId_Marca' => 12, 'FkId_Tipo' => 12, 'URL_Imagen' => null, 'Estatus' => 'Inactivo', 'status' => 'inactivo'],
        ];

        foreach ($modelos as $modelo) {
            DB::table('modelos')->updateOrInsert(['Id_Modelo' => $modelo['Id_Modelo']], $modelo);
        }

        $facturas = [
            ['Id_Factura' => 7, 'Folio' => 'F-2026-007', 'Fecha' => '2026-07-10', 'Proveedor' => 'TecnoPlus', 'Monto' => 15890.00, 'Descripcion' => 'Equipo adicional para auditorios', 'URL_Factura' => 'https://example.com/facturas/f-2026-007.pdf', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Factura' => 8, 'Folio' => 'F-2026-008', 'Fecha' => '2026-08-04', 'Proveedor' => 'Importaciones Móvil', 'Monto' => 9760.00, 'Descripcion' => 'Material de apoyo escolar', 'URL_Factura' => 'https://example.com/facturas/f-2026-008.pdf', 'status' => 'inactivo', 'Estatus' => 'Inactivo'],
        ];

        foreach ($facturas as $factura) {
            DB::table('facturas')->updateOrInsert(['Id_Factura' => $factura['Id_Factura']], $factura);
        }

        $articulos = [
            ['Id_Articulo' => 16, 'Descripcion' => 'Tablet Acer Aspire Switch', 'FkId_Marca' => 11, 'FkId_Modelo' => 13, 'N_Serie' => 'ACR-TAB-016', 'Color' => 'Gris', 'FkId_Categoria' => 2, 'FkId_Tipo' => 11, 'FkId_Ubicacion' => 11, 'FkId_Empleado' => 11, 'FkId_Factura' => 7, 'Notas' => 'Para presentación de eventos', 'Comentarios' => 'Uso ocasional', 'URL_Imagen' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'status' => 'activo', 'Estatus' => 'Activo', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 17, 'Descripcion' => 'Impresora Brother inactiva', 'FkId_Marca' => 12, 'FkId_Modelo' => 14, 'N_Serie' => 'BR-PR-017', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 12, 'FkId_Ubicacion' => 12, 'FkId_Empleado' => 12, 'FkId_Factura' => 8, 'Notas' => 'Equipo dado de baja temporal', 'Comentarios' => 'No visibles en inventario activo', 'URL_Imagen' => null, 'Estado' => 'Reparacion', 'Tipo_Articulo' => 'Capitalizable', 'status' => 'inactivo', 'Estatus' => 'Inactivo', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
        ];

        foreach ($articulos as $articulo) {
            DB::table('articulos')->updateOrInsert(['Id_Articulo' => $articulo['Id_Articulo']], $articulo);
        }
    }
}
