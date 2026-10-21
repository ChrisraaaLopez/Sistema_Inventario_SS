<div align="center">

<img src="assets/banner.svg" alt="Sistema Inventario" width="100%">

<br>

![Laravel](https://img.shields.io/badge/LARAVEL-13.X-FF2D20?style=for-the-badge&logo=laravel&logoColor=white&labelColor=555555)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white&labelColor=555555)
![Tailwind](https://img.shields.io/badge/TAILWIND-4.X-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white&labelColor=555555)
![Vite](https://img.shields.io/badge/VITE-8.X-646CFF?style=for-the-badge&logo=vite&logoColor=white&labelColor=555555)
![MySQL](https://img.shields.io/badge/MYSQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white&labelColor=555555)

![Estado](https://img.shields.io/badge/Estado-En_desarrollo-38BDF8?style=flat-square&labelColor=555555)
![Servicio Social](https://img.shields.io/badge/Proyecto-Servicio_Social-8B5CF6?style=flat-square&labelColor=555555)

<br>

Sistema de control de inventario hecho como **proyecto de servicio social**: un espacio para registrar los bienes de la institución, revisarlos físicamente con la cámara del celular y dejar constancia de **quién tuvo qué, y cuándo**.

<sub>Documentación técnica, diagramas y decisiones de diseño en <code>docs/</code></sub>

</div>

---

## ✨ Características

- Registro de artículos con marca, modelo, no. de serie, categoría, tipo, ubicación, responsable, estado y clasificación patrimonial.
- Catálogos normalizados: la marca filtra dinámicamente los modelos disponibles.
- Alta rápida de marca, modelo y factura desde el formulario de artículo, sin cambiar de pantalla.
- Facturas con folio, proveedor y monto, con el PDF consultable dentro del sistema.
- Imagen de referencia por modelo para comparar visualmente en campo.
- Registro de vehículos escolares (placas, serie, marca, modelo, año, capacidad y estado).
- Baja de artículos con registro histórico.
- Periodos de revisión (abiertos / cerrados) por ubicación.
- Lectura de **QR y código de barras** desde el navegador del celular.

## 📱 Escaneo QR local

Para usar la cámara del teléfono en la revisión patrimonial, el sitio debe servirse con HTTPS en la misma red local. En Laragon suele bastar con habilitar el SSL del sitio o usar un certificado local con mkcert y apuntar la app a la URL segura: `https://localhost` o `https://inventario.local`.

1. Activa el SSL del proyecto en Laragon o genera un certificado local con `mkcert`.
2. Asegúrate de que la URL pública del proyecto coincida con el `APP_URL` del entorno.
3. En el navegador móvil, abre la URL HTTPS y acepta el acceso a la cámara.
4. Si el escáner no inicia, revisa que la página se esté sirviendo por HTTPS y que el navegador permita permisos de cámara.

## 🔐 Seguridad

- La reapertura de un resguardo o periodo solo está disponible para el rol Administrador y pide confirmación en pantalla.
- El modo de depuración se mantiene apagado en producción y solo debe activarse temporalmente para diagnóstico.
- Artículos revisados en verde, faltantes en rojo y detección de artículos fuera de lugar con opción de reasignarlos.
- Resguardos imprimibles para firma del empleado responsable.
- Al desplegar, usa `APP_DEBUG=false` y `APP_ENV=production` en el `.env`, y cambia las contraseñas demo (`Os1234`) de los usuarios.

## 🧪 Pruebas

Antes de ejecutar `php artisan test` debe existir `.env` con `APP_KEY` (`copy .env.example .env` y `php artisan key:generate`). Las pruebas usan SQLite en memoria (ver `phpunit.xml`) y no tocan la base de datos MySQL del proyecto.
- Un periodo cerrado queda como histórico y no se modifica sin autorización del administrador.
- Cuentas con roles, empleado asociado y desactivación sin perder el historial.
- Rutas protegidas por rol en el servidor, no solo ocultando enlaces del menú.

## 👥 Roles

| Rol | Alcance |
| --- | --- |
| **Administrador** | Catálogos, artículos, cuentas, vehículos, periodos y reapertura de periodos cerrados |
| **Revisor** | Revisiones por escaneo y consulta del histórico |
| **Consulta** | Solo lectura de inventario e histórico |

## 🧱 Stack

| Capa | Tecnología |
| --- | --- |
| Backend | Laravel 13 · PHP 8.3 |
| Frontend | Blade · Tailwind CSS 4 · Vite 8 |
| Base de datos | MySQL 8 |
| Despliegue | Red local de la institución (sin exposición a internet pública) |

## 🗂️ Estructura

```
├── app/          # Modelos, controladores y lógica
├── database/     # Migraciones y seeders
├── resources/    # Vistas Blade, CSS y JS
├── routes/       # Rutas web
├── docs/         # ERS, diagramas y decisiones de diseño
└── assets/       # Recursos del README
```

## 🤝 Colaboradores

<table>
<tr>
<td align="center">
<a href="https://github.com/ChrisraaaLopez">
<img src="https://github.com/ChrisraaaLopez.png?size=100" width="100" height="100" alt="ChrisraaaLopez"><br>
<sub><b>ChrisraaaLopez</b></sub>
</a><br>
<sub>Desarrollo</sub>
</td>
<td align="center">
<a href="https://github.com/OsvaldoMarGal">
<img src="https://github.com/OsvaldoMarGal.png?size=100" width="100" height="100" alt="OsvaldoMarGal"><br>
<sub><b>OsvaldoMarGal</b></sub>
</a><br>
<sub>Desarrollo</sub>
</td>
<td align="center">
<a href="https://github.com/CDiazMC23">
<img src="https://github.com/CDiazMC23.png?size=100" width="100" height="100" alt="CDiazMC23"><br>
<sub><b>CDiazMC23</b></sub>
</a><br>
<sub>Desarrollo</sub>
</td>
</tr>
</table>

---

<div align="center">
<sub>Proyecto de Servicio Social</sub>
</div>