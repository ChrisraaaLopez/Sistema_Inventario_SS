# Configuración de acceso por roles

## Roles incluidos
- **Administrador**: administra catálogos y artículos, crea periodos y trabaja con revisiones.
- **Revisor**: trabaja en revisión de inventario e histórico.
- **Consulta**: consulta los listados disponibles y el histórico, sin operaciones de escritura.

## Preparar el proyecto
1. Configura `.env` para tu base de datos.
2. Ejecuta `composer install`.
3. Ejecuta `php artisan key:generate` solo si tu `.env` nuevo no tiene APP_KEY.
4. Ejecuta `php artisan migrate`.
5. Para cargar datos demo y cuentas de acceso en una base vacía, ejecuta `php artisan db:seed`. **El seeder demo puede reconstruir catálogos de demostración; no lo ejecutes sobre datos que quieras conservar sin revisar primero `database/seeders/DemoModulo1Seeder.php`.** Si solo quieres crear/actualizar cuentas y roles, ejecuta `php artisan db:seed --class=UsuariosRolesSeeder`.
6. Ejecuta `php artisan storage:link` para servir imágenes/PDF guardados en `storage/app/public`.
7. Ejecuta `php artisan optimize:clear` y `php artisan serve`.

## Cuentas demo
| Usuario | Rol | Contraseña inicial |
|---|---|---|
| Osvaldo | Administrador | `Os1234` |
| Emiliano | Revisor | `Os1234` |
| Jhonatan | Consulta | `Os1234` |

Cambia las contraseñas de demostración antes de desplegar el sistema en un entorno real. No compartas el archivo `.env` real.
