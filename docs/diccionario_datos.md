# Diccionario de datos de FotoGest Pro

Motor: MySQL 8 · Juego de caracteres: `utf8mb4` · Cotejamiento: `utf8mb4_unicode_ci` · Motor de tablas: InnoDB.

Todas las tablas incluyen `created_at` y `updated_at` (`TIMESTAMP NULL`), que Laravel gestiona automáticamente. No se repiten en cada tabla.

Criterios generales para los tipos de datos:

- **Claves:** `BIGINT UNSIGNED AUTO_INCREMENT`, el tipo que usa Laravel por defecto. No admite negativos.
- **Importes:** `DECIMAL(8,2)` para que los cálculos con euros sean exactos (`FLOAT` produce errores de redondeo). Admite hasta 999.999,99 €.
- **Duraciones:** `SMALLINT UNSIGNED` (0–65.535 minutos), suficiente para un servicio de varios días.
- **Estados:** `ENUM`. La base de datos solo acepta los valores definidos.

---

## users (tabla técnica de autenticación)

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador de la cuenta |
| email | VARCHAR(150) | NO | UK | — | UNIQUE | Email de acceso; no puede repetirse |
| password | VARCHAR(255) | NO | — | — | Hash bcrypt, coste 12 | Contraseña cifrada; nunca se devuelve en la API |
| rol | ENUM('admin','cliente') | NO | — | 'cliente' | Solo los dos valores | Perfil para el control de acceso (RBAC) |

## clientes

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador del cliente |
| user_id | BIGINT UNSIGNED | NO | FK, UK | — | REFERENCES users(id) ON DELETE CASCADE; UNIQUE (1:1) | Cuenta de acceso del cliente |
| nombre | VARCHAR(80) | NO | — | — | — | Nombre |
| apellidos | VARCHAR(120) | NO | — | — | — | Apellidos |
| telefono | VARCHAR(20) | SÍ | — | NULL | Solo dígitos, espacios y `+` (validación API) | Teléfono de contacto. Es texto porque puede llevar prefijo y no se opera con él |

## servicios_fotograficos

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador del servicio |
| nombre | VARCHAR(100) | NO | UK | — | UNIQUE | Nombre comercial del servicio |
| descripcion | TEXT | NO | — | — | Máx. 2000 caracteres (validación API) | Descripción para el catálogo |
| precio_base | DECIMAL(8,2) | NO | — | — | CHECK (precio_base >= 0) | Tarifa actual del catálogo |
| duracion_minutos | SMALLINT UNSIGNED | NO | — | — | CHECK (duracion_minutos > 0) | Duración orientativa |
| disponible | TINYINT(1) | NO | — | 1 | Booleano | Indica si aparece en el catálogo público; desactivar sustituye al borrado |

## sesiones_citas

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador de la sesión |
| cliente_id | BIGINT UNSIGNED | NO | FK | — | REFERENCES clientes(id) ON DELETE RESTRICT; índice (cliente_id, estado) | Cliente que solicita la sesión |
| fecha_solicitada | DATETIME | NO | — | — | Posterior a hoy al crearla (validación API) | Fecha y hora que pide el cliente |
| fecha_confirmada | DATETIME | SÍ | — | NULL | Obligatoria si estado = 'aceptada' (validación API) | Fecha acordada por la fotógrafa |
| lugar | VARCHAR(150) | SÍ | — | NULL | — | Lugar de la sesión |
| estado | ENUM('pendiente','aceptada','rechazada','realizada','cancelada') | NO | — | 'pendiente' | Solo esos valores | Estado de la solicitud |
| observaciones | TEXT | SÍ | — | NULL | Máx. 2000 caracteres (validación API) | Detalles que añade el cliente |

## sesion_servicio (tabla intermedia M:N)

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| sesion_id | BIGINT UNSIGNED | NO | PK, FK | — | REFERENCES sesiones_citas(id) ON DELETE CASCADE | Sesión a la que pertenece la línea |
| servicio_id | BIGINT UNSIGNED | NO | PK, FK | — | REFERENCES servicios_fotograficos(id) ON DELETE RESTRICT | Servicio contratado |
| precio_pactado | DECIMAL(8,2) | NO | — | — | CHECK (precio_pactado >= 0) | Precio acordado; se conserva aunque cambie el catálogo |
| duracion_minutos | SMALLINT UNSIGNED | NO | — | — | CHECK (duracion_minutos > 0) | Duración acordada |
| extras_incluidos | VARCHAR(255) | SÍ | — | NULL | — | Extras negociados para ese servicio |

## pagos

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador del pago |
| sesion_id | BIGINT UNSIGNED | NO | FK | — | REFERENCES sesiones_citas(id) ON DELETE RESTRICT | Sesión a la que corresponde el pago |
| importe | DECIMAL(8,2) | NO | — | — | CHECK (importe > 0) | Cantidad pagada o pendiente |
| fecha_pago | DATE | NO | — | — | — | Fecha del pago o del vencimiento |
| estado | ENUM('pendiente','pagado','devuelto') | NO | — | 'pendiente' | Solo esos valores | Situación del pago |
| metodo_pago | ENUM('efectivo','transferencia','bizum','tarjeta') | NO | — | — | Solo esos valores | Forma de pago (registro manual, sin pasarela) |
| concepto | VARCHAR(150) | SÍ | — | NULL | — | Descripción, por ejemplo «Señal de reserva» |

## fotografias_galeria

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | — | Identificador de la fotografía |
| sesion_id | BIGINT UNSIGNED | NO | FK | — | REFERENCES sesiones_citas(id) ON DELETE CASCADE | Sesión a cuya galería pertenece |
| nombre_archivo | VARCHAR(150) | NO | — | — | — | Nombre original del archivo, usado en la descarga |
| ruta_almacenamiento | VARCHAR(255) | NO | UK | — | UNIQUE; oculta en las respuestas JSON | Ruta interna del archivo en el disco privado |
| tamano_kb | INT UNSIGNED | SÍ | — | NULL | Máx. 15 MB al subir (validación API) | Tamaño del archivo |
| descripcion | VARCHAR(255) | SÍ | — | NULL | — | Pie o descripción de la foto |
| created_at | TIMESTAMP | SÍ | — | CURRENT_TIMESTAMP | — | Fecha de subida |

---

## Justificación de ON DELETE

- **CASCADE** cuando el registro hijo no tiene sentido sin el padre: la ficha de cliente sin su cuenta, una línea de `sesion_servicio` sin su sesión o una fotografía sin su sesión.
- **RESTRICT** cuando borrar el padre haría perder información de negocio: un cliente con historial de sesiones, un servicio ya contratado (se desactiva con `disponible`) o una sesión con pagos registrados.
