-- Crea la base de datos para el proyecto si no existe
CREATE DATABASE IF NOT EXISTS minimarket_vv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE minimarket_vv;

-- Tabla para almacenar opiniones de clientes
CREATE TABLE IF NOT EXISTS opiniones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  motivo VARCHAR(50) DEFAULT 'otro',
  opinion TEXT NOT NULL,
  creado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de ejemplo para usuarios (registro/login)
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  creado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
