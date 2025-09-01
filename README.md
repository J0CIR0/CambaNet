<<<<<<< HEAD
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
- HTML5 & CSS3 (Flexbox, Grid, variables CSS)  
- JavaScript (Vanilla)  
- MySQL / MariaDB  
- Composer (dependencias: PHPMailer)  
- Responsive Design (mobile-first)  

---

## ⚙️ Instalación

1. Clonar el repositorio:

```bash
git clone https://github.com/TU_USUARIO/CambaNet.git
cd CambaNet
Copiar la carpeta CambaNet dentro de xampp/htdocs/ (o el equivalente en tu servidor local).

Configurar la base de datos:

Crear una base de datos en MySQL.

Ejecutar el script SQL script.sql que se encuentra en la raíz del proyecto.

Configurar app/config/database.php con las credenciales de tu servidor MySQL.

Configurar otros parámetros en app/config/config.php (URL base, rutas, etc.).

Instalar dependencias de PHP con Composer:

bash
Copy code
composer install
Acceder al proyecto desde tu navegador:

bash
Copy code
http://localhost/CambaNet/public
📂 Estructura del Proyecto
pgsql
Copy code
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
🚀 Uso
Sidebar: Navega entre Dashboard, Cursos, Estudiantes, Material, Calificaciones y Perfil.


Material: Selecciona un curso, sube archivos PDF, DOC, PPT, imágenes o videos.

Estudiantes: Lista completa con filtros de verificación.

Calificaciones: Agrega, edita y visualiza calificaciones por curso.

📌 Buenas Prácticas
Sanitiza y valida siempre los inputs de usuario.

Mantén las credenciales de base de datos fuera del repositorio.

Realiza backups periódicos de la base de datos.

Optimiza archivos subidos para no sobrecargar el servidor.

📝 Autor
JociroPy (Josué Claros Roca)
Estudiante de Ingeniería de Sistemas, Bolivia.

📄 Licencia
MIT License – puedes usar, modificar y distribuir libremente, manteniendo la atribución al autor.
=======
# CambaNet
>>>>>>> f18a6fd7d4aa1116c7c0460a2f6df0ce4de37958
