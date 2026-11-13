# Anteproyecto: FotoGest Pro

**Centro:** CIFP Avilés  
**Ciclo:** 2.º de Desarrollo de Aplicaciones Web (DAW) Virtual  
**Módulo:** Proyecto Intermodular (0379 · PIDW2)  
**Curso:** 2026/2027  
**Autora:** Bárbara Álvarez Requejo  
**Tutor:** Pablo Lera Menéndez  
**Repositorio GitHub:** https://github.com/barbararequejo/daw-proyecto-fotogest-pro  
**Tablero GitHub Projects:** https://github.com/users/barbararequejo/projects/1

---

## 1. Justificación y dominio funcional

### 1.1. Elevator pitch (100–150 palabras)

FotoGest Pro es una aplicación web para la gestión de pequeños negocios de fotografía. El trabajo diario de un estudio implica atender a los clientes, gestionar las solicitudes de sesiones, controlar los pagos y entregar las fotografías de cada encargo. Cuando estos datos están repartidos en distintas herramientas, su consulta y seguimiento resultan poco eficientes. FotoGest Pro centraliza estas operaciones en una sola aplicación. Desde el perfil de administración, la fotógrafa mantiene el catálogo de servicios y sus tarifas, revisa las solicitudes, organiza las sesiones, registra los pagos y prepara las galerías privadas. Cada cliente dispone de una cuenta desde la que consulta sus sesiones y descarga sus propias fotografías. La aplicación complementa la web profesional existente mediante un enlace al área de clientes, sin sustituirla.

### 1.2. Público objetivo y contexto de uso

El proyecto se dirige a fotógrafos autónomos y a los clientes que contratan sus servicios. Su contexto de uso es el de un negocio pequeño que gestiona varias sesiones a la vez, controla el estado de cada reserva y entrega las imágenes de forma privada. La aplicación reúne esta información en un único entorno y facilita su consulta.

- **Administradora (fotógrafa):** gestiona los servicios y las tarifas, revisa las solicitudes, organiza las sesiones, registra los pagos y sube las fotografías a las galerías privadas.
- **Usuario registrado (cliente):** solicita sesiones, consulta el estado de sus reservas y pagos, y visualiza o descarga las fotografías de su espacio privado.
- **Público / invitado:** consulta el catálogo de servicios y accede al formulario de registro.

El acceso se realiza desde el navegador de un ordenador, una tableta o un móvil. La web profesional mantiene su funcionamiento actual e incorpora un enlace al área privada. La aplicación no sustituye a las herramientas de contabilidad ni de edición fotográfica: su alcance son las tareas de gestión de clientes, sesiones, pagos y entrega de imágenes.

---

## 2. Roles del sistema y casos de uso

### 2.1. Matriz de roles (RBAC)

| Funcionalidad | Administradora | Cliente registrado | Público / invitado |
|---|---|---|---|
| Consultar servicios públicos | Sí | Sí | Sí |
| Registrarse | No necesario | Ya registrado | Sí |
| Solicitar una sesión | Sí, en nombre de un cliente | Sí | No |
| Consultar sesiones | Todas | Solo las propias | No |
| Cambiar el estado de una sesión | Sí | No | No |
| Registrar o modificar pagos | Sí | No | No |
| Consultar el estado de pagos | Todos | Solo los propios | No |
| Subir fotografías a una galería | Sí | No | No |
| Ver y descargar fotografías | Todas | Solo las de sus sesiones | No |
| Acceder al panel de administración | Sí | No | No |

La API comprueba el rol y la titularidad de cada recurso antes de devolver información privada. Cada cliente accede solo a sus propios datos y a las galerías de sus sesiones; cualquier intento de acceder a un recurso ajeno recibe la respuesta 403 Forbidden.

### 2.2. Catálogo formal de casos de uso

#### CU-01. Registro e inicio de sesión

**Actor principal:** Público / invitado y usuario registrado.  
**Objetivo:** permitir el acceso a las funciones que corresponden a cada tipo de usuario.

**Secuencia operativa:**
1. El visitante abre el formulario de registro.
2. Introduce los datos solicitados y envía el formulario.
3. El sistema valida los datos y crea la cuenta si son correctos.
4. El usuario introduce sus credenciales en el formulario de inicio de sesión.
5. El sistema comprueba las credenciales y abre el área correspondiente a su rol.

**Resultado:** el usuario puede acceder a las funciones permitidas para su cuenta. Si los datos no son válidos, el sistema muestra un mensaje y no permite el acceso.

#### CU-02. Consultar el catálogo de servicios

**Actor principal:** Público / invitado y cliente registrado.  
**Objetivo:** consultar los servicios fotográficos disponibles y su información básica.

