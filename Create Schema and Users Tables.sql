-- Creamos el Schema/Database que vamos a usar
CREATE SCHEMA `file_manager_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- Para en las siguientes ejecuciones, usamos este schema
USE `file_manager_system`;

-- Se crea la tabla usuarios
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password_encrypted` varchar(128) NOT NULL,
  `password_salt` varchar(64) NOT NULL,
  `nombre` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(1) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_hora_registro` datetime NOT NULL,
  `es_admin` tinyint NOT NULL DEFAULT 0,
  `activo` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creamos la tabla archivos, para guardar los registros de los archivos
CREATE TABLE `archivos` (
	`id` int NOT NULL AUTO_INCREMENT,
    `descripcion` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    `nombre_archivo` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `extension` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `nombre_archivo_guardado` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `tamaño` bigint NOT NULL,
    `hash_sha256` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `fecha_subido` datetime,
    `usuario_subio_id` int NOT NULL,
    `fecha_borrado` datetime NULL,
    `usuario_borro_id` int NULL,
    `cant_descargas` int DEFAULT 0 NOT NULL,
    `es_publico` tinyint NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creación de la tabla archivos_log_general donde se guardará el registro general de las
-- acciones que se hagan sobre los archivos, esto para tener un registro detallado del 
-- comportamiento de los archivos y para auditorias
CREATE TABLE `archivos_log_general` (
	`id` int NOT NULL AUTO_INCREMENT,
    `archivo_id` int NOT NULL,
    `usuario_id` int NOT NULL,
    `fecha_hora` datetime NOT NULL,
	`accion_realizada` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `ip_realiza_operacion` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archivo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_agregado DATETIME NOT NULL,
    FOREIGN KEY (archivo_id) REFERENCES archivos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);