# Catálogo de endpoints de la API

URL base: `http://127.0.0.1:8000/api/v1`

- Autenticación: cabecera `Authorization: Bearer <token>`. El token caduca a las 2 horas.
- Respuestas de éxito: `{ "data": ... }`.
- Respuestas de error: formato RFC 9457 (`application/problem+json`).

Niveles de acceso: **Público** (sin token) · **Autenticado** (cliente o admin) · **Admin** (rol `admin`).

## Autenticación

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| POST | `/auth/registro` | Público | Body: `email`, `password`, `password_confirmation`, `nombre`, `apellidos`, `telefono?` | 201 `{token, token_type, expires_in, user}` · 409 email duplicado · 422 |
| POST | `/auth/login` | Público | Body: `email`, `password` | 200 `{token, token_type, expires_in, user}` · 401 · 422 |
| GET | `/auth/yo` | Autenticado | — | 200 usuario con su ficha de cliente · 401 |
| POST | `/auth/logout` | Autenticado | — | 204 (invalida el token) · 401 |

## Servicios

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| GET | `/servicios` | Público | — | 200 lista de servicios disponibles |
| GET | `/servicios/{id}` | Público | Ruta: `id` | 200 servicio · 404 |
| GET | `/admin/servicios` | Admin | — | 200 catálogo completo (también los desactivados) |
| POST | `/admin/servicios` | Admin | Body: `nombre`, `descripcion`, `precio_base`, `duracion_minutos`, `disponible?` | 201 servicio creado · 422 |
| PUT | `/admin/servicios/{id}` | Admin | Body: cualquier campo anterior | 200 servicio · 404 · 422 |
| DELETE | `/admin/servicios/{id}` | Admin | Ruta: `id` | 204 · 404 · 409 si el servicio está en alguna sesión |

## Sesiones

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| GET | `/sesiones` | Autenticado | Query: `estado?` | 200 sesiones con cliente y servicios. El cliente solo recibe las suyas |
| POST | `/sesiones` | Autenticado | Body: `fecha_solicitada`, `servicios[]` (ids), `lugar?`, `observaciones?`, `cliente_id` (solo admin) | 201 sesión en estado `pendiente` con líneas `sesion_servicio` · 422 |
| GET | `/sesiones/{id}` | Autenticado (propietario o admin) | Ruta: `id` | 200 · 403 sesión ajena · 404 |
| PATCH | `/admin/sesiones/{id}/estado` | Admin | Body: `estado`, `fecha_confirmada` (obligatoria si `aceptada`), `lugar?` | 200 · 404 · 422 |
| PUT | `/admin/sesiones/{id}/servicios/{servicioId}` | Admin | Body: `precio_pactado?`, `duracion_minutos?`, `extras_incluidos?` | 200 · 404 · 422 |

## Pagos

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| GET | `/sesiones/{id}/pagos` | Autenticado (propietario o admin) | Ruta: `id` | 200 pagos de la sesión · 403 · 404 |
| GET | `/admin/pagos` | Admin | Query: `estado?` | 200 todos los pagos con su sesión y cliente |
| POST | `/admin/sesiones/{id}/pagos` | Admin | Body: `importe`, `fecha_pago`, `metodo_pago`, `estado?`, `concepto?` | 201 pago creado · 404 · 422 |
| PUT | `/admin/pagos/{id}` | Admin | Body: cualquier campo anterior | 200 · 404 · 422 |

## Fotografías

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| GET | `/sesiones/{id}/fotografias` | Autenticado (propietario o admin) | Ruta: `id` | 200 fotografías (sin la ruta interna) · 403 · 404 |
| GET | `/fotografias/{id}/descargar` | Autenticado (propietario o admin) | Ruta: `id` | 200 archivo de imagen · 403 · 404 |
| POST | `/admin/sesiones/{id}/fotografias` | Admin | multipart/form-data: `foto` (jpg, png, webp; máx. 15 MB), `descripcion?` | 201 fotografía creada · 404 · 422 |
| DELETE | `/admin/fotografias/{id}` | Admin | Ruta: `id` | 204 · 404 |

## Clientes

| Método | Ruta | Acceso | Entrada | Respuesta |
|---|---|---|---|---|
| GET | `/admin/clientes` | Admin | — | 200 clientes con email y número de sesiones |
| GET | `/admin/clientes/{id}` | Admin | Ruta: `id` | 200 cliente con sus sesiones · 404 |

## Códigos de estado comunes

| Código | Cuándo se devuelve |
|---|---|
| 400 | El cuerpo de la petición no es un JSON válido |
| 401 | Falta el token, es inválido o ha caducado, o el login es incorrecto |
| 403 | El rol no tiene permiso o el recurso pertenece a otro cliente |
| 404 | El recurso o la ruta no existen |
| 409 | Email ya registrado, o borrado bloqueado por restricción de integridad |
| 422 | Los datos no cumplen las reglas de validación |
| 429 | Más de 20 intentos de login o registro por minuto |
| 500 | Error interno; se registra en el log y nunca se envía la traza al cliente |
