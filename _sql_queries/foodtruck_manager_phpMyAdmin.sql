-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 08:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodtruck_manager`
--
CREATE DATABASE IF NOT EXISTS `foodtruck_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `foodtruck_manager`;

-- --------------------------------------------------------

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `foodtruck_id` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `foodtruck_id`, `fecha_agregado`) VALUES
(1, 1, 1, '2025-06-05 16:47:25'),
(2, 1, 2, '2025-06-05 16:47:25'),
(14, 2, 1, '2025-06-05 19:29:49'),
(17, 2, 2, '2025-06-10 23:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `foodtrucks`
--

DROP TABLE IF EXISTS `foodtrucks`;
CREATE TABLE `foodtrucks` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `foodtrucks`
--

INSERT INTO `foodtrucks` (`id`, `nombre`, `descripcion`, `ubicacion`, `lat`, `lng`, `horario_apertura`, `horario_cierre`, `imagen`) VALUES
(1, 'Tacos El Güero', 'Los mejores tacos de la ciudad con receta familiar', 'Frente al edificio principal', '-0.2298500', '-78.5249500', '10:00:00', '22:00:00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQuO46A4sp8idqUqiRkAZGlp4WZQI3z06-w7Q&s'),
(2, 'Burger Paradise', 'Hamburguesas gourmet con ingredientes premium', 'Junto a la fuente central', '-0.2300000', '-78.5251000', '11:00:00', '23:00:00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpJO53oCmrir9go_lj5LKZ29df_urEGiJ4TQ&s'),
(3, 'Sushi Express', 'Sushi fresco preparado al momento', 'Área de comida internacional', '-0.2297000', '-78.5248000', '12:00:00', '21:00:00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRoUG8geQyokyyEipj-fv24-4dI-M8SGKL5SQ&s');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `foodtruck_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `foodtruck_id`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
(1, 1, 'Taco Especial', 'Taco de pastor con piña', '3.99', 'https://www.pequerecetas.com/wp-content/uploads/2023/09/tacos-al-pastor.jpeg'),
(2, 1, 'Quesadilla', 'Quesadilla de flor de calabaza', '4.50', 'https://www.mexicoenmicocina.com/wp-content/uploads/2017/05/quesadillas-de-flor-de-calabaza-receta-3.jpg'),
(3, 2, 'Clásica', 'Hamburguesa con queso y tocino', '6.91', 'https://media.istockphoto.com/id/520215281/es/foto/tocino-hamburguesa.jpg?s=612x612&w=0&k=20&c=1J3mCgtEhLj2zyTzBC2iprBDaI2yjg0Z0gOuFYXt-oE='),
(4, 2, 'Veggie', 'Hamburguesa vegetariana con portobello', '7.50', 'https://www.unileverfoodsolutions.com.mx/dam/global-ufs/mcos/NOLA/calcmenu/recipes/MX-recipes/general/falsa-hamburguesa-de-portobello/main-header.jpg'),
(5, 3, 'Roll California', 'Roll de cangrejo, pepino y aguacate', '8.99', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWG1TxIBKpwfrPEt6kL6nvU6PCccsMyo17xg&s'),
(6, 3, 'Sashimi Variado', 'Selección de 10 piezas de sashimi', '12.50', 'https://okaerisushibar.com/wp-content/uploads/2021/03/sashimi-de-salmon-10-piezas.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `foodtruck_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `foodtruck_id`, `fecha`, `hora`, `total`, `estado`, `fecha_creacion`) VALUES
(1, 1, 1, '2024-05-20', '12:00:00', '21.47', 'confirmada', '2025-06-05 16:47:03'),
(10, 2, 3, '2025-06-05', '19:35:30', '1105.77', 'confirmada', '2025-06-05 23:35:30'),
(12, 2, 1, '2025-06-05', '19:35:53', '1652.88', 'confirmada', '2025-06-05 23:35:53'),
(13, 2, 2, '2025-06-05', '20:14:03', '6.99', 'pendiente', '2025-06-06 00:14:03'),
(14, 2, 2, '2025-06-05', '20:21:37', '6.99', 'pendiente', '2025-06-06 00:21:37'),
(15, 2, 3, '2025-06-06', '19:26:55', '17.98', 'pendiente', '2025-06-06 23:26:55'),
(16, 2, 2, '2025-06-06', '19:29:07', '20.97', 'pendiente', '2025-06-06 23:29:07'),
(17, 2, 2, '2025-06-06', '19:31:11', '20.97', 'pendiente', '2025-06-06 23:31:11'),
(18, 2, 2, '2025-06-06', '20:46:51', '6.99', 'pendiente', '2025-06-07 00:46:51'),
(19, 2, 3, '2025-06-08', '09:02:02', '25.00', 'pendiente', '2025-06-08 13:02:02'),
(20, 1, 2, '2025-06-08', '09:59:46', '849.93', 'pendiente', '2025-06-08 13:59:46'),
(21, 2, 3, '2025-06-10', '19:59:54', '17.98', 'pendiente', '2025-06-10 23:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `reserva_items`
--

DROP TABLE IF EXISTS `reserva_items`;
CREATE TABLE `reserva_items` (
  `id` int(11) NOT NULL,
  `reserva_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reserva_items`
--

INSERT INTO `reserva_items` (`id`, `reserva_id`, `menu_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 3, '3.99'),
(2, 1, 2, 2, '4.50'),
(9, 10, 5, 123, '8.99'),
(11, 12, 2, 2, '4.50'),
(12, 12, 1, 412, '3.99'),
(13, 13, 3, 1, '6.99'),
(14, 14, 3, 1, '6.99'),
(15, 15, 5, 2, '8.99'),
(16, 16, 3, 3, '6.99'),
(17, 17, 3, 3, '6.99'),
(18, 18, 3, 1, '6.99'),
(19, 19, 6, 2, '12.50'),
(20, 20, 3, 123, '6.91'),
(21, 21, 5, 2, '8.99');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `foodtruck_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `usuario_id`, `foodtruck_id`, `rating`, `comentario`, `fecha`) VALUES
(1, 1, 1, 5, '¡Los mejores tacos que he probado! La salsa es increíble.', '2025-06-05 16:47:18'),
(2, 2, 1, 4, 'Buenos tacos, pero las porciones podrían ser más generosas.', '2025-06-05 16:47:18'),
(3, 3, 2, 5, 'Las mejores hamburguesas de la ciudad. Las papas son espectaculares.', '2025-06-05 16:47:18'),
(4, 4, 2, 3, 'Buena calidad pero un poco caro para lo que ofrecen.', '2025-06-05 16:47:18'),
(5, 5, 3, 4, 'Sushi fresco y bien preparado. Buen servicio.', '2025-06-05 16:47:18'),
(6, 2, 2, 2, 'estan ok?...', '2025-06-05 18:30:13'),
(7, 2, 2, 5, 'estan buenos', '2025-06-05 18:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_registro`, `admin`) VALUES
(1, 'admin', 'admin@mail.com', 'admin', '2025-06-05 14:44:48', 1),
(2, 'Elias Medina', 'elias.medina@mail.com', 'elias', '2025-06-05 16:32:00', 0),
(3, 'Ana López', 'ana.lopez@example.com', 'password123', '2025-06-05 16:46:08', 0),
(4, 'Carlos Ramírez', 'carlos.ramirez@example.com', 'qwerty', '2025-06-05 16:46:08', 0),
(5, 'María Fernández', 'maria.fernandez@example.com', 'abc123', '2025-06-05 16:46:08', 0),
(6, 'Juan Pérez', 'juan.perez@example.com', 'pass1234', '2025-06-05 16:46:08', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_foodtruck` (`usuario_id`,`foodtruck_id`),
  ADD KEY `foodtruck_id` (`foodtruck_id`);

--
-- Indexes for table `foodtrucks`
--
ALTER TABLE `foodtrucks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodtruck_id` (`foodtruck_id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `foodtruck_id` (`foodtruck_id`);

--
-- Indexes for table `reserva_items`
--
ALTER TABLE `reserva_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserva_id` (`reserva_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `foodtruck_id` (`foodtruck_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `foodtrucks`
--
ALTER TABLE `foodtrucks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reserva_items`
--
ALTER TABLE `reserva_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`foodtruck_id`) REFERENCES `foodtrucks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`foodtruck_id`) REFERENCES `foodtrucks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`foodtruck_id`) REFERENCES `foodtrucks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reserva_items`
--
ALTER TABLE `reserva_items`
  ADD CONSTRAINT `reserva_items_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reserva_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`foodtruck_id`) REFERENCES `foodtrucks` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