**Secuencia operativa:**
1. El usuario accede a la sección de servicios.
2. El sistema muestra los servicios publicados y sus datos principales, como descripción, precio orientativo y duración cuando corresponda.
3. El usuario selecciona un servicio para consultar más información.
4. Si desea contratarlo, puede iniciar sesión o acceder al formulario de solicitud de sesión.

**Resultado:** el usuario puede conocer los servicios disponibles antes de enviar una solicitud.

#### CU-03. Gestionar servicios y tarifas

**Actor principal:** Administradora.  
**Objetivo:** mantener actualizada la información de los servicios fotográficos.

**Secuencia operativa:**
1. La administradora inicia sesión y accede al panel de gestión.
2. Abre la sección de servicios.
3. Puede crear un servicio nuevo o seleccionar uno existente para modificarlo o desactivarlo.
4. Introduce o actualiza el nombre, la descripción, el precio y la duración.
5. El sistema valida los datos y guarda los cambios.

**Resultado:** el catálogo queda actualizado y solo se muestran públicamente los servicios disponibles.

#### CU-04. Solicitar una sesión

**Actor principal:** Cliente registrado.  
**Objetivo:** enviar una solicitud para contratar una sesión fotográfica.

**Secuencia operativa:**
1. El cliente inicia sesión y accede al formulario de solicitud.
2. Selecciona el servicio o los servicios que le interesan.
3. Indica una fecha preferida y añade los detalles necesarios para la solicitud.
4. Envía el formulario.
5. El sistema valida la información y registra la solicitud con estado pendiente.
6. La administradora puede consultar la solicitud desde el panel de gestión.

**Resultado:** la solicitud queda registrada para su revisión. El envío no supone una confirmación automática de la fecha.

#### CU-05. Gestionar y consultar sesiones

**Actor principal:** Administradora y cliente registrado.  
**Objetivo:** permitir la gestión de las solicitudes y la consulta del estado de las sesiones.

**Secuencia operativa:**
1. La administradora accede al listado de solicitudes y sesiones.
2. Selecciona una solicitud y revisa los datos enviados por el cliente.
3. Actualiza su estado, por ejemplo, pendiente, aceptada o rechazada, y ajusta los datos acordados cuando sea necesario.
4. El sistema guarda los cambios.
5. El cliente inicia sesión y accede a su área privada.
6. El sistema muestra únicamente las sesiones asociadas a ese cliente y su estado actual.

**Resultado:** la administradora puede llevar el seguimiento de las sesiones y el cliente puede consultar la información de sus propias reservas.

#### CU-06. Registrar y consultar pagos

**Actor principal:** Administradora y cliente registrado.  
**Objetivo:** llevar un control básico de los pagos asociados a las sesiones.

**Secuencia operativa:**
1. La administradora accede a la sesión correspondiente.
2. Registra un pago e indica el importe, la fecha y el estado.
3. El sistema valida y guarda el registro relacionado con la sesión.
4. El cliente inicia sesión y abre el apartado de sus sesiones o pagos.
5. El sistema muestra el estado de los pagos vinculados únicamente a sus propias sesiones.

**Resultado:** queda registrado el seguimiento manual de los pagos. El MVP no incluye pasarela de pago ni procesa cobros con tarjeta.

#### CU-07. Subir, consultar y descargar fotografías

**Actor principal:** Administradora y cliente registrado.  
**Objetivo:** entregar las fotografías de cada sesión mediante una galería privada.

**Secuencia operativa:**
1. La administradora selecciona una sesión desde el panel de gestión.
2. Sube las fotografías destinadas a esa sesión.
3. El sistema valida los archivos y guarda la información necesaria para relacionarlos con la sesión.
4. El cliente inicia sesión y abre la galería de una de sus sesiones.
5. El sistema comprueba que el cliente tiene permiso para acceder a esa galería.
6. El cliente puede visualizar o descargar las fotografías disponibles.
7. Si intenta acceder a una galería que no le corresponde, el sistema deniega el acceso.

**Resultado:** las fotografías quedan organizadas por sesión y solo son accesibles para los usuarios autorizados.

---

### 2.3. Alcance del MVP (Hito 3, diciembre)

El MVP es la primera versión utilizable de principio a fin. Prioriza las funciones básicas: la gestión de servicios y sesiones, el registro de pagos y el acceso de cada cliente a su galería privada.

**Incluido en el MVP:**

- Registro e inicio de sesión con permisos diferenciados por rol.
- Catálogo público de servicios fotográficos.
- Panel privado de administración.
- Alta, consulta, modificación y desactivación de servicios.
- Solicitud de sesiones por parte de clientes registrados.
- Gestión de solicitudes y actualización del estado de las sesiones.
- Asociación de una sesión con uno o varios servicios, con el precio y la duración pactados.
- Registro manual de pagos y consulta de su estado.
- Galerías privadas asociadas a sesiones, con acceso y descarga protegidos.
- Base de datos persistente y publicación de la aplicación mediante HTTPS.

