<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoModulo1Seeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('articulos')->exists() || app()->environment('production') || env('APP_ENV') !== 'local') {
            $this->command?->warn('DemoModulo1Seeder no se ejecuta porque ya hay datos o no está en entorno local.');
            return;
        }

        $areas = [
            ['Id_Area' => 1, 'Nombre' => 'Dirección', 'Estatus' => 'Activo'],
            ['Id_Area' => 2, 'Nombre' => 'Administración', 'Estatus' => 'Activo'],
            ['Id_Area' => 3, 'Nombre' => 'Finanzas', 'Estatus' => 'Activo'],
            ['Id_Area' => 4, 'Nombre' => 'Academia', 'Estatus' => 'Activo'],
            ['Id_Area' => 5, 'Nombre' => 'Tecnologías de la Información', 'Estatus' => 'Activo'],
            ['Id_Area' => 6, 'Nombre' => 'Servicios Escolares', 'Estatus' => 'Activo'],
        ];
        DB::table('areas')->insert($areas);

        $puestos = [
            ['Id_Puesto' => 1, 'Nombre' => 'Director General', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 2, 'Nombre' => 'Administrador', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 3, 'Nombre' => 'Coordinador', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 4, 'Nombre' => 'Analista', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 5, 'Nombre' => 'Técnico de Soporte', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 6, 'Nombre' => 'Jefe de Laboratorio', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 7, 'Nombre' => 'Secretaria', 'Estatus' => 'Activo'],
            ['Id_Puesto' => 8, 'Nombre' => 'Docente', 'Estatus' => 'Activo'],
        ];
        DB::table('puestos')->insert($puestos);

        $categorias = [
            ['Id_Categoria' => 1, 'Nombre' => 'Mobiliario y equipo de administración', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 2, 'Nombre' => 'Equipo de cómputo y tecnologías de la información', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 3, 'Nombre' => 'Mobiliario y equipo educativo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 4, 'Nombre' => 'Equipo e instrumental médico y de laboratorio', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 5, 'Nombre' => 'Maquinaria y herramientas', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 6, 'Nombre' => 'Licencias informáticas', 'Estatus' => 'Activo'],
        ];
        DB::table('categorias')->insert($categorias);

        $marcas = [
            ['Id_Marca' => 1, 'Nombre' => 'Dell', 'Estatus' => 'Activo'],
            ['Id_Marca' => 2, 'Nombre' => 'HP', 'Estatus' => 'Activo'],
            ['Id_Marca' => 3, 'Nombre' => 'Lenovo', 'Estatus' => 'Activo'],
            ['Id_Marca' => 4, 'Nombre' => 'Epson', 'Estatus' => 'Activo'],
            ['Id_Marca' => 5, 'Nombre' => 'Samsung', 'Estatus' => 'Activo'],
            ['Id_Marca' => 6, 'Nombre' => 'Logitech', 'Estatus' => 'Activo'],
            ['Id_Marca' => 7, 'Nombre' => 'Canon', 'Estatus' => 'Activo'],
            ['Id_Marca' => 8, 'Nombre' => 'Bosch', 'Estatus' => 'Activo'],
            ['Id_Marca' => 9, 'Nombre' => 'Microsoft', 'Estatus' => 'Activo'],
            ['Id_Marca' => 10, 'Nombre' => 'Cisco', 'Estatus' => 'Activo'],
        ];
        DB::table('marcas')->insert($marcas);

        $tipos = [
            ['Id_Tipo' => 1, 'Nombre' => 'Laptop', 'FkId_Categoria' => 2, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 2, 'Nombre' => 'Desktop', 'FkId_Categoria' => 2, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 3, 'Nombre' => 'Monitor', 'FkId_Categoria' => 2, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 4, 'Nombre' => 'Impresora', 'FkId_Categoria' => 2, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 5, 'Nombre' => 'Escritorio', 'FkId_Categoria' => 1, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 6, 'Nombre' => 'Silla ejecutiva', 'FkId_Categoria' => 1, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 7, 'Nombre' => 'Equipo audiovisual', 'FkId_Categoria' => 3, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 8, 'Nombre' => 'Equipo de laboratorio', 'FkId_Categoria' => 4, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 9, 'Nombre' => 'Herramienta eléctrica', 'FkId_Categoria' => 5, 'Estatus' => 'Activo'],
            ['Id_Tipo' => 10, 'Nombre' => 'Licencia', 'FkId_Categoria' => 6, 'Estatus' => 'Activo'],
        ];
        DB::table('tipos')->insert($tipos);

        $ubicaciones = [
            ['Id_Ubicacion' => 1, 'Nombre' => 'Sala de juntas', 'Edificio' => 'A', 'Planta' => '1', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 2, 'Nombre' => 'Oficina administrativa', 'Edificio' => 'A', 'Planta' => '2', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 3, 'Nombre' => 'Laboratorio de cómputo', 'Edificio' => 'B', 'Planta' => '1', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 4, 'Nombre' => 'Laboratorio de química', 'Edificio' => 'B', 'Planta' => '2', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 5, 'Nombre' => 'Aula 101', 'Edificio' => 'C', 'Planta' => '1', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 6, 'Nombre' => 'Aula 203', 'Edificio' => 'C', 'Planta' => '2', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 7, 'Nombre' => 'Taller mecánico', 'Edificio' => 'D', 'Planta' => '1', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 8, 'Nombre' => 'Bodega de apoyo', 'Edificio' => 'D', 'Planta' => '2', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 9, 'Nombre' => 'Recepción', 'Edificio' => 'E', 'Planta' => '1', 'Estatus' => 'Activo'],
            ['Id_Ubicacion' => 10, 'Nombre' => 'Área de soporte', 'Edificio' => 'E', 'Planta' => '2', 'Estatus' => 'Activo'],
        ];
        DB::table('ubicaciones')->insert($ubicaciones);

        $empleados = [
            ['Id_Empleado' => 1, 'N_Trabajador' => 'TSJ-001', 'Nombre' => 'María', 'Apellido_Paterno' => 'López', 'Apellido_Materno' => 'Salazar', 'FkId_Puesto' => 1, 'FkId_Area' => 1, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 2, 'N_Trabajador' => 'TSJ-002', 'Nombre' => 'Carlos', 'Apellido_Paterno' => 'Ramírez', 'Apellido_Materno' => 'García', 'FkId_Puesto' => 2, 'FkId_Area' => 2, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 3, 'N_Trabajador' => 'TSJ-003', 'Nombre' => 'Ana', 'Apellido_Paterno' => 'Martínez', 'Apellido_Materno' => 'Díaz', 'FkId_Puesto' => 7, 'FkId_Area' => 2, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 4, 'N_Trabajador' => 'TSJ-004', 'Nombre' => 'Luis', 'Apellido_Paterno' => 'Torres', 'Apellido_Materno' => 'Vega', 'FkId_Puesto' => 3, 'FkId_Area' => 3, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 5, 'N_Trabajador' => 'TSJ-005', 'Nombre' => 'Patricia', 'Apellido_Paterno' => 'Sánchez', 'Apellido_Materno' => 'Mendoza', 'FkId_Puesto' => 8, 'FkId_Area' => 4, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 6, 'N_Trabajador' => 'TSJ-006', 'Nombre' => 'Diego', 'Apellido_Paterno' => 'Hernández', 'Apellido_Materno' => 'Pérez', 'FkId_Puesto' => 6, 'FkId_Area' => 4, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 7, 'N_Trabajador' => 'TSJ-007', 'Nombre' => 'Rosa', 'Apellido_Paterno' => 'Flores', 'Apellido_Materno' => 'Cárdenas', 'FkId_Puesto' => 4, 'FkId_Area' => 5, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 8, 'N_Trabajador' => 'TSJ-008', 'Nombre' => 'Fernando', 'Apellido_Paterno' => 'Castro', 'Apellido_Materno' => 'Ruiz', 'FkId_Puesto' => 5, 'FkId_Area' => 5, 'Estatus' => 'Activo'],
            ['Id_Empleado' => 9, 'N_Trabajador' => 'TSJ-009', 'Nombre' => 'Javier', 'Apellido_Paterno' => 'Guerrero', 'Apellido_Materno' => 'Nava', 'FkId_Puesto' => 4, 'FkId_Area' => 6, 'Estatus' => 'Inactivo'],
            ['Id_Empleado' => 10, 'N_Trabajador' => 'TSJ-010', 'Nombre' => 'Sofía', 'Apellido_Paterno' => 'Méndez', 'Apellido_Materno' => 'Beltrán', 'FkId_Puesto' => 8, 'FkId_Area' => 6, 'Estatus' => 'Activo'],
        ];
        DB::table('empleados')->insert($empleados);

        $modelos = [
            ['Id_Modelo' => 1, 'Nombre' => 'Latitude 5440', 'FkId_Marca' => 1, 'FkId_Tipo' => 1, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 2, 'Nombre' => 'ProDesk 600 G5', 'FkId_Marca' => 1, 'FkId_Tipo' => 2, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 3, 'Nombre' => 'EliteBook 840', 'FkId_Marca' => 2, 'FkId_Tipo' => 1, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 4, 'Nombre' => 'Pavilion 24', 'FkId_Marca' => 2, 'FkId_Tipo' => 3, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 5, 'Nombre' => 'ThinkPad T14', 'FkId_Marca' => 3, 'FkId_Tipo' => 1, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 6, 'Nombre' => 'EcoTank L3250', 'FkId_Marca' => 4, 'FkId_Tipo' => 4, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 7, 'Nombre' => 'Smart Monitor M5', 'FkId_Marca' => 5, 'FkId_Tipo' => 3, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 8, 'Nombre' => 'MX Master 3', 'FkId_Marca' => 6, 'FkId_Tipo' => 10, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 9, 'Nombre' => 'PowerShot G7 X', 'FkId_Marca' => 7, 'FkId_Tipo' => 7, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 10, 'Nombre' => 'Apex 50', 'FkId_Marca' => 8, 'FkId_Tipo' => 9, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 11, 'Nombre' => 'Office 365', 'FkId_Marca' => 9, 'FkId_Tipo' => 10, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
            ['Id_Modelo' => 12, 'Nombre' => 'Catalyst 9300', 'FkId_Marca' => 10, 'FkId_Tipo' => 10, 'URL_Imagen' => null, 'Estatus' => 'Activo'],
        ];
        DB::table('modelos')->insert($modelos);

        $facturas = [
            ['Id_Factura' => 1, 'Folio' => 'F-2026-001', 'Fecha' => '2026-01-12', 'Proveedor' => 'TecnoMax', 'Monto' => 12450.00, 'Descripcion' => 'Laptops para coordinación', 'URL_Factura' => 'https://example.com/facturas/f-2026-001.pdf'],
            ['Id_Factura' => 2, 'Folio' => 'F-2026-002', 'Fecha' => '2026-02-09', 'Proveedor' => 'Sistemas Digitales', 'Monto' => 8650.00, 'Descripcion' => 'Monitores y soporte', 'URL_Factura' => 'https://example.com/facturas/f-2026-002.pdf'],
            ['Id_Factura' => 3, 'Folio' => 'F-2026-003', 'Fecha' => '2026-03-17', 'Proveedor' => 'LabPro', 'Monto' => 21840.00, 'Descripcion' => 'Equipo de laboratorio', 'URL_Factura' => 'https://example.com/facturas/f-2026-003.pdf'],
            ['Id_Factura' => 4, 'Folio' => 'F-2026-004', 'Fecha' => '2026-04-11', 'Proveedor' => 'OfficeHub', 'Monto' => 3460.80, 'Descripcion' => 'Licencias Microsoft', 'URL_Factura' => 'https://example.com/facturas/f-2026-004.pdf'],
            ['Id_Factura' => 5, 'Folio' => 'F-2026-005', 'Fecha' => '2026-05-23', 'Proveedor' => 'Audio Visuales MX', 'Monto' => 9725.50, 'Descripcion' => 'Equipo audiovisual', 'URL_Factura' => 'https://example.com/facturas/f-2026-005.pdf'],
            ['Id_Factura' => 6, 'Folio' => 'F-2026-006', 'Fecha' => '2026-06-18', 'Proveedor' => 'Muebles Universales', 'Monto' => 15880.00, 'Descripcion' => 'Mobiliario de oficina y aulas', 'URL_Factura' => 'https://example.com/facturas/f-2026-006.pdf'],
        ];
        DB::table('facturas')->insert($facturas);

        $articulos = [
            ['Id_Articulo' => 1, 'Descripcion' => 'Laptop Dell Latitude 5440 para coordinación', 'FkId_Marca' => 1, 'FkId_Modelo' => 1, 'N_Serie' => 'DL-5440-001', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 1, 'FkId_Ubicacion' => 2, 'FkId_Empleado' => 1, 'FkId_Factura' => 1, 'Notas' => 'Asignada a dirección', 'Comentarios' => 'Uso administrativo', 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 2, 'Descripcion' => 'Desktop HP ProDesk 600 G5', 'FkId_Marca' => 2, 'FkId_Modelo' => 2, 'N_Serie' => 'HP-600-015', 'Color' => 'Gris', 'FkId_Categoria' => 2, 'FkId_Tipo' => 2, 'FkId_Ubicacion' => 2, 'FkId_Empleado' => 2, 'FkId_Factura' => 1, 'Notas' => 'Equipo administrativo', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 3, 'Descripcion' => 'Escritorio de madera para oficina', 'FkId_Marca' => 1, 'FkId_Modelo' => 2, 'N_Serie' => null, 'Color' => 'Nogal', 'FkId_Categoria' => 1, 'FkId_Tipo' => 5, 'FkId_Ubicacion' => 1, 'FkId_Empleado' => 3, 'FkId_Factura' => 6, 'Notas' => 'Se usa para reuniones', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 4, 'Descripcion' => 'Monitor Samsung Smart Monitor M5', 'FkId_Marca' => 5, 'FkId_Modelo' => 7, 'N_Serie' => 'SM-2026-011', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 3, 'FkId_Ubicacion' => 3, 'FkId_Empleado' => 7, 'FkId_Factura' => 2, 'Notas' => 'Laboratorio de cómputo', 'Comentarios' => 'Pantalla de uso intensivo', 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 5, 'Descripcion' => 'Impresora Epson EcoTank L3250', 'FkId_Marca' => 4, 'FkId_Modelo' => 6, 'N_Serie' => 'EP-3250-022', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 4, 'FkId_Ubicacion' => 9, 'FkId_Empleado' => 3, 'FkId_Factura' => 2, 'Notas' => 'Uso de recepción y administración', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 6, 'Descripcion' => 'Laptop Lenovo ThinkPad T14', 'FkId_Marca' => 3, 'FkId_Modelo' => 5, 'N_Serie' => 'LT-014-117', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 1, 'FkId_Ubicacion' => 10, 'FkId_Empleado' => 8, 'FkId_Factura' => 1, 'Notas' => 'Asignada a soporte técnico', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 7, 'Descripcion' => 'Equipo de laboratorio de química', 'FkId_Marca' => 8, 'FkId_Modelo' => 10, 'N_Serie' => 'BS-1010-100', 'Color' => 'Azul', 'FkId_Categoria' => 4, 'FkId_Tipo' => 8, 'FkId_Ubicacion' => 4, 'FkId_Empleado' => 6, 'FkId_Factura' => 3, 'Notas' => 'Uso en laboratorio', 'Comentarios' => 'Mantener en condiciones controladas', 'Estado' => 'Reparacion', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 8, 'Descripcion' => 'Licencia Microsoft Office 365', 'FkId_Marca' => 9, 'FkId_Modelo' => 11, 'N_Serie' => null, 'Color' => null, 'FkId_Categoria' => 6, 'FkId_Tipo' => 10, 'FkId_Ubicacion' => 10, 'FkId_Empleado' => 2, 'FkId_Factura' => 4, 'Notas' => 'Licencia anual', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'No Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 9, 'Descripcion' => 'Switch Cisco Catalyst 9300', 'FkId_Marca' => 10, 'FkId_Modelo' => 12, 'N_Serie' => 'CS-9300-432', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 10, 'FkId_Ubicacion' => 10, 'FkId_Empleado' => 8, 'FkId_Factura' => 4, 'Notas' => 'Red institucional', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 10, 'Descripcion' => 'Equipo audiovisual para aula', 'FkId_Marca' => 5, 'FkId_Modelo' => 7, 'N_Serie' => 'AV-915', 'Color' => 'Plateado', 'FkId_Categoria' => 3, 'FkId_Tipo' => 7, 'FkId_Ubicacion' => 5, 'FkId_Empleado' => 5, 'FkId_Factura' => 5, 'Notas' => 'Asignado al aula 101', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 11, 'Descripcion' => 'Silla ejecutiva de oficina', 'FkId_Marca' => 1, 'FkId_Modelo' => 2, 'N_Serie' => null, 'Color' => 'Negro', 'FkId_Categoria' => 1, 'FkId_Tipo' => 6, 'FkId_Ubicacion' => 1, 'FkId_Empleado' => 2, 'FkId_Factura' => 6, 'Notas' => 'Mobiliario de dirección', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 12, 'Descripcion' => 'Laptop HP EliteBook 840', 'FkId_Marca' => 2, 'FkId_Modelo' => 3, 'N_Serie' => 'HP-EL-840-020', 'Color' => 'Plateado', 'FkId_Categoria' => 2, 'FkId_Tipo' => 1, 'FkId_Ubicacion' => 6, 'FkId_Empleado' => 10, 'FkId_Factura' => 1, 'Notas' => 'Asignada a docentes', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 13, 'Descripcion' => 'Laptop Dell Latitude 5440 para servicios escolares', 'FkId_Marca' => 1, 'FkId_Modelo' => 1, 'N_Serie' => 'DL-5440-205', 'Color' => 'Negro', 'FkId_Categoria' => 2, 'FkId_Tipo' => 1, 'FkId_Ubicacion' => 9, 'FkId_Empleado' => 9, 'FkId_Factura' => 1, 'Notas' => 'Asignada a apoyo escolar', 'Comentarios' => 'Pendiente de revisión', 'Estado' => 'Dañado', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 14, 'Descripcion' => 'Cámara fotográfica Canon PowerShot G7 X', 'FkId_Marca' => 7, 'FkId_Modelo' => 9, 'N_Serie' => 'CN-PS-7X-77', 'Color' => 'Negro', 'FkId_Categoria' => 3, 'FkId_Tipo' => 7, 'FkId_Ubicacion' => 5, 'FkId_Empleado' => 5, 'FkId_Factura' => 5, 'Notas' => 'Uso para eventos académicos', 'Comentarios' => null, 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => true, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
            ['Id_Articulo' => 15, 'Descripcion' => 'Herramienta eléctrica portátil', 'FkId_Marca' => 8, 'FkId_Modelo' => 10, 'N_Serie' => 'BS-HT-440', 'Color' => 'Rojo', 'FkId_Categoria' => 5, 'FkId_Tipo' => 9, 'FkId_Ubicacion' => 7, 'FkId_Empleado' => 6, 'FkId_Factura' => 3, 'Notas' => 'Uso técnico', 'Comentarios' => 'Revisar baterías', 'Estado' => 'Obsoleto', 'Tipo_Articulo' => 'Capitalizable', 'Revisado' => false, 'Fecha_Creacion' => now(), 'Fecha_Actualizacion' => now()],
        ];
        DB::table('articulos')->insert($articulos);
        foreach (range(1, 15) as $id) { DB::table('articulos')->where('Id_Articulo',$id)->update(['Codigo_Barras'=>'INV-'.str_pad((string)$id,5,'0',STR_PAD_LEFT)]); }
    }
}
