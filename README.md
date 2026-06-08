# CambaNet - Panel de Gestión Educativa

CambaNet es un **panel de gestión para instituciones educativas**, desarrollado en PHP, HTML, CSS y JavaScript. Está diseñado para administrar cursos, estudiantes, material didáctico y calificaciones, con un diseño **minimalista**, moderno y totalmente responsivo.

El proyecto se desarrolla con una arquitectura organizada en **MVC** y buenas prácticas de programación, pensado para ser escalable y fácil de mantener.

---

## 📌 Características

- **Gestión de estudiantes:** Visualiza todos los estudiantes y su estado de verificación.
- **Gestión de cursos:** Lista de cursos asignados a profesores, con información de estudiantes inscritos.
- **Material didáctico:** Subida, descarga y eliminación de archivos por curso.
- **Calificaciones:** Registro y control de calificaciones por estudiante.
- **Autenticación y seguridad:** Login, registro, recuperación de contraseña y verificación 2FA.
- **Perfil de usuario:** Administración de información personal y sesiones activas.
- **Diseño minimalista:** Colores verde oscuro (Santa Cruz), blanco, negro y gris.
- **Responsivo:** Sidebar adaptable, tablas y grids optimizados para móviles y escritorio.

---

## 🛠 Tecnologías Usadas

- PHP 7+
- HTML5 & CSS3 (Flexbox, Grid, Variables CSS)
- JavaScript (Vanilla)
- MySQL / MariaDB
- Composer (dependencias: PHPMailer)
- Responsive Design (Mobile First)

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/J0CIR0/CambaNet.git
cd CambaNet
```

### 2. Copiar el proyecto al servidor local

Copiar la carpeta `CambaNet` dentro de:

```text
xampp/htdocs/
```

o el directorio equivalente de tu servidor local.

### 3. Configurar la base de datos

1. Crear una base de datos en MySQL o MariaDB.
2. Ejecutar el script SQL:

```text
script.sql
```

ubicado en la raíz del proyecto.

### 4. Configurar la conexión

Editar:

```text
app/config/database.php
```

y establecer las credenciales de tu servidor MySQL.

### 5. Configurar parámetros generales

Editar:

```text
app/config/config.php
```

para configurar:

- URL base
- Rutas del sistema
- Parámetros generales

### 6. Instalar dependencias

```bash
composer install
```

### 7. Ejecutar el proyecto

Abrir en el navegador:

```text
http://localhost/CambaNet/public
```

---

## 📂 Estructura del Proyecto

```text
CambaNet/
├─ app/
│  ├─ config/
│  │  ├─ config.php
│  │  └─ database.php
│  ├─ controllers/
│  │  ├─ AdminController.php
│  │  ├─ AuthController.php
│  │  ├─ BaseController.php
│  │  ├─ CursoController.php
│  │  ├─ EstudianteController.php
│  │  ├─ ProfesorController.php
│  │  └─ ProfileController.php
│  ├─ models/
│  │  ├─ CalificacionModel.php
│  │  ├─ CursoModel.php
│  │  ├─ MaterialModel.php
│  │  ├─ SessionModel.php
│  │  └─ UsuarioModel.php
│  ├─ services/
│  │  ├─ composer.json
│  │  ├─ composer.lock
│  │  └─ EmailService.php
│  ├─ utils/
│  │  ├─ Logger.php
│  │  └─ Validator.php
│  └─ views/
│     ├─ admin/
│     ├─ auth/
│     ├─ estudiante/
│     ├─ profesor/
│     └─ profile/
├─ public/
│  ├─ css/
│  │  └─ styles.css
│  ├─ js/
│  │  └─ main.js
│  ├─ index.php
│  └─ .htaccess
├─ uploads/
│  └─ material/
├─ vendor/
├─ composer.json
├─ composer.lock
├─ composer.phar
└─ script.sql
```

---

## 🚀 Uso

### Sidebar

Navega entre las distintas secciones:

- Dashboard
- Cursos
- Estudiantes
- Material
- Calificaciones
- Perfil

### Estudiantes

- Visualización completa de estudiantes.
- Filtros por estado de verificación.

### Cursos

- Gestión de cursos.
- Consulta de estudiantes inscritos.
- Relación con profesores asignados.

### Material Didáctico

- Subida de archivos.
- Descarga de recursos.
- Eliminación de material.

### Calificaciones

- Registro de notas.
- Edición de calificaciones.
- Consulta por curso y estudiante.

---

## 📌 Buenas Prácticas

- Sanitiza y valida siempre los datos ingresados por los usuarios.
- Mantén las credenciales de la base de datos fuera del repositorio.
- Realiza copias de seguridad periódicas.
- Optimiza los archivos subidos para evitar sobrecargar el servidor.
- Utiliza control de versiones mediante Git.

---

## 📝 Autor

**JociroPy (Josué Claros Roca)**

Estudiante de Ingeniería de Sistemas, Bolivia.

GitHub: https://github.com/J0CIR0

---

## 📄 Licencia

MIT License

Puedes usar, modificar y distribuir libremente este proyecto, manteniendo la atribución correspondiente al autor.