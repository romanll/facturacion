-- phpMyAdmin SQL Dump
-- version 4.2.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-08-2014 a las 07:30:00
-- Versión del servidor: 5.1.62-community
-- Versión de PHP: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `facturacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
`idcliente` int(11) NOT NULL,
  `identificador` varchar(10) NOT NULL COMMENT 'Identificador de cliente: texto o numerico',
  `nombre` varchar(200) DEFAULT NULL COMMENT 'nombre o razon social',
  `rfc` varchar(15) NOT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `ninterior` varchar(20) DEFAULT NULL,
  `nexterior` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `localidad` varchar(150) DEFAULT NULL COMMENT 'ciudad o poblacion',
  `referencia` varchar(250) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `cp` varchar(7) DEFAULT NULL,
  `emisor` int(11) NOT NULL COMMENT 'Proveedor del cliente',
  `fecha` datetime NOT NULL COMMENT 'Fecha de registro del cliente, por si acaso',
  `telefono` varchar(45) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Clientes del Contribuyente' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE IF NOT EXISTS `conceptos` (
`idconcepto` int(11) NOT NULL,
  `noidentificacion` varchar(45) DEFAULT NULL,
  `descripcion` tinytext,
  `valor` varchar(45) NOT NULL COMMENT 'valor unitario',
  `unidad` varchar(45) NOT NULL,
  `observaciones` mediumtext,
  `emisor` int(11) NOT NULL COMMENT 'prestador del servicio o dueño del producto',
  `impuestos` tinytext NOT NULL COMMENT 'puede ser ''cero'',excento'',o strin array de impuestos'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Productos o servicios del contribuyente' AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emisor`
--

