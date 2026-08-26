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
| **Paciente** | Registrarse, ver su perfil, agendar/cancelar sus propias citas |
| **Recepcionista** | Registrar pacientes, agendar/cancelar citas a nombre de cualquier paciente, ver todas las citas |
| **Médico** | Ver su agenda de citas, registrar medicamentos y tratamientos |
| **Administrador** | Registrar empleados (médicos/recepcionistas/administradores), consultorios y medicamentos |

## Cómo funciona el agendamiento de citas

Cada médico tiene un horario semanal (`horario_medico`: días y rango de horas). Al agendar, el sistema calcula en tiempo real los horarios libres restando las citas ya reservadas de ese médico ese día, y bloquea a nivel de base de datos (`UNIQUE KEY`) que dos citas queden en el mismo horario con el mismo médico.

## Seguridad

- Contraseñas con `password_hash`/`password_verify` (nunca en texto plano).
- Sentencias preparadas en todas las consultas con datos del usuario.
- Recuperación de contraseña con token de un solo uso que vence a los 30 minutos.

Pendiente conocido: no hay protección CSRF en los formularios; queda como mejora futura si el proyecto se expone fuera de un entorno local/académico.
