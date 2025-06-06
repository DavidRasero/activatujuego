-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2025 a las 17:27:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `activatujuego`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centrodeportivo`
--

CREATE TABLE `centrodeportivo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centrodeportivo`
--

INSERT INTO `centrodeportivo` (`id`, `nombre`, `direccion`) VALUES
(1, 'Polideportivo Norte', 'Calle Mayor 10'),
(2, 'Club Padel Sur', 'Av. Andalucía 88'),
(4, 'Instalaciones Deportivas Charco de la Pava', 'Parque Vega de, Av. de Coria'),
(5, 'Club Deportivo Triana Sport Sevilla', 'Instalaciones Deportivas Municipales Arjona');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deporte`
--

CREATE TABLE `deporte` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `numero_jugadores` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `deporte`
--

INSERT INTO `deporte` (`id`, `nombre`, `descripcion`, `numero_jugadores`, `imagen`) VALUES
(12, 'Voleibol', 'El voleibol es un deporte de equipo que se juega entre dos equipos de seis jugadores, separados por una red, con el objetivo de pasar el balón por encima de la red y que toque el suelo en el campo contrario, evitando que caiga en el propio. El juego se basa en golpear el balón con cualquier parte del cuerpo, con un máximo de tres toques por equipo antes de pasar la pelota al otro lado de la red.', 6, 'deporte_682f3ea80ed81.jpg'),
(13, 'Pádel', 'El pádel es un deporte de raqueta jugado por parejas en una cancha rectangular con paredes laterales y traseras. Se juega con una pelota y una raqueta pequeña llamada pala. El objetivo es hacer rebotar la pelota en el campo contrario y que el adversario no pueda devolverla.', 4, 'deporte_683042034555f.jpg'),
(14, 'Fútbol 7', 'El Fútbol 7 es una variante del Fútbol 11 donde dos equipos de siete jugadores (uno de ellos portero) se enfrentan en un campo de menor tamaño, generalmente entre 50 y 65 metros de largo y 30 a 45 metros de ancho. La duración de los partidos suele ser de dos tiempos de 25 minutos, sin parada de reloj, con un descanso de 5 minutos entre ellos.', 14, 'deporte_682f3f03e2aa4.png'),
(15, 'Golf', 'El golf es un deporte que consiste en embocar una bola con un palo en un campo al aire libre, utilizando el menor número de golpes posible en cada uno de los hoyos. El objetivo principal es completar el recorrido de 18 hoyos con el menor número de golpes total.', 4, 'deporte_682f3f2b8ca77.jpg'),
(16, 'Ajedrez', 'El ajedrez es un juego de estrategia para dos jugadores en un tablero de 64 casillas. Cada jugador tiene 16 piezas, que se mueven de forma diferente, con el objetivo de dar jaque mate al rey del oponente.', 2, 'deporte_682f3f5c53594.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `organizador_id` int(11) NOT NULL,
  `deporte_id` int(11) NOT NULL,
  `centro_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` enum('activo','finalizado','completo') DEFAULT 'activo',
  `jugadores_aceptados` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id`, `organizador_id`, `deporte_id`, `centro_id`, `fecha`, `estado`, `jugadores_aceptados`) VALUES
(36, 29, 14, 1, '2025-05-24 17:20:00', 'activo', 1),
(38, 29, 16, 4, '2025-05-25 21:21:00', 'finalizado', 2),
(39, 29, 12, 5, '2025-06-01 00:00:00', 'activo', 1),
(40, 29, 14, 5, '2025-05-24 17:21:00', 'activo', 1),
(41, 32, 16, 5, '2025-05-31 08:27:00', 'finalizado', 2),
(42, 32, 13, 2, '2025-05-25 17:22:00', 'activo', 0),
(43, 32, 15, 1, '2025-05-31 17:22:00', 'activo', 1),
(44, 32, 14, 1, '2025-05-24 17:22:00', 'activo', 1),
(45, 32, 13, 2, '2025-06-08 11:26:00', 'activo', 1),
(46, 29, 16, 2, '2027-07-23 12:00:00', 'activo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`id`, `usuario_id`, `evento_id`, `estado`) VALUES
(67, 29, 36, 'aceptada'),
(69, 29, 38, 'aceptada'),
(70, 29, 39, 'aceptada'),
(71, 29, 40, 'aceptada'),
(72, 32, 41, 'aceptada'),
(73, 32, 43, 'aceptada'),
(74, 32, 44, 'aceptada'),
(75, 32, 45, 'aceptada'),
(76, 29, 46, 'aceptada'),
(78, 28, 38, 'aceptada'),
(79, 28, 36, 'pendiente'),
(80, 29, 41, 'aceptada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo` enum('jugador','organizador','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `correo`, `nombre`, `contraseña`, `tipo`) VALUES
(18, 'admin@activatujuego.com', 'Administrador', '$2y$10$js6TuA56CZx.tvzx57AEsOaMm35FObn9v651x7xLJOp4usBHqaWd2', 'admin'),
(24, 'davilitomorenito03@gmail.com', 'David Pruebas', '$2y$10$xL9ERsWJw9UIVvy034aiSe2fAqCTs3ir90io1UW5la/bK7TjML/X6', 'jugador'),
(27, 'Paconi@gmail.com', 'Paco', '$2y$10$YYrR64mZmboZHvNLP81nnujcGfLUtk/GDdXSqtGjG5ZM.UON6B0lu', 'jugador'),
(28, 'anto@gmail.com', 'Anto', '$2y$10$6X5/iH/Ivmh5lfEbnbW1buM7viiuuxFypsnUxMX3Gx4/ao1zazb6S', 'jugador'),
(29, 'Pepe@gmail.com', 'Pepe', '$2y$10$GUg6DWp8YwBDA3CEvgD1vudvPjT4luy2jqFbsFj9P/ZbtqG1yreQ.', 'organizador'),
(30, 'manoli@gmail.com', 'Manoli', '$2y$10$gJGZLEOdz7BbVJeIztBPNOCWomeKuKh0gvjYX5m1LF8wxRmrYhHP.', 'jugador'),
(31, 'ale@gmail.com', 'Alejandra', '$2y$10$7S9tmXJvbeS2yAc7PXR27O2bZgriqOFneXIYoAJFhQjQ8lS9h7a22', 'jugador'),
(32, 'ana@gmail.com', 'Ana', '$2y$10$4ePPSWaGfALf0E84KpB73uhveYjdBDzvrkb8J6lhdqNwGJML1vl7u', 'organizador'),
(33, 'jesus@gmail.com', 'Jesus', '$2y$10$GlWW9iLjty..ntxlIpfT.O8XMaeoyVDwQz0QV1UwqT/ktz.4ZQati', 'jugador'),
(35, 'sdfsf@sadsd.com', 'sadasd', '$2y$10$8evbrNV47WHvx2RREatOnu9eWkPutPqxaYI6EKoB7.a8r4ecWZUGG', 'jugador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `centrodeportivo`
--
ALTER TABLE `centrodeportivo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deporte`
--
ALTER TABLE `deporte`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizador_id` (`organizador_id`),
  ADD KEY `deporte_id` (`deporte_id`),
  ADD KEY `centro_id` (`centro_id`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `centrodeportivo`
--
ALTER TABLE `centrodeportivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `deporte`
--
ALTER TABLE `deporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`organizador_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`deporte_id`) REFERENCES `deporte` (`id`),
  ADD CONSTRAINT `evento_ibfk_3` FOREIGN KEY (`centro_id`) REFERENCES `centrodeportivo` (`id`);

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
