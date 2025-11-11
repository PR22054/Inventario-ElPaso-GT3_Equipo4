-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2025 a las 02:27:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dis115-4`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(7) NOT NULL,
  `categoria_nombre` varchar(50) NOT NULL,
  `categoria_tipo` varchar(50) NOT NULL,
  `categoria_ubicacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_nombre`, `categoria_tipo`, `categoria_ubicacion`) VALUES
(1, 'Herramientas Manuales', 'Herramienta', 'Estante A1'),
(2, 'Herramientas Eléctricas', 'Herramienta', 'Estante A2'),
(3, 'Lubricantes y Aceites', 'Producto', 'Estante B1'),
(4, 'Filtros y Repuestos', 'Producto', 'Estante B2'),
(5, 'Baterías y Electricidad', 'Producto', 'Estante C1'),
(6, 'Neumáticos y Llantas', 'Producto', 'Estante C2'),
(7, 'Seguridad y Consumibles', 'Producto', 'Estante D1'),
(8, 'Diagnóstico y Medición', 'Herramienta', 'Estante A3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `herramienta`
--

CREATE TABLE `herramienta` (
  `herramienta_id` int(20) NOT NULL,
  `herramienta_codigo` varchar(70) NOT NULL,
  `herramienta_nombre` varchar(70) NOT NULL,
  `herramienta_descripcion` varchar(300) NOT NULL,
  `herramienta_precio` decimal(30,2) NOT NULL,
  `herramienta_stock` int(25) NOT NULL,
  `stock_maximo` int(25) DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT NULL,
  `herramienta_foto` varchar(500) NOT NULL,
  `necesita_mantenimiento` tinyint(1) DEFAULT 0,
  `estado_alarma` tinyint(1) DEFAULT 0,
  `requiere_mantenimiento` tinyint(1) DEFAULT 0,
  `categoria_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `herramienta`
--

INSERT INTO `herramienta` (`herramienta_id`, `herramienta_codigo`, `herramienta_nombre`, `herramienta_descripcion`, `herramienta_precio`, `herramienta_stock`, `stock_maximo`, `stock_minimo`, `herramienta_foto`, `necesita_mantenimiento`, `estado_alarma`, `requiere_mantenimiento`, `categoria_id`, `usuario_id`) VALUES
(1, 'HM001', 'Martillo', 'Herramienta manual de percusión con cabeza metálica y mango ergonómico, ideal para trabajos en general.', 15.00, 10, 5, 2, 'Martillo_50.png', 0, 0, 0, 1, 1),
(3, 'HE001', 'Taladro Eléctrico Bosch', 'Taladro eléctrico de marca Bosch con motor potente, velocidad variable y percutor. Perfecto para perforar madera, metal y concreto en aplicaciones profesionales.', 120.00, 5, 5, 2, 'Taladro_Eléctrico_Bosch_98.png', 1, 2, 1, 1, 1),
(4, 'HE002', 'Sierra Circular', 'Sierra circular eléctrica de alto rendimiento para cortes rectos precisos en materiales derivados. Incluye guía de corte y protección de seguridad.', 250.00, 3, 4, 2, 'Sierra_Circular_52.png', 1, 2, 1, 1, 1),
(5, 'DI001', 'Scanner OBD2 Profesional', 'Escáner automotriz profesional para diagnóstico de vehículos mediante puerto OBD2. Lee y borra códigos de falla, muestra datos en tiempo real y compatible con múltiples protocolos.', 180.00, 3, 6, 4, 'Scanner_OBD2_Profesional_7.png', 1, 8, 1, 1, 1),
(6, 'DI002', 'Multímetro Digital', 'Instrumento de medición digital para verificar voltaje, corriente y resistencia eléctrica. Pantalla LCD de fácil lectura y protección contra sobrecargas.', 45.00, 4, 5, 6, 'Multímetro_Digital_57.png', 0, 8, 0, 0, 1),
(7, 'HE003', 'Gato Hidráulico 3T', 'Gato hidráulico tipo botella con capacidad de 3 toneladas para elevación de vehículos. Sistema de bombeo manual y válvula de seguridad integrada.', 180.00, 2, 3, 5, 'Gato_Hidráulico_3T_1.png', 1, 2, 1, 1, 1),
(8, 'HE004', 'Compresor de Aire 100L', 'Compresor de aire con tanque de 100 litros, motor de alta potencia y regulador de presión. Ideal para taller mecánico, pintura y herramientas neumáticas.', 300.00, 1, 3, 1, 'Compresor_de_Aire_100L_43.png', 1, 2, 1, 1, 1),
(9, 'HE005', 'Elevador Hidráulico 2P', 'Elevador hidráulico de dos postes para vehículos, capacidad profesional. Sistema de seguridad con traba automática y brazos ajustables para diferentes tipos de automóviles.', 800.00, 2, 5, 3, 'Elevador_Hidráulico_2P_100.jpg', 1, 2, 1, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `mantenimiento_id` int(20) NOT NULL,
  `mantenimiento_persona` varchar(70) NOT NULL,
  `mantenimiento_detalles` varchar(255) NOT NULL,
  `mantenimiento_fecha1` date NOT NULL,
  `mantenimiento_fecha2` date NOT NULL,
  `herramienta_id` int(20) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mantenimiento`
--

INSERT INTO `mantenimiento` (`mantenimiento_id`, `mantenimiento_persona`, `mantenimiento_detalles`, `mantenimiento_fecha1`, `mantenimiento_fecha2`, `herramienta_id`, `usuario_id`) VALUES
(1, 'Erick Perez', 'Revisión de taladro Bosch, cambio de broca y engrase', '2025-09-01', '2025-09-05', 3, 6),
(2, 'Juan Antonio Rivas Ruiz', 'Sierra circular: afilar hoja y lubricar engranajes', '2025-09-10', '2025-09-12', 4, 7),
(3, 'Erick Perez', 'Gato hidráulico: revisar fugas de aceite', '2025-09-15', '2025-09-17', 7, 6),
(4, 'Juan Antonio Rivas Ruiz', 'Compresor de aire: limpieza filtro y lubricación', '2025-09-20', '2025-09-22', 8, 7),
(5, 'Erick Perez', 'Martillo: reemplazo de mango', '2025-09-25', '2025-09-26', 1, 6),
(6, 'Juan Antonio Rivas Ruiz', 'Llave inglesa: ajuste y verificación de calibración', '2025-09-28', '2025-09-29', 2, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(20) NOT NULL,
  `producto_codigo` varchar(70) NOT NULL,
  `producto_nombre` varchar(70) NOT NULL,
  `producto_precio` decimal(30,2) NOT NULL,
  `producto_stock` int(25) NOT NULL,
  `stock_maximo` int(25) DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT NULL,
  `producto_foto` varchar(500) NOT NULL,
  `categoria_id` int(7) NOT NULL,
  `usuario_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `producto_codigo`, `producto_nombre`, `producto_precio`, `producto_stock`, `stock_maximo`, `stock_minimo`, `producto_foto`, `categoria_id`, `usuario_id`) VALUES
