CREATE DATABASE CambaNet;
USE CambaNet;
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);
INSERT INTO roles (nombre) VALUES ('ADM'), ('Profesor'), ('Estudiante');
CREATE TABLE tipos_suscripcion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    max_sesiones INT NOT NULL,
    precio DECIMAL(10,2),
    duracion_dias INT,
    descripcion TEXT
);
INSERT INTO tipos_suscripcion (nombre, max_sesiones, precio, duracion_dias, descripcion) VALUES
('Básica', 1, 0.00, 30, '1 sesión concurrente'),
('Premium', 3, 30.00, 30, '3 sesiones concurrentes'),
('Familiar', 5, 50.00, 30, '5 sesiones concurrentes');
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    verificado TINYINT(1) DEFAULT 0,
    forzar_cambio_password TINYINT(1) DEFAULT 0,
    habilitar_2fa TINYINT(1) DEFAULT 0,
    suscripcion_id INT DEFAULT 1,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (suscripcion_id) REFERENCES tipos_suscripcion(id)
);
CREATE TABLE suscripciones_compradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_suscripcion_id INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATETIME,
    activa TINYINT(1) DEFAULT 1,
    metodo_pago VARCHAR(50) DEFAULT 'simulado',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (tipo_suscripcion_id) REFERENCES tipos_suscripcion(id)
);
CREATE TABLE tokens_verificacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    tipo ENUM('verificacion', 'recuperacion') NOT NULL,
    expiracion DATETIME NOT NULL,
    utilizado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profesor_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id)
);
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    curso_id INT NOT NULL,
    fecha_inscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'completado', 'cancelado') DEFAULT 'activo',
    FOREIGN KEY (estudiante_id) REFERENCES usuarios(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    UNIQUE KEY unique_inscripcion (estudiante_id, curso_id)
);
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);
CREATE TABLE roles_permisos (
    rol_id INT NOT NULL,
    permiso_id INT NOT NULL,
    PRIMARY KEY (rol_id, permiso_id),
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (permiso_id) REFERENCES permisos(id)
);
CREATE TABLE logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion VARCHAR(255) NOT NULL,
    tabla_afectada VARCHAR(100),
    registro_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE material_didactico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    profesor_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    archivo_nombre VARCHAR(255),
    archivo_ruta VARCHAR(500),
    tipo_archivo ENUM('pdf', 'doc', 'docx', 'ppt', 'pptx', 'image', 'video', 'otro'),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id)
);
CREATE TABLE historial_passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_historial_usuario (usuario_id)
);
CREATE TABLE codigos_2fa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    expiracion DATETIME NOT NULL,
    utilizado TINYINT(1) DEFAULT 0,
    expirado TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_codigos_expiracion (expiracion),
    INDEX idx_codigos_usuario (usuario_id),
    INDEX idx_codigos_validos (usuario_id, expiracion, utilizado, expirado)
);
CREATE TABLE sesiones_activas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_ultima_actividad DATETIME DEFAULT CURRENT_TIMESTAMP,
    activa TINYINT(1) DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_sesiones_usuario (usuario_id, activa),
    INDEX idx_sesiones_activas (activa, fecha_ultima_actividad)
);


INSERT INTO usuarios (nombre, email, password, rol_id, verificado) 
VALUES ('Josue Claros Roca','clarosrocajosue@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,1);
INSERT INTO usuarios (nombre, email, password, rol_id, verificado) 
VALUES ('Estudiante1','estudiante1@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',3,1);
INSERT INTO usuarios (nombre, email, password, rol_id, verificado) 
VALUES ('Profesor 1','profesor1@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2,1);
INSERT INTO permisos (nombre, descripcion) VALUES
('crear_usuarios', 'Permite crear nuevos usuarios'),
('editar_usuarios', 'Permite editar usuarios existentes'),
('eliminar_usuarios', 'Permite eliminar usuarios'),
('gestionar_cursos', 'Permite gestionar todos los aspectos de cursos');
DELIMITER //
CREATE PROCEDURE sp_crear_usuario(
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_rol_id INT,
    IN p_verificado TINYINT(1)
)
BEGIN
    INSERT INTO usuarios (nombre, email, password, rol_id, verificado)
    VALUES (p_nombre, p_email, p_password, p_rol_id, p_verificado);
    SELECT LAST_INSERT_ID() AS nuevo_id;
END //
DELIMITER ;
DELIMITER //
CREATE TRIGGER after_usuario_update
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF OLD.password <> NEW.password THEN
        INSERT INTO logs_auditoria (usuario_id, accion, tabla_afectada, registro_id)
        VALUES (NEW.id, 'ACTUALIZACIÓN_CONTRASEÑA', 'usuarios', NEW.id);
    END IF;
    
    IF OLD.email <> NEW.email THEN
        INSERT INTO logs_auditoria (usuario_id, accion, tabla_afectada, registro_id)
        VALUES (NEW.id, 'ACTUALIZACIÓN_EMAIL', 'usuarios', NEW.id);
    END IF;
END //
DELIMITER ;
DELIMITER //
CREATE PROCEDURE limpiar_codigos_expirados(IN p_user_id INT)
BEGIN
    UPDATE codigos_2fa 
    SET utilizado = 1, expirado = 1 
    WHERE usuario_id = p_user_id 
    AND expiracion <= NOW() 
    AND utilizado = 0;
END //
DELIMITER ;
SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS auto_cleanup_expired_codes
ON SCHEDULE EVERY 30 SECOND
DO
    UPDATE codigos_2fa 
    SET utilizado = 1, expirado = 1 
    WHERE expiracion <= NOW() 
    AND utilizado = 0;