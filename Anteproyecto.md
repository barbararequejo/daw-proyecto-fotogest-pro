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

FotoGest Pro será una aplicación web pensada para facilitar la organización de un pequeño negocio de fotografía. En el trabajo diario hay que atender a los clientes, gestionar las solicitudes de sesiones, tener claros los pagos y entregar las fotografías de cada encargo. Si esos datos se guardan en distintos sitios, cuesta más encontrar la información y llevar el seguimiento de cada trabajo. La propuesta consiste en reunir esas tareas en una sola aplicación. Desde ella, la fotógrafa podrá mantener actualizados sus servicios, revisar las solicitudes, organizar las sesiones, registrar los pagos y preparar galerías privadas. Los clientes tendrán una cuenta desde la que consultar sus sesiones y acceder a sus propias fotografías. La aplicación se utilizará como complemento de la web profesional, a través de un enlace al área de clientes, sin tener que sustituir la web actual.

### 1.2. Público objetivo y contexto de uso

El proyecto está dirigido, sobre todo, a fotógrafos autónomos y a las personas que contratan sus servicios. Se parte de una situación habitual en un negocio pequeño: tener que controlar varias sesiones a la vez, recordar en qué estado está cada una y entregar las imágenes de forma privada. Por eso, la aplicación busca reunir la información más importante y hacer que sea más fácil consultarla.

- **Administradora (fotógrafa):** gestionará los servicios y tarifas, revisará las solicitudes, organizará las sesiones, registrará los pagos y subirá las fotografías a las galerías privadas.
- **Usuario registrado (cliente):** podrá solicitar sesiones, consultar el estado de sus reservas y pagos, y ver o descargar las fotografías disponibles en su espacio privado.
- **Público / invitado:** podrá consultar el catálogo de servicios y una selección pública del portfolio, además de acceder al formulario de registro.

Se podrá entrar desde el navegador de un ordenador, una tableta o un móvil. La web profesional seguirá funcionando como hasta ahora y tendrá un enlace para acceder a esta zona privada. El objetivo no es crear una web nueva ni sustituir herramientas de contabilidad o edición de fotografías, sino resolver algunas tareas de gestión que forman parte del trabajo diario.

---

## 2. Roles del sistema y casos de uso

### 2.1. Matriz de roles (RBAC)

| Funcionalidad | Administradora | Cliente registrado | Público / invitado |
|---|---|---|---|
| Consultar servicios públicos | Sí | Sí | Sí |
| Registrarse | No necesario | Ya registrado | Sí |
| Solicitar una sesión | Sí, puede gestionarla | Sí | No |
| Consultar y actualizar sesiones | Todas | Solo las propias | No |
| Registrar o modificar pagos | Sí | No | No |
| Consultar el estado de pagos | Todos los registrados | Solo los propios | No |
| Subir fotografías a una galería | Sí | No | No |
| Ver y descargar fotografías | Puede gestionarlas | Solo las de sus sesiones | No |
| Acceder al panel de administración | Sí | No | No |

La aplicación comprobará los permisos antes de mostrar la información privada. De esta forma, cada cliente solo podrá consultar sus propios datos y las galerías de sus sesiones.

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

**Resultado:** queda registrado el seguimiento manual de los pagos. En el MVP no se procesarán cobros con tarjeta de forma automática.

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

El **MVP** será la primera versión que se pueda utilizar de principio a fin. Para llegar a ella, se dará prioridad a las funciones básicas: gestionar los servicios y las sesiones, registrar los pagos y permitir que cada cliente consulte su galería privada. Las mejoras que no sean imprescindibles quedarán para más adelante.

**Incluido en esta primera versión:**

- Registro e inicio de sesión con permisos diferenciados por rol.
- Catálogo público de servicios fotográficos.
- Panel privado de administración.
- Alta, consulta, modificación y desactivación de servicios.
- Solicitud de sesiones por parte de clientes registrados.
- Gestión de solicitudes y actualización del estado de las sesiones.
- Asociación de una sesión con uno o varios servicios, conservando el precio y la duración acordados.
- Registro manual de pagos y consulta de su estado.
- Galerías privadas asociadas a sesiones, con acceso y descarga protegidos.
- Base de datos persistente y publicación de la aplicación mediante HTTPS.

**Fuera de esta primera versión:**

- Cobros con tarjeta y reembolsos automáticos.
- Edición de fotografías dentro de la aplicación.
- Aplicación móvil nativa.
- Sincronización automática con calendarios externos.
- Integración con programas externos de contabilidad.
- Campañas de marketing y envíos masivos.
- Automatizaciones avanzadas e informes complejos, salvo que se completen las funciones principales y exista tiempo suficiente.

Primero se desarrollarán y probarán estas funciones principales. Solo después se valorará añadir otras características. La aplicación será independiente de la web pública, desde la que se podrá acceder mediante un enlace.