CREATE TABLE IF NOT EXISTS `emisor` (
`idemisor` int(11) NOT NULL,
  `razonsocial` varchar(500) DEFAULT NULL,
  `rfc` varchar(45) NOT NULL,
  `regimen` varchar(70) NOT NULL,
  `calle` varchar(200) DEFAULT NULL,
  `ninterior` varchar(10) DEFAULT NULL,
  `nexterior` varchar(10) DEFAULT NULL,
  `colonia` varchar(200) DEFAULT NULL,
  `localidad` varchar(200) DEFAULT NULL,
  `referencia` varchar(200) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `pais` varchar(50) NOT NULL,
  `cp` varchar(5) DEFAULT NULL,
  `key` varchar(100) DEFAULT NULL,
  `cer` varchar(100) DEFAULT NULL,
  `keypwd` varchar(100) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `nocertificado` varchar(45) DEFAULT NULL,
  `pem` varchar(100) DEFAULT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `timbres` int(10) DEFAULT '0',
  `telefono` varchar(30) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `fecha` date NOT NULL COMMENT 'Fecha de registro del emisor'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Datos del contribuyente' AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE IF NOT EXISTS `estados` (
`idestado` int(11) NOT NULL,
  `estado` varchar(70) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE IF NOT EXISTS `facturas` (
`idfactura` int(11) NOT NULL,
  `receptor` varchar(45) DEFAULT NULL COMMENT 'RFC del receptor, solo para referencia',
  `fecha` date DEFAULT NULL,
  `emisor` int(11) NOT NULL COMMENT 'fk de emisor',
  `nodo_comprobante` text NOT NULL COMMENT 'para nodo comprobante de xml',
  `nodo_emisor` text NOT NULL,
  `nodo_receptor` text NOT NULL,
  `nodo_conceptos` text NOT NULL,
  `nodo_impuestos` text NOT NULL,
  `nodo_timbre` text NOT NULL,
  `estado` varchar(45) DEFAULT NULL COMMENT 'cancelado o no',
  `filename` varchar(70) DEFAULT NULL COMMENT 'nombre del archivo XML o PDF, la extension se puede agregar',
  `impuestos` text,
  `uuid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='almacena las facturas emtidas por cliente' AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos`
--

CREATE TABLE IF NOT EXISTS `impuestos` (
`idimpuesto` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL COMMENT 'descripcion del impuesto',
  `tipo` varchar(45) NOT NULL COMMENT 'traslado o retenido',
  `nombre` varchar(45) NOT NULL COMMENT 'Siglas del impuesto',
  `tasa` decimal(10,2) NOT NULL COMMENT 'tasa en decimal, 16%,10.67%...'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Lista de impuestos' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internet`
--

CREATE TABLE IF NOT EXISTS `internet` (
`idinternet` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nombre` text NOT NULL,
  `fechanac` date DEFAULT NULL,
  `direccion` text NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `correo` varchar(125) NOT NULL,
  `ife` varchar(30) NOT NULL,
  `ref1` varchar(250) DEFAULT NULL,
  `ref2` varchar(250) DEFAULT NULL,
  `horario` varchar(150) NOT NULL,
  `accespoint` char(1) NOT NULL,
  `requisitos` text,
  `nota` text,
  `mapa` varchar(250) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'abierto',
  `tarifa` varchar(45) DEFAULT NULL,
  `fechainstalacion` date DEFAULT NULL,
  `instalador` varchar(150) DEFAULT NULL COMMENT 'usuario que hizo la instalacion, string',
  `detalles` text,
  `historial` text,
  `localidad` varchar(125) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE IF NOT EXISTS `municipios` (
`idmunicipio` int(11) NOT NULL,
  `municipio` varchar(150) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2453 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `series`
--

CREATE TABLE IF NOT EXISTS `series` (
`idserie` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `folio_inicial` int(11) NOT NULL DEFAULT '1' COMMENT 'folio inicial',
  `emisor` int(11) NOT NULL COMMENT 'creador de serie',
  `folio_actual` int(11) NOT NULL COMMENT 'folio actual, debe actualizarse en +1'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='series y folios con la cual inicia la serie' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`idusuario` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='datos de usuario para accesar al sistema' AUTO_INCREMENT=9 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
 ADD PRIMARY KEY (`idcliente`), ADD KEY `cliente_emisor_idx` (`emisor`);

--
-- Indices de la tabla `conceptos`
--
ALTER TABLE `conceptos`
 ADD PRIMARY KEY (`idconcepto`), ADD KEY `concepto_emisor_idx` (`emisor`);

--
-- Indices de la tabla `emisor`
--
ALTER TABLE `emisor`
 ADD PRIMARY KEY (`idemisor`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
 ADD PRIMARY KEY (`idestado`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
 ADD PRIMARY KEY (`idfactura`), ADD KEY `factura_emisor_idx` (`emisor`);

--
-- Indices de la tabla `impuestos`
--
ALTER TABLE `impuestos`
 ADD PRIMARY KEY (`idimpuesto`);

--
-- Indices de la tabla `internet`
--
ALTER TABLE `internet`
 ADD PRIMARY KEY (`idinternet`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
 ADD PRIMARY KEY (`idmunicipio`), ADD KEY `estado_municipio_idx` (`estado`);

--
-- Indices de la tabla `series`
--
ALTER TABLE `series`
 ADD PRIMARY KEY (`idserie`), ADD KEY `serie_emisor_idx` (`emisor`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
MODIFY `idconcepto` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `emisor`
--
ALTER TABLE `emisor`
MODIFY `idemisor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
MODIFY `idestado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
MODIFY `idfactura` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT de la tabla `impuestos`
--
ALTER TABLE `impuestos`
MODIFY `idimpuesto` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `internet`
--
ALTER TABLE `internet`
MODIFY `idinternet` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
MODIFY `idmunicipio` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2453;
--
-- AUTO_INCREMENT de la tabla `series`
--
ALTER TABLE `series`
MODIFY `idserie` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
ADD CONSTRAINT `cliente_emisor` FOREIGN KEY (`emisor`) REFERENCES `emisor` (`idemisor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `conceptos`
--
ALTER TABLE `conceptos`
ADD CONSTRAINT `concepto_emisor` FOREIGN KEY (`emisor`) REFERENCES `emisor` (`idemisor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
ADD CONSTRAINT `factura_emisor` FOREIGN KEY (`emisor`) REFERENCES `emisor` (`idemisor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
ADD CONSTRAINT `estado_municipio` FOREIGN KEY (`estado`) REFERENCES `estados` (`idestado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `series`
--
ALTER TABLE `series`
ADD CONSTRAINT `serie_emisor` FOREIGN KEY (`emisor`) REFERENCES `emisor` (`idemisor`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
