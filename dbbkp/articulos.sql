-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-04-2017 a las 16:13:57
-- Versión del servidor: 5.6.25
-- Versión de PHP: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test_ambiente`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE IF NOT EXISTS `articulos` (
  `factualizado` date DEFAULT NULL,
  `bloqueado` tinyint(1) DEFAULT '0',
  `equivalencia` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `idsubcuentairpfcom` int(11) DEFAULT NULL,
  `idsubcuentacom` int(11) DEFAULT NULL,
  `stockmin` double DEFAULT '0',
  `observaciones` text COLLATE utf8_bin,
  `codbarras` varchar(18) COLLATE utf8_bin DEFAULT NULL,
  `codimpuesto` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `stockfis` double DEFAULT '0',
  `stockmax` double DEFAULT '0',
  `costemedio` double DEFAULT '0',
  `preciocoste` double DEFAULT '0',
  `precio_con_iva` double NOT NULL DEFAULT '0',
  `tipocodbarras` varchar(8) COLLATE utf8_bin DEFAULT 'Code39',
  `nostock` tinyint(1) DEFAULT NULL,
  `codsubcuentacom` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `descripcion` text COLLATE utf8_bin NOT NULL,
  `codsubcuentairpfcom` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `secompra` tinyint(1) DEFAULT NULL,
  `codfamilia` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `imagen` text COLLATE utf8_bin,
  `controlstock` tinyint(1) DEFAULT '0',
  `referencia` varchar(18) COLLATE utf8_bin NOT NULL,
  `tipo` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `pvp` double DEFAULT '0',
  `sevende` tinyint(1) DEFAULT NULL,
  `publico` tinyint(1) DEFAULT '0',
  `codfabricante` varchar(8) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`factualizado`, `bloqueado`, `equivalencia`, `idsubcuentairpfcom`, `idsubcuentacom`, `stockmin`, `observaciones`, `codbarras`, `codimpuesto`, `stockfis`, `stockmax`, `costemedio`, `preciocoste`, `precio_con_iva`, `tipocodbarras`, `nostock`, `codsubcuentacom`, `descripcion`, `codsubcuentairpfcom`, `secompra`, `codfamilia`, `imagen`, `controlstock`, `referencia`, `tipo`, `pvp`, `sevende`, `publico`, `codfabricante`) VALUES
('2016-04-08', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 25, 0, 3000, 3000, 3500, 'Code39', 0, NULL, '', NULL, 0, NULL, NULL, 0, 'ACIDO-SULFONICO', NULL, 3000, 0, 0, NULL),
('2016-03-13', 0, NULL, NULL, NULL, 13, '', '', 'IVA10', 37, 0, 5000, 4000, 7000, 'Code39', 0, NULL, 'Anilina en polvo', NULL, 1, 'QUIM', NULL, 1, 'ANILINA-0001', NULL, 5000, 1, 0, 'PROV'),
('2016-03-14', 0, 'BASE-SUAVE001', NULL, NULL, 0, '', '', 'IVA10', 10, 0, 24000, 22000, 0, 'Code39', 0, NULL, 'Base suave color 1', NULL, 1, 'Mt', NULL, 1, 'BASE-SUAVE', NULL, 24000, 1, 0, 'PROV'),
('2016-10-03', 0, NULL, NULL, NULL, 3, '', '', 'IVA10', 11, 0, 171727.27272727, 2000, 0, 'Code39', 0, NULL, 'bicarbonato', NULL, 1, 'Mt', NULL, 1, 'BICARBONATO', NULL, 13636.36, 1, 0, 'PROV'),
('2016-03-05', 0, NULL, NULL, NULL, 16, '', '', 'IVA10', 44, 0, 2720, 0, 0, 'Code39', 0, NULL, 'Bidon de 5Lts', NULL, 1, 'VARI', NULL, 1, 'BIDON-5L-001', NULL, 2900, 1, 0, 'PROV'),
('2016-03-13', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 13, 0, 5000, 4000, 5500, 'Code39', 0, NULL, '', NULL, 0, NULL, NULL, 0, 'CARBUR-0014', NULL, 5000, 0, 0, NULL),
('2016-10-23', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 160, 0, 1818.1818181818, 2000, 0, 'Code39', 0, NULL, 'Cloro 12%', NULL, 1, 'Mt', NULL, 1, 'CLORO 12%', NULL, 17272.73, 1, 0, 'PROV'),
('2016-10-23', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 1.5, 0, 9090.9090909091, 0, 0, 'Code39', 0, NULL, 'color verde para detergente', NULL, 1, 'Mt', NULL, 1, 'COLOR VERDE', NULL, 10000, 1, 0, 'PROV'),
('2016-03-14', 0, 'Colorante Fluoreceina Kg.', NULL, NULL, 35, '', '', 'IVA10', 49, 0, 242000, 0, 0, 'Code39', 0, NULL, 'Colorante Fluoreceina Kg.', NULL, 1, 'Mt', NULL, 1, 'COLOR-FLUOR', NULL, 242000, 1, 0, 'PROV'),
('2016-03-13', 0, 'colorante rojo', NULL, NULL, 0, '', '', 'IVA10', 2, 0, 201000, 201000, 0, 'Code39', 0, NULL, 'colorante químico', NULL, 1, 'QUIM', NULL, 1, 'COLOR-ROJO', NULL, 201000, 1, 0, 'PROV'),
('2016-10-23', 0, NULL, NULL, NULL, 2, '', '', 'IVA10', 9, 0, 10500, 0, 0, 'Code39', 0, NULL, 'color azul colanil para suavizante', NULL, 1, 'Mt', NULL, 1, 'COLOR_AZUL', NULL, 10500, 1, 0, 'PROV'),
('2015-12-11', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 93, 0, 0, 0, 0, 'Code39', 0, NULL, 'Detergente Neutro sin tensan al 30%', NULL, 0, 'DETER', NULL, 1, 'DETER-0002', NULL, 2929, 1, 0, 'PROPIO'),
('2015-12-11', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 99, 0, 0, 0, 0, 'Code39', 0, NULL, 'Detergente Neutro', NULL, 1, 'DETER', NULL, 1, 'DETER-0003', NULL, 864, 1, 0, 'PROPIO'),
('2015-12-11', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 297, 0, 0, 0, 0, 'Code39', 0, NULL, 'Detergente Neutro sin tensan', NULL, 0, 'DETER', NULL, 1, 'DETER-0020', NULL, 864, 1, 0, 'PROPIO'),
('2015-12-11', 0, NULL, NULL, NULL, 50, '', '', 'IVA10', 58, 0, 0, 0, 0, 'Code39', 0, NULL, 'Detergente Neutro 30 %', NULL, 0, 'DETER', NULL, 1, 'DETER-3001', NULL, 3224, 1, 0, 'PROPIO'),
('2016-03-14', 0, 'Enturbiante', NULL, NULL, 0, '', '', 'IVA10', 8, 0, 9350, 9350, 0, 'Code39', 0, NULL, 'Enturbiante', NULL, 1, 'QUIM', NULL, 1, 'ENTURBIANTE-001', NULL, 10000, 1, 0, 'PROV'),
('2017-04-13', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 35, 0, 0, 0, 1000, 'Code39', 0, NULL, 'Jabon Liquido Mano', NULL, 0, 'JABON LI', NULL, 1, 'JABON-LIQM', NULL, 0, 1, 0, 'PROPIO'),
('2015-12-11', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 184, 0, 0, 0, 5000, 'Code39', 0, NULL, '', NULL, 0, NULL, NULL, 0, 'JABON-LIQR', NULL, 2222, 0, 0, NULL),
('2016-03-05', 0, NULL, NULL, NULL, 0, '', '', 'IVA10', 23, 0, 14776, 0, 0, 'Code39', 0, NULL, 'Trietanolamina TEA 85', NULL, 1, 'QUIM', NULL, 1, 'TRIETANOLAMI-TEA85', NULL, 14776, 1, 0, 'PROV');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`referencia`),
  ADD KEY `ca_articulos_impuestos2` (`codimpuesto`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `ca_articulos_impuestos2` FOREIGN KEY (`codimpuesto`) REFERENCES `impuestos` (`codimpuesto`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