---

## 3. Modelo de datos

La información de la aplicación se organizará en una base de datos. El diagrama Entidad-Relación se incluirá en `docs/der.md` y recogerá las entidades principales y la forma en que se relacionan:

- **`clientes`:** almacena los datos necesarios de cada cliente.
- **`servicios_fotograficos`:** contiene los servicios ofrecidos, con información como nombre, descripción, precio y duración orientativa.
- **`sesiones_citas`:** recoge las solicitudes y sesiones, su fecha y su estado, y las relaciona con el cliente correspondiente.
- **`pagos`:** registra los pagos relacionados con una sesión, con datos como importe, fecha y estado.
- **`fotografias_galeria`:** almacena los datos de las fotografías y su relación con la sesión a la que pertenecen.

### Relaciones principales

- **Relación 1:N entre `clientes` y `sesiones_citas`:** un cliente puede tener varias sesiones, pero cada sesión pertenece a un único cliente.
- **Relación M:N entre `sesiones_citas` y `servicios_fotograficos`:** una sesión puede incluir varios servicios y un servicio puede formar parte de distintas sesiones. Esta relación se resolverá mediante la tabla intermedia `sesion_servicio`, que incluirá `precio_pactado`, `duracion_minutos` y `extras_incluidos`. Estos atributos permiten conservar las condiciones acordadas para cada servicio incluido en una sesión, aunque el catálogo cambie posteriormente.
- **Relación 1:N entre `sesiones_citas` y `pagos`:** una sesión podrá tener uno o varios registros de pago.
- **Relación 1:N entre `sesiones_citas` y `fotografias_galeria`:** una sesión podrá tener varias fotografías asociadas.

### Justificación de la tercera forma normal (3FN)

La base de datos se diseñará siguiendo la tercera forma normal (3FN). El propósito es evitar guardar varias veces la misma información y hacer que los datos sean más fáciles de mantener:

- **Primera forma normal (1FN):** cada campo almacenará un único valor. Los servicios contratados y las fotografías no se guardarán como listas dentro de una misma columna.
- **Segunda forma normal (2FN):** los atributos de la tabla intermedia `sesion_servicio` dependerán de la combinación de sesión y servicio a la que pertenecen.
- **Tercera forma normal (3FN):** los datos propios de clientes, servicios, sesiones, pagos y fotografías se guardarán en sus tablas correspondientes, evitando repetir información que pertenece a otra entidad.

En `docs/der.md` se detallarán las claves primarias y foráneas, los tipos de datos y las cardinalidades. Las tablas técnicas necesarias para el inicio de sesión y la gestión de roles no sustituirán a las entidades de negocio indicadas.

---

## 4. Stack tecnológico previsto

Para desarrollar la aplicación se propone utilizar las siguientes tecnologías. Cada una tendrá una función concreta: guardar la información, gestionar las operaciones de la aplicación o mostrar las pantallas que utilizarán los usuarios.

- **Motor de base de datos: MySQL.** Se utilizará para guardar la información de clientes, servicios, sesiones, pagos y fotografías en tablas relacionadas.
- **Backend API REST: Laravel con PHP.** Se encargará de procesar las solicitudes, validar los datos, gestionar las operaciones y aplicar los permisos de cada rol. También permitirá crear una API REST para comunicar el backend con la interfaz.
- **Frontend: Vue.js.** Se utilizará para construir las pantallas del catálogo público, el área privada del cliente y el panel de administración.
- **Alojamiento cloud: Railway.** Se propone como plataforma inicial para publicar la aplicación y configurar la base de datos. El despliegue deberá mantener los datos de forma persistente y utilizar HTTPS. Si durante la implementación se detecta alguna limitación, se valorará Render como alternativa.

La aplicación estará separada de la web pública del negocio, que enlazará con el área de clientes. Por seguridad, las contraseñas y las claves de configuración no se guardarán en GitHub, sino en variables de entorno.

---

## 5. Selección tentativa de Add-ons (mínimo 2)

Se proponen dos funciones adicionales que podrían mejorar la aplicación. No serán prioritarias: primero habrá que terminar las funciones básicas y comprobar que funcionan correctamente.

- [x] **Almacenamiento desacoplado de fotografías en AWS S3 o Cloudinary (+0.5).** Se valorará utilizar un servicio externo para guardar las fotografías en lugar de depender únicamente del servidor de la aplicación. El acceso a las galerías se limitará a los clientes autorizados.
- [x] **Generación de informes PDF/Excel (+0.5).** Se plantea añadir un informe sencillo con un resumen de las sesiones y los pagos registrados, para facilitar la consulta de la actividad del negocio. No sustituirá a un programa de contabilidad ni a la facturación oficial.

La prioridad será completar, probar y documentar las funciones básicas. La selección de add-ons podrá revisarse según el tiempo disponible y los requisitos técnicos del módulo.
