-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 01:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE dis115;
USE dis115;


--
-- Database: `dis115`
--

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(7) NOT NULL,
  `categoria_nombre` varchar(50) NOT NULL,
  `categoria_tipo` varchar(50) NOT NULL,
  `categoria_ubicacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_nombre`, `categoria_tipo`, `categoria_ubicacion`) VALUES
(1, 'UNO DOS TRES', '', 'Es una categoria');

-- --------------------------------------------------------

--
-- Table structure for table `herramienta`
--

CREATE TABLE `herramienta` (
  `herramienta_id` int(20) NOT NULL,
  `herramienta_codigo` varchar(70) NOT NULL,
  `herramienta_nombre` varchar(70) NOT NULL,
  `herramienta_precio` decimal(30,2) NOT NULL,
  `herramienta_stock` int(25) NOT NULL,
  `herramienta_foto` varchar(500) NOT NULL,
  `categoria_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `herramienta`
--

INSERT INTO `herramienta` (`herramienta_id`, `herramienta_codigo`, `herramienta_nombre`, `herramienta_precio`, `herramienta_stock`, `herramienta_foto`, `categoria_id`, `usuario_id`) VALUES
(1, 'Yo', 'Canal', 4.00, 5, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(20) NOT NULL,
  `producto_codigo` varchar(70) NOT NULL,
  `producto_nombre` varchar(70) NOT NULL,
  `producto_precio` decimal(30,2) NOT NULL,
  `producto_stock` int(25) NOT NULL,
  `producto_foto` varchar(500) NOT NULL,
  `categoria_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`producto_id`, `producto_codigo`, `producto_nombre`, `producto_precio`, `producto_stock`, `producto_foto`, `categoria_id`, `usuario_id`) VALUES
(1, '3ha', 'kjdalshf', 23.00, 4, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reporteh`
--

CREATE TABLE `reporteh` (
  `reporteh_id` int(7) NOT NULL,
  `reporteh_tipo` varchar(30) NOT NULL,
  `reporteh_persona` varchar(40) NOT NULL,
  `reporteh_detalles` varchar(200) NOT NULL,
  `herramienta_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `reporteh`
--

INSERT INTO `reporteh` (`reporteh_id`, `reporteh_tipo`, `reporteh_persona`, `reporteh_detalles`, `herramienta_id`, `usuario_id`) VALUES
(2, 'robo', '1', 'GSF', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reportep`
--

CREATE TABLE `reportep` (
  `reportep_id` int(7) NOT NULL,
  `reportep_tipo` varchar(30) NOT NULL,
  `reportep_persona` varchar(40) NOT NULL,
  `reportep_detalles` varchar(200) NOT NULL,
  `reportep_cantidad` int(11) NOT NULL,
  `producto_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `reportep`
--

INSERT INTO `reportep` (`reportep_id`, `reportep_tipo`, `reportep_persona`, `reportep_detalles`, `reportep_cantidad`, `producto_id`, `usuario_id`) VALUES
(5, 'robo', '1', 'TREDSF', 342, 1, 1),
(6, 'robo', '1', 'TREDSF', 342, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL,
  `usuario_tipo` varchar(70) CHARACTER SET ucs2 COLLATE ucs2_spanish2_ci NOT NULL,
  `usuario_nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `usuario_apellido` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `usuario_usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `usuario_clave` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `usuario_email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_tipo`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_email`) VALUES
(1, 'admin', 'Administrador', 'Principal', 'Administrador', '$2y$10$EPY9LSLOFLDDBriuJICmFOqmZdnDXxLJG8YFbog5LcExp77DBQvgC', '');

CREATE TABLE `mantenimiento` (
  `mantenimiento_id` int(20) NOT NULL,
  `mantenimiento_persona` varchar(70) NOT NULL,
  `mantenimiento_detalles` varchar(70) NOT NULL,
  `mantenimiento_fecha1` DATE NOT NULL,
  `mantenimiento_fecha2` DATE NOT NULL,  
  `herramienta_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indexes for table `herramienta`
--
ALTER TABLE `herramienta`
  ADD PRIMARY KEY (`herramienta_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `reporteh`
--
ALTER TABLE `reporteh`
  ADD PRIMARY KEY (`reporteh_id`),
  ADD KEY `herramienta_id` (`herramienta_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `reportep`
--
ALTER TABLE `reportep`
  ADD PRIMARY KEY (`reportep_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);


ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`mantenimiento_id`),
  ADD KEY `herramienta_id` (`herramienta_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `herramienta`
--
ALTER TABLE `herramienta`
  MODIFY `herramienta_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reporteh`
--
ALTER TABLE `reporteh`
  MODIFY `reporteh_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reportep`
--
ALTER TABLE `reportep`
  MODIFY `reportep_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `mantenimiento`
  MODIFY `mantenimiento_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `herramienta`
--
ALTER TABLE `herramienta`
  ADD CONSTRAINT `herramienta_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `herramienta_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Constraints for table `reporteh`
--
ALTER TABLE `reporteh`
  ADD CONSTRAINT `reporteH_ibfk_1` FOREIGN KEY (`herramienta_id`) REFERENCES `herramienta` (`herramienta_id`),
  ADD CONSTRAINT `reporteH_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Constraints for table `reportep`
--
ALTER TABLE `reportep`
  ADD CONSTRAINT `reporteP_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`),
  ADD CONSTRAINT `reporteP_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;


ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`herramienta_id`) REFERENCES `herramienta` (`herramienta_id`),
  ADD CONSTRAINT `mantenimiento_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
