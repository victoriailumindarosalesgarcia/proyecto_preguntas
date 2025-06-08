-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 05:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pag`
--

-- --------------------------------------------------------

--
-- Table structure for table `alta`
--

CREATE TABLE `alta` (
  `id_user` int(20) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alta`
--

INSERT INTO `alta` (`id_user`, `correo`, `nombre`, `password`) VALUES
(1, '', 'ale.sal.130613@gmail.com', 'kk'),
(2, '', 'ale.sal.130613@gmail.com', 'kk'),
(3, '', 'Carlita@gmail.com', 'rfesita'),
(4, '', 'ale.sal.130613@gmail.com', 'JJ'),
(5, '', 'gg@gmail.com', 'hola'),
(6, '', 'Tamalitoemalito@gmail.com', 'amoaandres'),
(11, 'Tatis@gmail.com', 'Tatis', '$2y$10$7OqsY5vSWXhtof.A5n.p/eRitHQATZ4l75ppcQPqfeO.QZe.3W.ay');

-- --------------------------------------------------------

--
-- Table structure for table `opciones`
--

CREATE TABLE `opciones` (
  `id_opciones` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `texto` varchar(300) NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `es_correcta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `opciones`
--

INSERT INTO `opciones` (`id_opciones`, `id_pregunta`, `texto`, `imagen`, `es_correcta`) VALUES
(1, 1, 'd', '', 1),
(2, 1, 's', '', 0),
(3, 1, 'a', '', 0),
(4, 1, 'c', '', 0),
(5, 2, 'd', '', 1),
(6, 2, 's', '', 0),
(7, 2, 'a', '', 0),
(8, 2, 'c', '', 0),
(9, 3, 'd', '', 1),
(10, 3, 's', '', 0),
(11, 3, 'a', '', 0),
(12, 3, 'c', '', 0),
(13, 4, 'd', '', 1),
(14, 4, 's', '', 0),
(15, 4, 'f', '', 0),
(16, 4, 'c', '', 0),
(17, 5, 'd', '', 1),
(18, 5, 's', '', 0),
(19, 5, 'f', '', 0),
(20, 5, 'c', '', 0),
(21, 6, 'hola', '', 0),
(22, 6, 'adios', '', 0),
(23, 6, 'bye', '', 0),
(24, 6, 'hello', '', 1),
(25, 7, 'hola', '', 0),
(26, 7, 'adios', '', 0),
(27, 7, 'bye', '', 0),
(28, 7, 'hello', '', 1),
(29, 9, 'f', NULL, 1),
(30, 9, 'h', NULL, 0),
(31, 9, 'c', NULL, 0),
(32, 9, 'b', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pregunta_abierta`
--

CREATE TABLE `pregunta_abierta` (
  `id_pregunta` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `texto` varchar(300) NOT NULL,
  `imagen` blob DEFAULT NULL,
  `respuesta` varchar(300) NOT NULL,
  `dificultad` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pregunta_opcion`
--

CREATE TABLE `pregunta_opcion` (
  `id_preg_op` int(11) NOT NULL,
  `texto` varchar(300) NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `dificultad` varchar(5) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pregunta_opcion`
--

INSERT INTO `pregunta_opcion` (`id_preg_op`, `texto`, `imagen`, `dificultad`, `id_user`) VALUES
(1, 'ds', '', '3', 1),
(2, 'ds', '', '3', 1),
(3, 'ds', '', '3', 1),
(4, 'ww', '', '3', 1),
(5, 'ww', '', '3', 1),
(6, 'check', '', '3', 1),
(7, 'check', '', '3', 1),
(8, 'dsdfds', NULL, '3', 1),
(9, 'dsdfds', NULL, '3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pregunta_vf`
--

CREATE TABLE `pregunta_vf` (
  `id_pregunta` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `texto` varchar(300) NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `respuesta` enum('Verdadero','Falso','','') NOT NULL,
  `incorrecta` enum('Verdadero','Falso','','') NOT NULL,
  `dificultad` int(30) NOT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alta`
--
ALTER TABLE `alta`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id_opciones`),
  ADD KEY `fk_pregunta_respuesta` (`id_pregunta`);

--
-- Indexes for table `pa`
--
ALTER TABLE `pa`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indexes for table `pregunta_abierta`
--
ALTER TABLE `pregunta_abierta`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `fk__usuario_pregunta_abierta` (`id_user`);

--
-- Indexes for table `pregunta_opcion`
--
ALTER TABLE `pregunta_opcion`
  ADD PRIMARY KEY (`id_preg_op`),
  ADD KEY `fk_usuario_pregunta_opcion` (`id_user`);

--
-- Indexes for table `pregunta_vf`
--
ALTER TABLE `pregunta_vf`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `fk__usuario_pregunta_vf` (`id_user`);

--
-- Indexes for table `vf`
--
ALTER TABLE `vf`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alta`
--
ALTER TABLE `alta`
  MODIFY `id_user` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `opciones`
--
ALTER TABLE `opciones`
  MODIFY `id_opciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pa`
--
ALTER TABLE `pa`
  MODIFY `id_pregunta` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pregunta_abierta`
--
ALTER TABLE `pregunta_abierta`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pregunta_opcion`
--
ALTER TABLE `pregunta_opcion`
  MODIFY `id_preg_op` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pregunta_vf`
--
ALTER TABLE `pregunta_vf`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vf`
--
ALTER TABLE `vf`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `fk_pregunta_respuesta` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta_opcion` (`id_preg_op`);

--
-- Constraints for table `pregunta_abierta`
--
ALTER TABLE `pregunta_abierta`
  ADD CONSTRAINT `fk__usuario_pregunta_abierta` FOREIGN KEY (`id_user`) REFERENCES `alta` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `preguntas_abiertas`
ADD `ruta_imagen_respuesta` VARCHAR(255) NULL DEFAULT NULL AFTER `respuesta_esperada`;
--
-- Constraints for table `pregunta_opcion`
--
ALTER TABLE `pregunta_opcion`
  ADD CONSTRAINT `fk_usuario_pregunta_opcion` FOREIGN KEY (`id_user`) REFERENCES `alta` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pregunta_vf`
--
ALTER TABLE `pregunta_vf`
  ADD CONSTRAINT `fk__usuario_pregunta_vf` FOREIGN KEY (`id_user`) REFERENCES `alta` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