**Fuera del MVP:**

- Cobros con tarjeta y reembolsos automáticos.
- Edición de fotografías dentro de la aplicación.
- Aplicación móvil nativa.
- Sincronización con calendarios externos.
- Integración con programas de contabilidad.
- Campañas de marketing y envíos masivos.

La aplicación es independiente de la web pública del negocio, que enlaza con el área de clientes.

---

## 3. Modelo de datos

La información se almacena en una base de datos relacional MySQL. El diagrama entidad-relación completo está en [`docs/der.md`](docs/der.md) y el diccionario de datos en [`docs/diccionario_datos.md`](docs/diccionario_datos.md).

**Entidades de negocio:**

- **`clientes`:** datos personales y de contacto de cada cliente.
- **`servicios_fotograficos`:** catálogo de servicios con nombre, descripción, precio base, duración y disponibilidad.
- **`sesiones_citas`:** solicitudes y sesiones, con su fecha, lugar y estado, vinculadas al cliente.
- **`pagos`:** pagos de cada sesión, con importe, fecha, estado y método de pago.
- **`fotografias_galeria`:** metadatos de las fotografías y su vinculación con la sesión.

**Tabla técnica:** `users` guarda el email, el hash de la contraseña y el rol (`admin` o `cliente`) de cada cuenta. Cada cliente se enlaza con su cuenta mediante `clientes.user_id`. Esta tabla no computa como entidad de negocio.

### Relaciones principales

- **1:N entre `clientes` y `sesiones_citas`:** un cliente tiene cero o varias sesiones; cada sesión pertenece a un único cliente.
- **M:N entre `sesiones_citas` y `servicios_fotograficos`:** se resuelve con la tabla intermedia `sesion_servicio`, que incluye los atributos propios `precio_pactado`, `duracion_minutos` y `extras_incluidos`. Estos atributos conservan las condiciones acordadas aunque el catálogo cambie después.
- **1:N entre `sesiones_citas` y `pagos`:** una sesión tiene cero o varios pagos.
- **1:N entre `sesiones_citas` y `fotografias_galeria`:** una sesión tiene cero o varias fotografías.

### Justificación de la tercera forma normal (3FN)

El modelo relacional sigue los principios de normalización hasta la tercera forma normal (3FN). Esta organización reduce la redundancia de información y evita anomalías durante las operaciones de inserción, actualización y eliminación de registros.

- **1FN:** cada atributo contiene un único valor. Los servicios contratados y las fotografías se representan como filas relacionadas, no como listas dentro de una columna.
- **2FN:** los atributos de `sesion_servicio` dependen de la clave compuesta completa (`sesion_id`, `servicio_id`).
- **3FN:** no existen dependencias transitivas. El email y la contraseña están solo en `users`, y los datos de cada entidad permanecen en su propia tabla, sin duplicarse en las demás.

---

## 4. Stack tecnológico

- **Base de datos: MySQL 8.** Almacena los datos en tablas relacionadas, con claves foráneas y restricciones `UNIQUE` y `CHECK` que garantizan la integridad.
- **Backend: Laravel 13 (PHP 8.3).** Implementa la API REST por capas (rutas, controladores, servicios y modelos), valida los datos y aplica la autenticación con JWT y los permisos de cada rol.
- **Frontend: Vue.js.** Construye el catálogo público, el área privada del cliente y el panel de administración, y consume la API REST.
- **Alojamiento: Railway.** Publica la aplicación y la base de datos con persistencia y HTTPS.
- **Almacenamiento de fotografías: Cloudinary.** Guarda los archivos de imagen fuera del servidor de la aplicación.

Las credenciales y claves de configuración se gestionan con variables de entorno (`.env`), excluidas del repositorio. El archivo `backend/.env.example` recoge los nombres de las variables con valores ficticios.

---

## 5. Selección de add-ons

- [x] **Almacenamiento desacoplado de fotografías en Cloudinary.** Los archivos se guardan en Cloudinary en lugar de en el servidor de la aplicación. Se elige Cloudinary frente a AWS S3 porque su plan gratuito cubre el volumen del proyecto, dispone de un SDK para Laravel y genera automáticamente miniaturas para las galerías. Las imágenes son privadas y solo se entregan al cliente propietario de la sesión, a través de la API. En el Hito 2, la API guarda los archivos en el disco privado de Laravel; en el Hito 3, ese disco se sustituye por Cloudinary.
- [x] **Generación de informes PDF/Excel.** Resumen de las sesiones y los pagos registrados para consultar la actividad del negocio. Tiene carácter informativo y no sustituye a la facturación oficial.
