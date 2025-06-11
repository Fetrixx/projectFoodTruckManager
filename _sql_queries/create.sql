-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS foodtruck_manager;

-- Usar la base de datos
USE foodtruck_manager;

-- Tabla para usuarios del sistema (login)
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  admin BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

-- Tabla individual del alumno 
CREATE TABLE alumno_emedina (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombres VARCHAR(100) NOT NULL,
  cedula VARCHAR(20) NOT NULL UNIQUE,
  correo VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Tabla para food trucks
CREATE TABLE foodtrucks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  ubicacion VARCHAR(255),
  lat DECIMAL(10,7),
  lng DECIMAL(10,7),
  horario_apertura TIME,
  horario_cierre TIME,
  imagen VARCHAR(255)
) ENGINE=InnoDB;

-- Tabla para menús de cada food truck
CREATE TABLE menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  foodtruck_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255).
  FOREIGN KEY (foodtruck_id) REFERENCES foodtrucks(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla para reservas (pedidos)
CREATE TABLE reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  foodtruck_id INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (foodtruck_id) REFERENCES foodtrucks(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla detalle de pedidos (items de cada reserva)
CREATE TABLE reserva_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reserva_id INT NOT NULL,
  menu_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla para reviews de food trucks por usuarios
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  foodtruck_id INT NOT NULL,
  rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comentario TEXT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (foodtruck_id) REFERENCES foodtrucks(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla para favoritos (usuarios que marcan food trucks)
CREATE TABLE favoritos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  foodtruck_id INT NOT NULL,
  fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY usuario_foodtruck (usuario_id, foodtruck_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (foodtruck_id) REFERENCES foodtrucks(id) ON DELETE CASCADE
) ENGINE=InnoDB;