# FotoGest Pro

Aplicación web para gestionar los servicios, las sesiones, los pagos y las galerías privadas de un negocio de fotografía.

## Autora

Bárbara Álvarez Requejo  
Ciclo: 2.º DAW Virtual  
Centro: CIFP Avilés  
Curso académico: 2026/2027

## Descripción

FotoGest Pro centraliza la gestión diaria de un pequeño negocio de fotografía. La administradora (fotógrafa) gestiona el catálogo de servicios, las solicitudes de sesiones, los pagos y las galerías. Cada cliente consulta sus sesiones y pagos y descarga las fotografías de sus propias sesiones. La aplicación complementa la web profesional existente, que enlaza con el área de clientes.

## Tecnologías

| Capa | Tecnología |
|---|---|
| Base de datos | MySQL 8 |
| Backend | Laravel 13 (PHP 8.3) · API REST |
| Autenticación | JWT (`php-open-source-saver/jwt-auth`) · contraseñas con bcrypt |
| Frontend | Vue.js (Hito 3) |
| Alojamiento | Railway (Hito 3) |

## Estructura del repositorio

```
backend/            API REST en Laravel
  app/Http/Controllers/Api   Controladores (reciben la petición y devuelven la respuesta)
  app/Http/Requests          Validación de los datos de entrada
  app/Http/Middleware        RoleGuard (roles) y JsonValido (JSON mal formado)
  app/Services               Lógica de negocio, transacciones y control de titularidad
  app/Models                 Modelos Eloquent y relaciones
  app/Support                Formato de errores RFC 9457
  database/migrations        Esquema de la base de datos
  database/seeders           Datos de prueba
  routes/api.php             Rutas de la API (/api/v1)
docs/
  der.md                     Diagrama entidad-relación (Mermaid) y justificación 3FN
  diccionario_datos.md       Diccionario de datos
  api/endpoints.md           Catálogo de endpoints
  api/*.postman_collection.json   Colección de pruebas de Postman
```

## Arquitectura del backend

Cada petición recorre cuatro capas:

**Rutas y middlewares** → **Controladores** → **Servicios** → **Modelos (Eloquent)**

- **Rutas** (`routes/api.php`): definen los endpoints y aplican los middlewares `auth:api` (exige un JWT válido; si no, 401) y `rol:admin` (exige rol de administradora; si no, 403).
- **Controladores**: reciben los datos ya validados por un *FormRequest*, llaman al servicio y devuelven el código HTTP correspondiente. No contienen consultas.
- **Servicios**: contienen la lógica de negocio, las transacciones (`DB::transaction`) y la comprobación de que cada cliente accede solo a sus propios recursos.
- **Modelos**: acceden a la base de datos mediante Eloquent, con consultas parametrizadas que evitan la inyección SQL.

Los errores se gestionan en un único punto (`bootstrap/app.php` y `app/Support/ProblemDetails.php`) y siempre devuelven JSON con formato RFC 9457, sin trazas de pila.

## Instalación en local

Requisitos: PHP 8.3 o superior, Composer y MySQL 8 (por ejemplo, con Laragon).

1. Crear una base de datos vacía llamada `fotogest_pro` (`utf8mb4_unicode_ci`).
2. Instalar y configurar el backend:

```bash
cd backend
composer install
cp .env.example .env        # en Windows: copy .env.example .env
php artisan key:generate
php artisan jwt:secret
```

3. Revisar en `.env` los datos de conexión (`DB_USERNAME`, `DB_PASSWORD`).

### Reconstruir la base de datos (comando único)

```bash
composer db:reset
```

Este comando ejecuta `php artisan migrate:fresh --seed --force`: borra todas las tablas, ejecuta las migraciones y carga los datos de prueba.

### Arrancar la API

```bash
php artisan serve
```

La API queda disponible en `http://127.0.0.1:8000/api/v1`.

## Cuentas de prueba

Todas las cuentas tienen la contraseña `Password123`.

| Email | Rol |
|---|---|
| admin@cifpaviles.es | Administradora |
| user1@cifpaviles.es | Cliente |
| user2@cifpaviles.es | Cliente |

Los seeders crean además 8 clientes más, 10 servicios, 12 sesiones, 17 líneas de `sesion_servicio`, 14 pagos y 15 fotografías.

## Pruebas de la API

1. Importar en Postman `docs/api/FotoGestPro_Hito2.postman_collection.json`.
2. Ejecutar `composer db:reset` para partir de datos limpios.
3. Ejecutar la colección completa (*Run collection*). Los logins guardan los tokens automáticamente en las variables `{{tokenAdmin}}`, `{{tokenUser1}}` y `{{tokenUser2}}`.

La colección cubre los casos 200, 201, 204, 400, 401, 403, 404, 409 y 422. Para la subida de fotografías hay que seleccionar un archivo .jpg en la petición «Admin: subir foto».

## Flujo de trabajo con Git

- `main`: versión estable, protegida. Recibe cambios solo desde `develop` mediante Pull Request.
- `develop`: integración de las funcionalidades.
- `feature/*`: una rama por funcionalidad, integrada en `develop` mediante Pull Request.
- Commits con [Conventional Commits](https://www.conventionalcommits.org/es/) (`feat:`, `fix:`, `docs:`, `test:`, `chore:`).
- Etiquetas de entrega: `hito-1`, `hito-2`…

## Tablero Kanban

https://github.com/users/barbararequejo/projects/1

## Documentación

- [Anteproyecto](Anteproyecto.md)
- [Diagrama entidad-relación](docs/der.md)
- [Diccionario de datos](docs/diccionario_datos.md)
- [Catálogo de endpoints](docs/api/endpoints.md)
