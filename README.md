# MEDISOFT

Plataforma web para la gestión de citas médicas: pacientes, médicos, recepcionistas y administradores, cada uno con su propio panel y funciones.

## Tecnologías

- PHP 8.2 + MySQLi (sentencias preparadas)
- MySQL / MariaDB
- Bootstrap / MDB UI Kit + Font Awesome
- JavaScript (fetch/AJAX) para las partes dinámicas del formulario de citas

## Estructura del proyecto

```
medisoft/
├── index.html            Página principal
├── login.html             Inicio de sesión
├── bd_citas_medicas.sql   Esquema de la base de datos
├── css/                   Estilos
├── js/                    Scripts del lado del cliente
├── imagenes/              Recursos gráficos
└── php/                   Toda la lógica del servidor y páginas protegidas
```

Convención dentro de `php/`:
- `nombre.php` → página protegida (verifica sesión y rol, muestra HTML).
- `guardar_nombre.php` / `procesar_nombre.php` → procesa un formulario (INSERT/UPDATE), no imprime HTML.
- `verificar_sesion.php` / `verificar_rol.php` → guardas de autenticación reutilizadas por casi todas las páginas.

## Instalación (XAMPP)

1. Copia la carpeta del proyecto dentro de `htdocs` (debe quedar en `htdocs/medisoft`).
2. Inicia **Apache** y **MySQL** desde el Panel de Control de XAMPP.
3. Crea la base de datos e importa el esquema:
   ```
   mysql -u root -e "CREATE DATABASE bd_citas_medicas CHARACTER SET utf8 COLLATE utf8_spanish_ci;"
   mysql -u root bd_citas_medicas < bd_citas_medicas.sql
   ```
4. Revisa `php/conexion.php` si tu usuario/contraseña de MySQL no son los de XAMPP por defecto (`root` sin contraseña).
5. Abre `http://localhost/medisoft/` en el navegador.

No se necesita configurar correo ni ninguna clave externa: la recuperación de contraseña funciona verificando documento + correo registrados, sin depender de un servicio de envío de correos.

## Roles del sistema

| Rol | Puede hacer |
|---|---|
| **Paciente** | Registrarse, ver/editar su perfil, agendar/reagendar/cancelar sus propias citas, cambiar su contraseña |
| **Recepcionista** | Registrar pacientes, agendar/reagendar/cancelar citas a nombre de cualquier paciente, ver todas las citas y el historial clínico de cualquier paciente |
| **Médico** | Ver su agenda, marcar citas como atendidas (con diagnóstico, notas, medicamentos recetados y tratamientos aplicados), gestionar el catálogo de medicamentos/tratamientos |
| **Administrador** | Registrar y editar empleados (médicos/recepcionistas/administradores), activar/desactivar usuarios, gestionar consultorios e inventario de medicamentos, ver estadísticas del sistema |

## Cómo funciona el agendamiento de citas

Cada médico tiene un horario semanal (`horario_medico`: días y rango de horas) y un **consultorio fijo asignado**. Al agendar, el sistema calcula en tiempo real los horarios libres restando las citas ya reservadas de ese médico ese día, y asigna automáticamente el consultorio del médico a la cita — así el paciente siempre sabe a qué consultorio ir.

Dos restricciones únicas a nivel de base de datos (sobre columnas generadas que ignoran las citas canceladas) evitan que:
- Un mismo médico quede doble-reservado a la misma hora.
- Dos médicos que comparten consultorio queden agendados en el mismo cuarto a la misma hora.

Al marcar una cita como atendida, el médico puede recetar medicamentos del inventario (se descuenta el stock automáticamente) y registrar tratamientos aplicados; todo queda visible en el historial clínico del paciente.

## Seguridad

- Contraseñas con `password_hash`/`password_verify` (nunca en texto plano).
- Sentencias preparadas en todas las consultas con datos del usuario.
- Recuperación de contraseña con token de un solo uso que vence a los 30 minutos.
- Documento y correo únicos a nivel de base de datos (no solo validados en la aplicación).
- Protección CSRF (`php/csrf.php`) en todos los formularios que modifican datos.
- Páginas protegidas marcadas como no-cache y con un guard de JavaScript para que el botón "atrás" del navegador no muestre contenido de una sesión ya cerrada.

Pendiente conocido: `login.html` es un archivo estático y no tiene token CSRF (requeriría convertirlo a `.php`); no hay límite de intentos de inicio de sesión (rate limiting).
