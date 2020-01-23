-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-01-2020 a las 15:23:01
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `socialdaw`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_post`
--

CREATE TABLE `categoria_post` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria_post`
--

INSERT INTO `categoria_post` (`id`, `descripcion`) VALUES
(0, 'Anime'),
(1, 'Kpop'),
(2, 'Jpop');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comenta`
--

CREATE TABLE `comenta` (
  `post_id` int(11) NOT NULL,
  `usuario_login` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL,
  `texto` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `like`
--

CREATE TABLE `like` (
  `post_id` int(11) NOT NULL,
  `usuario_login` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `resumen` varchar(500) DEFAULT NULL,
  `texto` mediumtext DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `categoria_post_id` int(11) NOT NULL,
  `usuario_login` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `post`
--

INSERT INTO `post` (`id`, `fecha`, `resumen`, `texto`, `foto`, `categoria_post_id`, `usuario_login`) VALUES
(1, NULL, 'soy un resumen', 'soyn un texto', 'iconoAnime1.jpg', 0, 'admin'),
(3, '2020-01-19 20:16:07', 'Soy el resumen del anime', 'Este anime esta muy guay! Me encantaria poder ver más para así compartirlos todos con mis amigos!', 'iconoAnime2.png', 0, 'andres'),
(4, '2020-01-19 20:44:12', 'Jpop es increible!', 'Acabo de escuchar un Jpop extremadamente increible! Cualquiera deberia de escucharlo, es simplemente genial!.', 'iconoAnime2.png', 2, 'andres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `descripcion`) VALUES
(0, 'usuario'),
(1, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sigue`
--

CREATE TABLE `sigue` (
  `usuario_login_seguidor` varchar(50) NOT NULL,
  `usuario_login_seguido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sigue`
--

INSERT INTO `sigue` (`usuario_login_seguidor`, `usuario_login_seguido`) VALUES
('admin', 'andres'),
('nuevo', 'andres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `login` varchar(50) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`login`, `password`, `rol_id`, `nombre`, `email`) VALUES
('admin', '$2y$10$1ZfvTrW3ZNTe7STKTSHOJugTwGvocZGKKyepCwWsEtO2td7qiIPxu', 1, 'admin', 'admin@admin.com'),
('andres', '$2y$10$oTKarAZFXsoaFlmKVT3ru.fo73YrqlyqZF4zt.emaAWEKQtU9coga', 0, 'andres', 'a@a.com'),
('nuevo', '$2y$10$LnYxsbAOK0JvFMtWfMLCQe3UumBgUx4G8DIMmW5I9PI3vmttZUaK2', 0, 'nuevo', 'nuevo@nuevo.com'),
('nuevoya', '$2y$10$aJQNm2n6T3USjKA2bTp.Xeye5EyTMBTGeJrZcYrIv0lbumsdDOmV2', 0, 'nuevoya', 'nuevo@nuevo.com'),
('usu1', '$2y$10$AH/QLAu.YzyP3ZC.rRq9/.KWM3E8SSRhuV/dPfbTpRnKxh6WKQQIC', 0, 'usu1', 'a@a.com'),
('usu2', '$2y$10$Z/zpoNXN1zE77PpqLtAbBONLL/owDMMGQDGCCxRjs4Bsfzwu/zGuC', 0, 'usu1', 'a@a.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria_post`
--
ALTER TABLE `categoria_post`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comenta`
--
ALTER TABLE `comenta`
  ADD PRIMARY KEY (`post_id`,`usuario_login`,`fecha`),
  ADD KEY `fk_post_has_usuario_usuario2_idx` (`usuario_login`),
  ADD KEY `fk_post_has_usuario_post2_idx` (`post_id`);

--
-- Indices de la tabla `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`post_id`,`usuario_login`),
  ADD KEY `fk_post_has_usuario_usuario1_idx` (`usuario_login`),
  ADD KEY `fk_post_has_usuario_post1_idx` (`post_id`);

--
-- Indices de la tabla `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_categoria_post1_idx` (`categoria_post_id`),
  ADD KEY `fk_post_usuario1_idx` (`usuario_login`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sigue`
--
ALTER TABLE `sigue`
  ADD PRIMARY KEY (`usuario_login_seguidor`,`usuario_login_seguido`),
  ADD KEY `fk_usuario_has_usuario_usuario2_idx` (`usuario_login_seguido`),
  ADD KEY `fk_usuario_has_usuario_usuario1_idx` (`usuario_login_seguidor`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`login`),
  ADD KEY `fk_usuario_rol_idx` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comenta`
--
ALTER TABLE `comenta`
  ADD CONSTRAINT `fk_post_has_usuario_post2` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_has_usuario_usuario2` FOREIGN KEY (`usuario_login`) REFERENCES `usuario` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `fk_post_has_usuario_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_has_usuario_usuario1` FOREIGN KEY (`usuario_login`) REFERENCES `usuario` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_categoria_post1` FOREIGN KEY (`categoria_post_id`) REFERENCES `categoria_post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_usuario1` FOREIGN KEY (`usuario_login`) REFERENCES `usuario` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sigue`
--
ALTER TABLE `sigue`
  ADD CONSTRAINT `fk_usuario_has_usuario_usuario1` FOREIGN KEY (`usuario_login_seguidor`) REFERENCES `usuario` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_has_usuario_usuario2` FOREIGN KEY (`usuario_login_seguido`) REFERENCES `usuario` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