(1, 'P001', 'Aceite Motor 10W40', 15.00, 50, 30, 10, 'Aceite_Motor_10W40_55.png', 3, 1),
(2, 'P002', 'Filtro de Aceite', 8.00, 30, 30, 10, 'Filtro_de_Aceite_83.png', 4, 1),
(3, 'P003', 'Batería 12V 60Ah', 120.00, 10, 15, 3, 'Batería_12V_60Ah_94.png', 5, 1),
(4, 'P004', 'Neumático 195/65 R15', 75.00, 2, 20, 5, 'Neumático_195_65_R15_51.png', 6, 1),
(5, 'P005', 'Neumático 205/55 R16', 85.00, 15, 20, 5, 'Neumático_205_55_R16_42.png', 6, 1),
(6, 'P006', 'Filtro de Combustible', 12.00, 5, 30, 10, 'Filtro_de_Combustible_31.png', 4, 1),
(7, 'P007', 'Líquido de Frenos DOT4', 8.50, 30, 25, 10, 'Líquido_de_Frenos_DOT4_24.png', 3, 1),
(8, 'P008', 'Guantes de Seguridad', 5.50, 40, 50, 15, 'Guantes_de_Seguridad_89.png', 7, 1),
(9, 'P009', 'Cables de Arranque 400A', 25.00, 10, 10, 4, 'Cables_de_Arranque_400A_68.png', 5, 1),
(10, 'P010', 'Bombilla Halógena H4', 5.00, 50, 50, 5, 'Bombilla_Halógena_H4_41.png', 5, 1),
(11, 'P011', 'Aceite Transmisión 75W90', 18.00, 30, 50, 15, 'Aceite_Transmisión_75W90_8.png', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporteh`
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
-- Volcado de datos para la tabla `reporteh`
--

INSERT INTO `reporteh` (`reporteh_id`, `reporteh_tipo`, `reporteh_persona`, `reporteh_detalles`, `herramienta_id`, `usuario_id`) VALUES
(1, 'daño', 'Erick Perez', 'Taladro Bosch dejó de funcionar', 3, 6),
(2, 'robo', 'Juan Antonio Rivas Ruiz', 'Multímetro digital desaparecido', 6, 7),
(3, 'daño', 'Erick Perez', 'Sierra circular con hoja desgastada', 4, 6),
(4, 'daño', 'Juan Antonio Rivas Ruiz', 'Gato hidráulico con fuga de aceite', 7, 7),
(5, 'robo', 'Erick Perez', 'Scanner OBD2 robado', 5, 6),
(6, 'daño', 'Juan Antonio Rivas Ruiz', 'Compresor de aire no arranca', 8, 7),
(7, 'robo', 'Erick Perez', 'Martillo con mango flojo', 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportep`
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
-- Volcado de datos para la tabla `reportep`
--

INSERT INTO `reportep` (`reportep_id`, `reportep_tipo`, `reportep_persona`, `reportep_detalles`, `reportep_cantidad`, `producto_id`, `usuario_id`) VALUES
(1, 'daño', 'Alexandra Galvez', 'Aceite motor derramado en estante', 5, 1, 4),
(2, 'robo', 'Fernando Cortez', 'Se llevaron filtro de aceite', 2, 2, 5),
(3, 'daño', 'Fernando Cortez', 'Batería golpeada y abollada', 1, 3, 5),
(4, 'pérdida', 'Alexandra Galvez', 'Neumático 195/65 R15 extraviado', 1, 4, 4),
(5, 'robo', 'Fernando Cortez', 'Guantes de seguridad faltantes', 10, 5, 5),
(6, 'daño', 'Alexandra Galvez', 'Líquido de frenos derramado', 3, 6, 4),
(7, 'pérdida', 'Fernando Cortez', 'Filtro de combustible perdido', 2, 7, 5),
(8, 'robo', 'Alexandra Galvez', 'Aceite de transmisión 75W90 robado', 2, 8, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_producto`
--

CREATE TABLE `salida_producto` (
  `salida_id` int(7) NOT NULL,
  `producto_id` int(20) NOT NULL,
  `usuario_id` int(10) NOT NULL,
  `tipo_salida` enum('Reparación','Venta') NOT NULL,
  `orden_trabajo` varchar(50) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_salida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salida_producto`
--

INSERT INTO `salida_producto` (`salida_id`, `producto_id`, `usuario_id`, `tipo_salida`, `orden_trabajo`, `cantidad`, `fecha_salida`) VALUES
(1, 1, 3, 'Reparación', 'OT-1001', 5, '2025-09-30 09:15:00'),
(2, 2, 4, 'Reparación', 'OT-1002', 3, '2025-09-30 10:30:00'),
(3, 3, 6, 'Venta', NULL, 2, '2025-09-30 11:00:00'),
(4, 4, 5, 'Venta', NULL, 4, '2025-09-30 12:20:00'),
(5, 5, 6, 'Reparación', 'OT-1003', 2, '2025-09-30 13:45:00'),
(6, 6, 3, 'Reparación', 'OT-1004', 6, '2025-09-30 14:10:00'),
(7, 7, 5, 'Venta', NULL, 5, '2025-09-30 15:25:00'),
(8, 8, 4, 'Reparación', 'OT-1005', 10, '2025-09-30 16:00:00'),
(9, 9, 3, 'Reparación', 'OT-1006', 2, '2025-09-30 16:30:00'),
(10, 10, 6, 'Venta', NULL, 8, '2025-09-30 17:00:00'),
(11, 11, 5, 'Reparación', 'OT-1007', 3, '2025-09-30 17:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
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
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_tipo`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_email`) VALUES
(1, 'admin', 'Administrador', 'Principal', 'Administrador', 'Administrador', 'administrador@ues.edu.sv'),
(2, 'admin', 'Jose', 'Lemus', 'LR07038', 'LR07038', 'lr07038@ues.edu.sv'),
(3, 'admin', 'Jose Luis', 'Antonio', 'AV22025', 'AV22025', 'av22025@ues.edu.sv'),
(4, 'empleado', 'Alexandra', 'Galvez', 'GR22078', 'GR22078', 'gr22078@ues.edu.sv'),
(5, 'empleado', 'Fernando', 'Cortez', 'CM21018', 'CM21018', 'cm21018@ues.edu.sv'),
(6, 'mecanico', 'Erick', 'Perez', 'PR22054', 'PR22054', 'pr22054@ues.edu.sv'),
(7, 'mecanico', 'Juan Antonio', 'Rivas Ruiz', 'RR20253', 'RR20253', 'rr20253@ues.edu.sv');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `herramienta`
--
ALTER TABLE `herramienta`
  ADD PRIMARY KEY (`herramienta_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`mantenimiento_id`),
  ADD KEY `categoria_id` (`herramienta_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `reporteh`
--
ALTER TABLE `reporteh`
  ADD PRIMARY KEY (`reporteh_id`),
  ADD KEY `herramienta_id` (`herramienta_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `reportep`
--
ALTER TABLE `reportep`
  ADD PRIMARY KEY (`reportep_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `salida_producto`
--
ALTER TABLE `salida_producto`
  ADD PRIMARY KEY (`salida_id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `herramienta`
--
ALTER TABLE `herramienta`
  MODIFY `herramienta_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `mantenimiento_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `reporteh`
--
ALTER TABLE `reporteh`
  MODIFY `reporteh_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reportep`
--
ALTER TABLE `reportep`
  MODIFY `reportep_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `salida_producto`
--
ALTER TABLE `salida_producto`
  MODIFY `salida_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
