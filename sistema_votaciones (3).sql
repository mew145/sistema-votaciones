-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2025 a las 00:34:34
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
-- Base de datos: `sistema_votaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id_admin` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id_admin`, `nombre`, `apellido`, `email`, `contrasena`, `id_rol`) VALUES
('c45c76ea-13ee-11f0-9', 'Aldo', 'Figueredo', 'aldofiguesalva@gmail.com', '$2y$12$zl1uS8lRwwfu334BXt.k0u/kCFS1zCxsFlP/lyE8hn/KlofKBv4gK', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `candidatos`
--

CREATE TABLE `candidatos` (
  `id_candidato` varchar(20) NOT NULL,
  `id_eleccion` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `id_partido` varchar(20) NOT NULL,
  `foto_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `candidatos`
--

INSERT INTO `candidatos` (`id_candidato`, `id_eleccion`, `nombre`, `apellido`, `id_partido`, `foto_url`) VALUES
('33dbb197-25cd-11f0-b', '787058f3-2c3d-11f0-9', 'Samuel', 'Medina', 'PP-0001', NULL),
('4a4218f0-25ce-11f0-b', '6ec0bda0-2b89-11f0-9', 'Samuel', 'Medina', 'PP-0001', NULL),
('777c7fe7-2ad4-11f0-a', '5f591f52-2ad4-11f0-a', 'Samuel', 'Medina', 'PP-0001', NULL),
('b7124f0d-2b89-11f0-9', '787058f3-2c3d-11f0-9', 'Evo', 'Morales', 'PP-0001', NULL),
('ce0e4adf-25cf-11f0-b', '20335149-2487-11f0-b', 'Samuel', 'Medina', 'PP-0001', NULL),
('e853ee82-2ad9-11f0-a', '787058f3-2c3d-11f0-9', 'Tuto', 'Quiroga', 'PP-0001', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `id_ciudad` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_provincia` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id_ciudad`, `nombre`, `id_provincia`) VALUES
('ci-1', 'La Paz', 'prov-1'),
('ci-10', 'Sacaba', 'prov-25'),
('ci-11', 'Colcapirhua', 'prov-25'),
('ci-12', 'Tiquipaya', 'prov-25'),
('ci-13', 'Vinto', 'prov-25'),
('ci-14', 'Santa Cruz de la Sierra', 'prov-120'),
('ci-15', 'Cotoca', 'prov-120'),
('ci-16', 'Porongo', 'prov-120'),
('ci-17', 'La Guardia', 'prov-120'),
('ci-18', 'El Torno', 'prov-120'),
('ci-19', 'Potos?', 'prov-119'),
('ci-2', 'El Alto', 'prov-1'),
('ci-20', 'Tinguipaya', 'prov-119'),
('ci-21', 'Yocalla', 'prov-119'),
('ci-22', 'Urmiri', 'prov-119'),
('ci-23', 'Sucre', 'prov-2'),
('ci-24', 'Yotala', 'prov-2'),
('ci-25', 'Poroma', 'prov-2'),
('ci-26', 'Tarija', 'prov-25'),
('ci-27', 'San Lorenzo', 'prov-25'),
('ci-28', 'El Valle', 'prov-25'),
('ci-29', 'Oruro', 'prov-25'),
('ci-3', 'Cochabamba', 'prov-2'),
('ci-30', 'Caracollo', 'prov-25'),
('ci-31', 'El Choro', 'prov-25'),
('ci-35', 'Warnes', 'prov-126'),
('ci-36', 'Okinawa', 'prov-126'),
('ci-37', 'Buena Vista', 'prov-127'),
('ci-38', 'San Carlos', 'prov-127'),
('ci-39', 'Vallegrande', 'prov-133'),
('ci-4', 'Achocalla', 'prov-1'),
('ci-40', 'Pucar?', 'prov-133'),
('ci-41', 'Moro Moro', 'prov-133'),
('ci-42', 'Puerto Su?rez', 'prov-121'),
('ci-43', 'Puerto Quijarro', 'prov-121'),
('ci-44', 'Lagunillas', 'prov-123'),
('ci-45', 'Camiri', 'prov-123'),
('ci-46', 'Charagua', 'prov-123'),
('ci-47', 'Bermejo', 'prov-105'),
('ci-48', 'Padcaya', 'prov-105'),
('ci-49', 'Yacuiba', 'prov-110'),
('ci-5', 'Viacha', 'prov-1'),
('ci-50', 'Villamontes', 'prov-110'),
('ci-51', 'Puerto Rico', 'prov-102'),
('ci-52', 'Filadelfia', 'prov-102'),
('ci-6', 'Laja', 'prov-1'),
('ci-7', 'Palca', 'prov-1'),
('ci-8', 'Mecapaca', 'prov-1'),
('ci-9', 'Quillacollo', 'prov-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

CREATE TABLE `configuracion_sistema` (
  `id_configuracion` varchar(20) NOT NULL,
  `parametro` varchar(100) NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id_departamento` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id_departamento`, `nombre`) VALUES
('dep-6', 'Beni'),
('dep-8', 'Chuquisaca'),
('dep-2', 'Cochabamba'),
('dep-1', 'La Paz'),
('dep-4', 'Oruro'),
('dep-7', 'Pando'),
('dep-9', 'Potosi'),
('dep-3', 'Santa Cruz'),
('Dep-5', 'Tarija');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elecciones`
--

CREATE TABLE `elecciones` (
  `id_eleccion` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` enum('activa','finalizada','pendiente') DEFAULT 'pendiente',
  `id_tipo` int(11) NOT NULL DEFAULT 1 COMMENT '1=Electoral, 2=Refer?ndum, etc.',
  `id_ciudad` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elecciones`
--

INSERT INTO `elecciones` (`id_eleccion`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `estado`, `id_tipo`, `id_ciudad`) VALUES
('0d63f3a6-13f0-11f0-9', 'Candidatura a presidencia', 'Selecciona tu mejor opcion a presidente', '2025-04-07 16:36:00', '2025-04-07 16:41:00', 'finalizada', 1, NULL),
('1759ab8e-13f0-11f0-9', 'Candidatura a presidencia', 'Selecciona tu mejor opcion a presidente', '2025-04-07 16:36:00', '2025-04-07 16:41:00', 'finalizada', 1, NULL),
('20335149-2487-11f0-b', 'elecciones 2025', 'evo', '2025-04-30 10:26:45', '2025-05-07 10:26:45', 'activa', 1, NULL),
('2912db1a-25cd-11f0-b', 'elecciones 2025', 'Elecciones presidenciales gestion 2025', '2025-04-30 10:10:00', '2025-04-30 10:15:00', 'finalizada', 1, NULL),
('43881bc6-25ce-11f0-b', 'elecciones 2026', 'awea', '2025-04-30 10:20:00', '2025-04-30 10:25:00', 'activa', 1, NULL),
('5f591f52-2ad4-11f0-a', 'elecciones 2027', 'votaciones 2027 pruebas', '2025-05-06 19:46:00', '2025-05-07 19:46:00', 'activa', 1, NULL),
('6ec0bda0-2b89-11f0-9', 'Presidencia 2025', 'Elecciones presidenciales 2025', '2025-05-07 17:22:00', '2025-05-08 17:22:00', 'activa', 1, NULL),
('787058f3-2c3d-11f0-9', 'Presidencia 2029', 'Elecciones 2029', '2025-05-08 14:51:00', '2025-05-16 14:51:00', 'activa', 1, NULL),
('c762791d-25cf-11f0-b', 'elecciones 2027', 'deas', '2025-04-29 10:31:00', '2025-04-30 10:45:00', 'activa', 1, NULL),
('ebbdb8f4-2ad6-11f0-a', 'Alcaldia La Paz 2025', 'Votaciones para definir al nuevo alcalde para la ciudad de La Paz gestion 2025', '2025-05-06 20:04:00', '2025-05-07 20:04:00', 'activa', 2, 'ci-1'),
('f01959c6-25cc-11f0-b', 'elecciones 2025', 'Elecciones presidenciales gestion 2025', '2025-04-30 10:10:00', '2025-04-30 10:15:00', 'pendiente', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_auditoria`
--

CREATE TABLE `log_auditoria` (
  `id_log` varchar(20) NOT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `accion` text NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_origen` varchar(50) NOT NULL,
  `tipo_usuario` enum('usuario','admin','super_admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_auditoria`
--

INSERT INTO `log_auditoria` (`id_log`, `usuario`, `accion`, `fecha_hora`, `ip_origen`, `tipo_usuario`) VALUES
('', '572b95ad-125a-11f0-a', 'Inicio de sesión', '2025-04-05 16:16:23', '::1', NULL),
('67f1985e7d98f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 16:53:50', '::1', NULL),
('67f198a2be976', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 16:54:58', '::1', NULL),
('67f198caa0762', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 16:55:38', '::1', NULL),
('67f19949394a2', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 16:57:45', '::1', NULL),
('67f19981d1c28', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 16:58:41', '::1', NULL),
('67f199ba74059', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-04-05 16:59:38', '::1', NULL),
('67f199d7997ce', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 17:00:07', '::1', NULL),
('67f199d8edcc8', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-05 17:00:08', '::1', NULL),
('67f199dd80577', '572b95ad-125a-11f0-a', 'Inicio de sesión', '2025-04-05 17:00:13', '::1', NULL),
('67f199dd81d63', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-05 17:00:13', '::1', NULL),
('67f199e6d7df7', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-05 17:00:22', '::1', NULL),
('67f43239dc467', '572b95ad-125a-11f0-a', 'Inicio de sesión', '2025-04-07 16:14:49', '::1', NULL),
('67f43239e626f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:14:49', '::1', NULL),
('67f4323b6f693', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:14:51', '::1', NULL),
('67f43305db10a', '572b95ad-125a-11f0-a', 'Inicio de sesión', '2025-04-07 16:18:13', '::1', NULL),
('67f43305e33de', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:18:13', '::1', NULL),
('67f43307e8998', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:18:15', '::1', NULL),
('67f433540eb17', '572b95ad-125a-11f0-a', 'Inicio de sesión', '2025-04-07 16:19:32', '::1', NULL),
('67f4335412eae', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:19:32', '::1', NULL),
('67f43354c74cc', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:19:32', '::1', NULL),
('67f4350632981', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:26:46', '::1', NULL),
('67f43512c85e2', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:26:58', '::1', NULL),
('67f43542936cd', 'suad-001', 'Creó nuevo administrador: aldofiguesalva@gmail.com', '2025-04-07 16:27:46', '::1', NULL),
('67f4354293bf8', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:27:46', '::1', NULL),
('67f435463f381', 'suad-001', 'Eliminó administrador ID: admin-1', '2025-04-07 16:27:50', '::1', NULL),
('67f435463fd87', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:27:50', '::1', NULL),
('67f4356fc8b81', 'suad-001', 'Eliminó administrador ID: admin-1', '2025-04-07 16:28:31', '::1', NULL),
('67f4356fc94f6', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:28:31', '::1', NULL),
('67f435754eebf', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:28:37', '::1', NULL),
('67f43577f1ecd', 'suad-001', 'Accedió al panel de reclamos', '2025-04-07 16:28:39', '::1', NULL),
('67f4357dbbb0b', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:28:45', '::1', NULL),
('67f4357eeabc0', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:28:46', '::1', NULL),
('67f43584ee7cf', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:28:52', '::1', NULL),
('67f4358991b47', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:28:57', '::1', NULL),
('67f43630d5dc2', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:31:44', '::1', NULL),
('67f43635bb1be', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:31:49', '::1', NULL),
('67f4364586d52', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:32:05', '::1', NULL),
('67f43647c0a62', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:32:07', '::1', NULL),
('67f436592515b', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:32:25', '::1', NULL),
('67f4365b4e68c', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:32:27', '::1', NULL),
('67f4374fb7ce4', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:36:31', '::1', NULL),
('67f4376a97f8e', 'suad-001', 'Creó votación electoral: Candidatura a presidencia', '2025-04-07 16:36:58', '::1', NULL),
('67f4376a988a9', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:36:58', '::1', NULL),
('67f4377b51dbd', 'suad-001', 'Creó votación electoral: Candidatura a presidencia', '2025-04-07 16:37:15', '::1', NULL),
('67f4377b52e2b', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:37:15', '::1', NULL),
('67f4378038f8d', 'suad-001', 'Cierre de sesión', '2025-04-07 16:37:20', '::1', NULL),
('67f4378315a4e', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:37:23', '::1', NULL),
('67f437893aa15', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:37:29', '::1', NULL),
('67f4378c22a08', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:37:32', '::1', NULL),
('67f4378eadbb3', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:37:34', '::1', NULL),
('67f437961a6e7', 'suad-001', 'Cambió estado de votación ID: 0d63f3a6-13f0-11f0-9 a activa', '2025-04-07 16:37:42', '::1', NULL),
('67f437961ae47', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:37:42', '::1', NULL),
('67f4379890b85', 'suad-001', 'Cierre de sesión', '2025-04-07 16:37:44', '::1', NULL),
('67f4379c04172', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:37:48', '::1', NULL),
('67f4379fd2fbe', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:37:51', '::1', NULL),
('67f437a237169', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:37:54', '::1', NULL),
('67f437a386def', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:37:55', '::1', NULL),
('67f437a655260', 'suad-001', 'Cambió estado de votación ID: 1759ab8e-13f0-11f0-9 a activa', '2025-04-07 16:37:58', '::1', NULL),
('67f437a655ae8', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:37:58', '::1', NULL),
('67f437af85743', 'suad-001', 'Cambió estado de votación ID: 1759ab8e-13f0-11f0-9 a activa', '2025-04-07 16:38:07', '::1', NULL),
('67f437af87498', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:38:07', '::1', NULL),
('67f437b288869', 'suad-001', 'Cambió estado de votación ID: 1759ab8e-13f0-11f0-9 a activa', '2025-04-07 16:38:10', '::1', NULL),
('67f437b289634', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:38:10', '::1', NULL),
('67f437b34fbdb', 'suad-001', 'Cambió estado de votación ID: 0d63f3a6-13f0-11f0-9 a activa', '2025-04-07 16:38:11', '::1', NULL),
('67f437b35062f', 'suad-001', 'Accedió a votaciones electorales', '2025-04-07 16:38:11', '::1', NULL),
('67f437b44f803', 'suad-001', 'Cierre de sesión', '2025-04-07 16:38:12', '::1', NULL),
('67f437b69cd54', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:38:14', '::1', NULL),
('67f437b8bf234', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-04-07 16:38:16', '::1', NULL),
('67f437bd0383a', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-07 16:38:21', '::1', NULL),
('67f437bf393a9', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:38:23', '::1', NULL),
('67f437c08a253', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:24', '::1', NULL),
('67f437c4a1528', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:28', '::1', NULL),
('67f437c8e3706', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:32', '::1', NULL),
('67f437cf1e600', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:39', '::1', NULL),
('67f437d153f8a', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:41', '::1', NULL),
('67f437d6ea995', 'suad-001', 'Accedió a reportes globales', '2025-04-07 16:38:46', '::1', NULL),
('67f437d848701', 'suad-001', 'Cierre de sesión', '2025-04-07 16:38:48', '::1', NULL),
('67f437d9e81bc', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-07 16:38:49', '::1', NULL),
('67f437dd73bc2', 'suad-001', 'Accedió a gestión de administradores', '2025-04-07 16:38:53', '::1', NULL),
('67f437df89d80', 'suad-001', 'Cierre de sesión', '2025-04-07 16:38:55', '::1', NULL),
('67f437e92e412', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-07 16:39:05', '::1', NULL),
('67f437f2cb0b7', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-04-07 16:39:14', '::1', NULL),
('67f437f7e8826', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-07 16:39:19', '::1', NULL),
('67f437fda3ca4', 'c45c76ea-13ee-11f0-9', 'Accedió a votaciones de diputados', '2025-04-07 16:39:25', '::1', NULL),
('67f438054652c', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-07 16:39:33', '::1', NULL),
('67f43806775a7', 'c45c76ea-13ee-11f0-9', 'Accedió a la sección de reportes', '2025-04-07 16:39:34', '::1', NULL),
('67f43808c828e', 'c45c76ea-13ee-11f0-9', 'Accedió a la sección de reportes', '2025-04-07 16:39:36', '::1', NULL),
('67f438119e650', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-07 16:39:45', '::1', NULL),
('67f4381379915', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-07 16:39:47', '::1', NULL),
('67f438155286a', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-04-07 16:39:49', '::1', NULL),
('67f438196ab77', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-07 16:39:53', '::1', NULL),
('67f66201b8019', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:03:13', '::1', NULL),
('67f662061a13c', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:03:18', '::1', NULL),
('67f6620774467', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:03:19', '::1', NULL),
('67f6620a05f57', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:03:22', '::1', NULL),
('67f6620b19eaf', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:03:23', '::1', NULL),
('67f6620c8d99f', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-09 08:03:24', '::1', NULL),
('67f662760e327', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:05:10', '::1', NULL),
('67f66277909fe', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-09 08:05:11', '::1', NULL),
('67f66279858da', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-09 08:05:13', '::1', NULL),
('67f6627c7cdd2', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-09 08:05:16', '::1', NULL),
('67f9776697838', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-11 16:11:18', '::1', NULL),
('67f9776b40d6a', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-11 16:11:23', '::1', NULL),
('67f9776dacea1', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:11:25', '::1', NULL),
('67f97782cdf9d', 'suad-001', 'Accedió a gestión de administradores', '2025-04-11 16:11:46', '::1', NULL),
('67f9778b4d6e6', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:11:55', '::1', NULL),
('67f97792b03f9', 'suad-001', 'Cierre de sesión', '2025-04-11 16:12:02', '::1', NULL),
('67f980d12d8ed', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:51:29', '::1', NULL),
('67f980d4b0cc2', 'suad-001', 'Accedió a gestión de administradores', '2025-04-11 16:51:32', '::1', NULL),
('67f980dfe0017', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:51:43', '::1', NULL),
('67f980e0d6bea', 'suad-001', 'Accedió a votaciones electorales', '2025-04-11 16:51:44', '::1', NULL),
('67f980efa4c95', 'suad-001', 'Accedió a votaciones electorales', '2025-04-11 16:51:59', '::1', NULL),
('67f980f8e6759', 'suad-001', 'Cambió estado de votación ID: 1759ab8e-13f0-11f0-9 a finalizada', '2025-04-11 16:52:08', '::1', NULL),
('67f980f8e6fd3', 'suad-001', 'Accedió a votaciones electorales', '2025-04-11 16:52:08', '::1', NULL),
('67f980fc6ff5f', 'suad-001', 'Cambió estado de votación ID: 0d63f3a6-13f0-11f0-9 a finalizada', '2025-04-11 16:52:12', '::1', NULL),
('67f980fc7055a', 'suad-001', 'Accedió a votaciones electorales', '2025-04-11 16:52:12', '::1', NULL),
('67f980fe1887f', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:52:14', '::1', NULL),
('67f980feddd07', 'suad-001', 'Accedió al panel de reclamos', '2025-04-11 16:52:14', '::1', NULL),
('67f980fff3932', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-11 16:52:15', '::1', NULL),
('67f98100912f1', 'suad-001', 'Accedió a reportes globales', '2025-04-11 16:52:16', '::1', NULL),
('67f98104af165', 'suad-001', 'Accedió a reportes globales', '2025-04-11 16:52:20', '::1', NULL),
('67f9810726548', 'suad-001', 'Accedió a reportes globales', '2025-04-11 16:52:23', '::1', NULL),
('67f981f0bc968', 'suad-001', 'Cierre de sesión', '2025-04-11 16:56:16', '::1', NULL),
('680bf0c7681c5', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-25 16:29:59', '::1', NULL),
('680bf0c9d504d', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-25 16:30:01', '::1', NULL),
('680bf0cbec443', 'suad-001', 'Cierre de sesión', '2025-04-25 16:30:03', '::1', NULL),
('680bf0d10f7ab', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-25 16:30:09', '::1', NULL),
('680bf0d37ad4e', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-04-25 16:30:11', '::1', NULL),
('680bf0d70831b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-25 16:30:15', '::1', NULL),
('680bf0e634a24', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-04-25 16:30:30', '::1', NULL),
('680bf0e83a168', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-25 16:30:32', '::1', NULL),
('680bf0e8d6875', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-25 16:30:32', '::1', NULL),
('680bf0ed4bcfc', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-25 16:30:37', '::1', NULL),
('68100c880f273', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:17:28', '::1', NULL),
('68100c92e829c', 'suad-001', 'Accedió a gestión de administradores', '2025-04-28 19:17:38', '::1', NULL),
('68100c9849c48', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:17:44', '::1', NULL),
('68100c9a10e24', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:17:46', '::1', NULL),
('68100ca607217', 'suad-001', 'Cambió estado de votación ID: 0d63f3a6-13f0-11f0-9 a activa', '2025-04-28 19:17:58', '::1', NULL),
('68100ca6079e0', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:17:58', '::1', NULL),
('68100cad3c95a', 'suad-001', 'Cambió estado de votación ID: 0d63f3a6-13f0-11f0-9 a finalizada', '2025-04-28 19:18:05', '::1', NULL),
('68100cad3cfcf', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:18:05', '::1', NULL),
('68100cd2c1644', 'suad-001', 'Creó votación electoral: elecciones 2025', '2025-04-28 19:18:42', '::1', NULL),
('68100cd2c21f8', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:18:42', '::1', NULL),
('68100cda04c0b', 'suad-001', 'Cambió estado de votación ID: 20335149-2487-11f0-b a pendiente', '2025-04-28 19:18:50', '::1', NULL),
('68100cda063ef', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:18:50', '::1', NULL),
('68100ce388d23', 'suad-001', 'Cambió estado de votación ID: 20335149-2487-11f0-b a pendiente', '2025-04-28 19:18:59', '::1', NULL),
('68100ce389818', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:18:59', '::1', NULL),
('68100ce60abf9', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:19:02', '::1', NULL),
('68100ce848e59', 'suad-001', 'Accedió a reportes globales', '2025-04-28 19:19:04', '::1', NULL),
('68100cee78846', 'suad-001', 'Accedió a reportes globales', '2025-04-28 19:19:10', '::1', NULL),
('68100cf5cb559', 'suad-001', 'Accedió a reportes globales', '2025-04-28 19:19:17', '::1', NULL),
('68100cfad348b', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:19:22', '::1', NULL),
('68100cfe27e89', 'suad-001', 'Accedió a votaciones electorales', '2025-04-28 19:19:26', '::1', NULL),
('68100d05c36a8', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:19:33', '::1', NULL),
('68100d07042ed', 'suad-001', 'Accedió al panel de reclamos', '2025-04-28 19:19:35', '::1', NULL),
('68100d09893b5', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-28 19:19:37', '::1', NULL),
('68100d0abe56d', 'suad-001', 'Cierre de sesión', '2025-04-28 19:19:38', '::1', NULL),
('68100d0f94fbc', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-28 19:19:43', '::1', NULL),
('68100d1ace56a', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-28 19:19:54', '::1', NULL),
('68100d1f22daf', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-28 19:19:59', '::1', NULL),
('68100d2590522', 'c45c76ea-13ee-11f0-9', 'Accedió a la sección de reportes', '2025-04-28 19:20:05', '::1', NULL),
('68100d2b0a892', 'c45c76ea-13ee-11f0-9', 'Accedió a la sección de reportes', '2025-04-28 19:20:11', '::1', NULL),
('68100d353611d', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-28 19:20:21', '::1', NULL),
('68100d3737b19', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-04-28 19:20:23', '::1', NULL),
('68100d3cdc42c', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-28 19:20:28', '::1', NULL),
('68100d3f01f9a', 'c45c76ea-13ee-11f0-9', 'Accedió a votaciones de diputados', '2025-04-28 19:20:31', '::1', NULL),
('68100d46a74c6', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-04-28 19:20:38', '::1', NULL),
('68100d49c84f3', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-04-28 19:20:41', '::1', NULL),
('68122efb74360', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:08:59', '::1', NULL),
('68122f01b26be', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:09:05', '::1', NULL),
('68122f499e608', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:10:17', '::1', NULL),
('68122f570004a', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:10:31', '::1', NULL),
('68122f57e06c6', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:10:31', '::1', NULL),
('68122f72061f5', 'suad-001', 'Creó votación electoral: elecciones 2025', '2025-04-30 10:10:58', '::1', NULL),
('68122f72078b8', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:10:58', '::1', NULL),
('68122fd195958', 'suad-001', 'Creó votación electoral: elecciones 2025', '2025-04-30 10:12:33', '::1', NULL),
('68122fd198c60', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:12:33', '::1', NULL),
('68122fe3ada48', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:12:51', '::1', NULL),
('68122fec674de', 'suad-001', 'Cambió estado de votación ID: 2912db1a-25cd-11f0-b a activa', '2025-04-30 10:13:00', '::1', NULL),
('68122fec68923', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:13:00', '::1', NULL),
('68122fee12de9', 'suad-001', 'Cierre de sesión', '2025-04-30 10:13:02', '::1', NULL),
('68122ff2cd46e', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:13:06', '::1', NULL),
('68122ffc35883', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:13:16', '::1', NULL),
('68123004190d6', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:13:24', '::1', NULL),
('681230092a6af', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:13:29', '::1', NULL),
('681230151bd9c', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:13:41', '::1', NULL),
('68123017eec1d', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:13:43', '::1', NULL),
('681230269655f', 'suad-001', 'Cambió estado de votación ID: 2912db1a-25cd-11f0-b a activa', '2025-04-30 10:13:58', '::1', NULL),
('6812302697f7d', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:13:58', '::1', NULL),
('68123028e358f', 'suad-001', 'Cierre de sesión', '2025-04-30 10:14:00', '::1', NULL),
('6812302d8173c', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:14:05', '::1', NULL),
('68123143d918c', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:18:43', '::1', NULL),
('6812314b90825', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:18:51', '::1', NULL),
('6812314fa3754', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:18:55', '::1', NULL),
('6812315b0c03e', 'suad-001', 'Cambió estado de votación ID: 2912db1a-25cd-11f0-b a activa', '2025-04-30 10:19:07', '::1', NULL),
('6812315b0ce73', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:19:07', '::1', NULL),
('6812315f18258', 'suad-001', 'Cierre de sesión', '2025-04-30 10:19:11', '::1', NULL),
('68123165a0df8', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:19:17', '::1', NULL),
('68123176e5652', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:19:34', '::1', NULL),
('681231787b117', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:19:36', '::1', NULL),
('6812317b8242b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:19:39', '::1', NULL),
('6812317e46300', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-04-30 10:19:42', '::1', NULL),
('6812317fd9a5b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:19:43', '::1', NULL),
('68123181892f5', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:19:45', '::1', NULL),
('681231847ee51', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:19:48', '::1', NULL),
('681231870b061', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:19:51', '::1', NULL),
('6812318c2401f', 'suad-001', 'Cambió estado de votación ID: 2912db1a-25cd-11f0-b a finalizada', '2025-04-30 10:19:56', '::1', NULL),
('6812318c253f9', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:19:56', '::1', NULL),
('681231ab79c7f', 'suad-001', 'Creó votación electoral: elecciones 2026', '2025-04-30 10:20:27', '::1', NULL),
('681231ab7c396', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:20:27', '::1', NULL),
('681231b6c14a3', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:20:38', '::1', NULL),
('681231c2043db', 'suad-001', 'Cierre de sesión', '2025-04-30 10:20:50', '::1', NULL),
('681231c5c28c4', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:20:53', '::1', NULL),
('681231c81e33a', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:20:56', '::1', NULL),
('681231c8ca810', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:20:56', '::1', NULL),
('681231e8cfaad', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:21:28', '::1', NULL),
('681231ea472f2', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:21:30', '::1', NULL),
('681231eabb22c', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:21:30', '::1', NULL),
('681231eae19b5', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:21:30', '::1', NULL),
('681231eb11e4b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:21:31', '::1', NULL),
('6812340851534', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:30:32', '::1', NULL),
('681234181624f', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-04-30 10:30:48', '::1', NULL),
('6812341a1f898', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:30:50', '::1', NULL),
('6812341debdfa', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:30:53', '::1', NULL),
('6812341f0237a', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:30:55', '::1', NULL),
('681234232b9b2', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:30:59', '::1', NULL),
('681234249295d', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:31:00', '::1', NULL),
('6812343632bf0', 'suad-001', 'Creó votación electoral: elecciones 2027', '2025-04-30 10:31:18', '::1', NULL),
('6812343634611', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:31:18', '::1', NULL),
('6812344162d96', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:31:29', '::1', NULL),
('68123443e192b', 'suad-001', 'Cierre de sesión', '2025-04-30 10:31:31', '::1', NULL),
('68123447890ba', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:35', '::1', NULL),
('6812344a9e17e', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:38', '::1', NULL),
('6812344b50fea', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:39', '::1', NULL),
('6812344bc0f8b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:39', '::1', NULL),
('6812344be7db7', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:39', '::1', NULL),
('6812344c2156d', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:40', '::1', NULL),
('6812344c46f6a', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:40', '::1', NULL),
('6812344cb3c1c', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:40', '::1', NULL),
('6812344cd54e3', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:40', '::1', NULL),
('6812344d01135', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:31:41', '::1', NULL),
('6812345d80242', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:31:57', '::1', NULL),
('68123460a1f64', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:32:00', '::1', NULL),
('6812346234a82', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:32:02', '::1', NULL),
('68123466cdc7e', 'suad-001', 'Cambió estado de votación ID: 20335149-2487-11f0-b a activa', '2025-04-30 10:32:06', '::1', NULL),
('68123466cf8de', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:32:06', '::1', NULL),
('681234847c96f', 'suad-001', 'Cambió estado de votación ID: 43881bc6-25ce-11f0-b a activa', '2025-04-30 10:32:36', '::1', NULL),
('6812348487fb1', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:32:36', '::1', NULL),
('68123485ef583', 'suad-001', 'Cierre de sesión', '2025-04-30 10:32:37', '::1', NULL),
('6812348928a79', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:32:41', '::1', NULL),
('6812348fdfb76', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:32:47', '::1', NULL),
('6812349385156', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:32:51', '::1', NULL),
('6812349534e97', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:32:53', '::1', NULL),
('6812349df3687', 'suad-001', 'Cambió estado de votación ID: c762791d-25cf-11f0-b a activa', '2025-04-30 10:33:01', '::1', NULL),
('6812349e02f9d', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:33:02', '::1', NULL),
('681234a4df342', 'suad-001', 'Cierre de sesión', '2025-04-30 10:33:08', '::1', NULL),
('681234a82b2e0', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:33:12', '::1', NULL),
('681234a9a53c0', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-04-30 10:33:13', '::1', NULL),
('681234ac368da', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:33:16', '::1', NULL),
('681234b02e910', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-04-30 10:33:20', '::1', NULL),
('681234b190ca8', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:33:21', '::1', NULL),
('681234b2d78a0', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-04-30 10:33:22', '::1', NULL),
('681234b64987c', '572b95ad-125a-11f0-a', 'Votó en la elección: elecciones 2025', '2025-04-30 10:33:26', '::1', NULL),
('681234b64a930', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-04-30 10:33:26', '::1', NULL),
('681234b86218f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:33:28', '::1', NULL),
('681234b9b5f1b', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-04-30 10:33:29', '::1', NULL),
('681234bbb2143', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:33:31', '::1', NULL),
('681234bf0e409', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:33:35', '::1', NULL),
('681234c496db5', 'suad-001', 'Accedió a reportes globales', '2025-04-30 10:33:40', '::1', NULL),
('681234cad6210', 'suad-001', 'Accedió a reportes globales', '2025-04-30 10:33:46', '::1', NULL),
('681234d696ddf', 'suad-001', 'Cierre de sesión', '2025-04-30 10:33:58', '::1', NULL),
('681234d9afe2b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:34:01', '::1', NULL),
('6812355553919', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:36:05', '::1', NULL),
('68123557d3643', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-04-30 10:36:07', '::1', NULL),
('68123558eeabf', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:36:08', '::1', NULL),
('68123559c21fb', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-04-30 10:36:09', '::1', NULL),
('6812355af15e2', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-04-30 10:36:10', '::1', NULL),
('6812355c86d80', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-04-30 10:36:12', '::1', NULL),
('6812355f08b5d', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:36:15', '::1', NULL),
('68123563e6c33', 'suad-001', 'Accedió a gestión de administradores', '2025-04-30 10:36:19', '::1', NULL),
('681235661d719', 'suad-001', 'Cierre de sesión', '2025-04-30 10:36:22', '::1', NULL),
('681235683984f', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:36:24', '::1', NULL),
('6812356aa5fe4', 'suad-001', 'Accedió a votaciones electorales', '2025-04-30 10:36:26', '::1', NULL),
('6812356c405a4', 'suad-001', 'Acceso al panel de superadministrador', '2025-04-30 10:36:28', '::1', NULL),
('6812356da4680', 'suad-001', 'Accedió a reportes globales', '2025-04-30 10:36:29', '::1', NULL),
('681235718dc7d', 'suad-001', 'Accedió a reportes globales', '2025-04-30 10:36:33', '::1', NULL),
('68123579559e7', 'suad-001', 'Accedió a reportes globales', '2025-04-30 10:36:41', '::1', NULL),
('681a9a381d29d', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:24:40', '::1', NULL),
('681a9a3b30a35', 'suad-001', 'Cierre de sesión', '2025-05-06 19:24:43', '::1', NULL),
('681a9a443972d', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:24:52', '::1', NULL),
('681a9e870d55b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:43:03', '::1', NULL),
('681a9e8f06a75', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:43:11', '::1', NULL),
('681a9e9263608', '572b95ad-125a-11f0-a', 'Accedió a la página de perfil', '2025-05-06 19:43:14', '::1', NULL),
('681a9e9b832fa', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:43:23', '::1', NULL),
('681a9e9eb5ba6', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:43:26', '::1', NULL),
('681a9e9f8525e', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:43:27', '::1', NULL),
('681a9ea0a4567', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:43:28', '::1', NULL),
('681a9ea12f8ac', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:43:29', '::1', NULL),
('681a9ea1cb787', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-05-06 19:43:29', '::1', NULL),
('681a9ea342322', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:43:31', '::1', NULL),
('681a9ea4ae8ba', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-05-06 19:43:32', '::1', NULL),
('681a9ea62a781', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:43:34', '::1', NULL),
('681a9ec99a077', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:09', '::1', NULL),
('681a9eca24cc1', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:10', '::1', NULL),
('681a9ed3c73a5', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:19', '::1', NULL),
('681a9eef6f55f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:47', '::1', NULL),
('681a9ef2b8641', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:50', '::1', NULL),
('681a9ef3dc209', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:51', '::1', NULL),
('681a9ef454096', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:52', '::1', NULL),
('681a9ef4790d6', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:52', '::1', NULL),
('681a9ef4a63c6', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:52', '::1', NULL),
('681a9ef4ced05', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:52', '::1', NULL),
('681a9ef4eb088', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:52', '::1', NULL),
('681a9ef58a84b', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:53', '::1', NULL),
('681a9ef60cb33', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:54', '::1', NULL),
('681a9ef75372f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:44:55', '::1', NULL),
('681a9f045c748', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:45:08', '::1', NULL),
('681a9f04b7aff', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:45:08', '::1', NULL),
('681a9f28a4b45', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:45:44', '::1', NULL),
('681a9f2924db0', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:45:45', '::1', NULL),
('681a9f2a07aaa', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 19:45:46', '::1', NULL),
('681a9f2c1a27e', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:45:48', '::1', NULL),
('681a9f2eeaafb', '572b95ad-125a-11f0-a', 'Accedió a la página de perfil', '2025-05-06 19:45:50', '::1', NULL),
('681a9f303d9e3', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:45:52', '::1', NULL),
('681a9f30e113e', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-05-06 19:45:52', '::1', NULL),
('681a9f349448a', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-05-06 19:45:56', '::1', NULL),
('681a9f34cd35c', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-05-06 19:45:56', '::1', NULL),
('681a9f3ee5eee', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 19:46:06', '::1', NULL),
('681a9f42c11b9', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:46:10', '::1', NULL),
('681a9f49b7bdb', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 19:46:17', '::1', NULL),
('681a9f4d0017a', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 19:46:21', '::1', NULL),
('681a9f66ea77f', 'suad-001', 'Creó votación electoral: elecciones 2027', '2025-05-06 19:46:46', '::1', NULL),
('681a9f66eaf9a', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 19:46:46', '::1', NULL),
('681a9f7ba23c4', 'suad-001', 'Cambió estado de votación ID: 5f591f52-2ad4-11f0-a a activa', '2025-05-06 19:47:07', '::1', NULL),
('681a9f7ba2dec', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 19:47:07', '::1', NULL),
('681a9f8f70435', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 19:47:27', '::1', NULL),
('681a9f976a3b3', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:47:35', '::1', NULL),
('681a9f99a750a', 'suad-001', 'Cierre de sesión', '2025-05-06 19:47:37', '::1', NULL),
('681a9f9cabd81', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:47:40', '::1', NULL),
('681a9fa0226c4', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 19:47:44', '::1', NULL),
('681a9fa757807', '572b95ad-125a-11f0-a', 'Votó en la elección: elecciones 2027', '2025-05-06 19:47:51', '::1', NULL),
('681a9fa757df5', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 19:47:51', '::1', NULL),
('681a9fa8e280f', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:47:52', '::1', NULL),
('681a9faa64b8e', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 19:47:54', '::1', NULL),
('681a9fab77396', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:47:55', '::1', NULL),
('681a9fac720b0', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-05-06 19:47:56', '::1', NULL),
('681a9fae14369', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:47:58', '::1', NULL),
('681a9fb53ea64', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 19:48:05', '::1', NULL),
('681a9fbe90924', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:48:14', '::1', NULL),
('681a9fc01584c', '572b95ad-125a-11f0-a', 'Accedió a la página de perfil', '2025-05-06 19:48:16', '::1', NULL),
('681a9fc13147f', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 19:48:17', '::1', NULL),
('681a9fc5dc14e', '572b95ad-125a-11f0-a', 'Accedió a crear votación simple', '2025-05-06 19:48:21', '::1', NULL),
('681a9fca22895', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 19:48:26', '::1', NULL),
('681a9fcdf2d35', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 19:48:29', '::1', NULL),
('681a9fd6cd748', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:48:38', '::1', NULL),
('681aa242739d8', 'suad-001', 'Cierre de sesión', '2025-05-06 19:58:58', '::1', NULL),
('681aa2473d3ef', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:59:03', '::1', NULL),
('681aa24cb8f9b', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 19:59:08', '::1', NULL),
('681aa252c6946', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:59:14', '::1', NULL),
('681aa2547db59', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 19:59:16', '::1', NULL),
('681aa25920d50', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 19:59:21', '::1', NULL),
('681aa25a7ddc7', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 19:59:22', '::1', NULL),
('681aa298c64f8', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:00:24', '::1', NULL),
('681aa2a3d71a8', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:00:35', '::1', NULL),
('681aa2a6a7810', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:00:38', '::1', NULL),
('681aa2a8695bd', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:00:40', '::1', NULL),
('681aa2aa16199', 'suad-001', 'Accedió al panel de reclamos', '2025-05-06 20:00:42', '::1', NULL),
('681aa2ae1f0b4', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:00:46', '::1', NULL),
('681aa37952377', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:04:09', '::1', NULL),
('681aa37a163d1', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:04:10', '::1', NULL),
('681aa37aa0277', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:04:10', '::1', NULL),
('681aa37b101f0', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:04:11', '::1', NULL),
('681aa37bd5bd4', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:04:11', '::1', NULL),
('681aa3ad78aed', 'suad-001', 'Creó votación de alcaldía: Alcaldia La Paz 2025 para ciudad ID: ci-1', '2025-05-06 20:05:01', '::1', NULL),
('681aa3ad7af93', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:05:01', '::1', NULL),
('681aa3b7f1f56', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:05:11', '::1', NULL),
('681aa3bcab700', 'suad-001', 'Cambió estado de votación de alcaldía ID: ebbdb8f4-2ad6-11f0-a a activa', '2025-05-06 20:05:16', '::1', NULL),
('681aa3bcae459', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:05:16', '::1', NULL),
('681aa3c6df840', 'suad-001', 'Cierre de sesión', '2025-05-06 20:05:26', '::1', NULL),
('681aa3cb74f85', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:05:31', '::1', NULL),
('681aa3ce0ade1', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: Alcaldia La Paz 2025', '2025-05-06 20:05:34', '::1', NULL),
('681aa3d04dddb', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:05:36', '::1', NULL),
('681aa3d8997b0', '572b95ad-125a-11f0-a', 'Accedió a la página de perfil', '2025-05-06 20:05:44', '::1', NULL),
('681aa3dbed027', '572b95ad-125a-11f0-a', 'Accedió a votaciones activas', '2025-05-06 20:05:47', '::1', NULL),
('681aa3dcc1555', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:05:48', '::1', NULL),
('681aa41738cc4', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 20:06:47', '::1', NULL),
('681aa41c7a638', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:06:52', '::1', NULL),
('681aa41e7e5fc', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:06:54', '::1', NULL),
('681aa6b5ca135', 'suad-001', 'Cierre de sesión', '2025-05-06 20:17:57', '::1', NULL),
('681aa6ba750b4', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:18:02', '::1', NULL),
('681aa6bcb3874', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:18:04', '::1', NULL),
('681aa6c5c10b0', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:18:13', '::1', NULL),
('681aa6c9218f6', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:18:17', '::1', NULL),
('681aa6cacb88d', 'suad-001', 'Accedió al panel de reclamos', '2025-05-06 20:18:18', '::1', NULL),
('681aa6ce74809', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:18:22', '::1', NULL),
('681aa6d00b5a8', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:18:24', '::1', NULL),
('681aa6d105367', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:18:25', '::1', NULL),
('681aa6d29f1a0', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:18:26', '::1', NULL),
('681aa81bc8c4e', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:23:55', '::1', NULL),
('681aa81c8468d', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:23:56', '::1', NULL),
('681aa81f64bc9', 'suad-001', 'Accedió a asignar candidatos para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:23:59', '::1', NULL),
('681aa84381aa4', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:24:35', '::1', NULL),
('681aa85f693c2', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:25:03', '::1', NULL),
('681aa863c4ff7', 'suad-001', 'Accedió a asignar candidatos para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:25:07', '::1', NULL),
('681aa86b75cb1', 'suad-001', 'Accedió a asignar candidatos para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:25:15', '::1', NULL),
('681aa87b36f84', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:25:31', '::1', NULL),
('681aa87f0129a', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:25:35', '::1', NULL),
('681aa888a0455', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:25:44', '::1', NULL),
('681aa88d7fdb5', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:25:49', '::1', NULL),
('681aa8924bbff', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 20:25:54', '::1', NULL),
('681aa896675cd', 'suad-001', 'Accedió a asignar candidatos para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:25:58', '::1', NULL),
('681aa8b03f230', 'suad-001', 'Creó nuevo candidato para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:26:24', '::1', NULL),
('681aa8b0485c8', 'suad-001', 'Accedió a asignar candidatos para elección ID: 5f591f52-2ad4-11f0-a', '2025-05-06 20:26:24', '::1', NULL),
('681aa8bf9e5db', 'suad-001', 'Cierre de sesión', '2025-05-06 20:26:39', '::1', NULL),
('681aa8c2aec45', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:26:42', '::1', NULL),
('681aa8c6f04f4', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:26:46', '::1', NULL),
('681aa8c804af1', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:26:48', '::1', NULL),
('681aa8c9a844a', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 20:26:49', '::1', NULL),
('681aa8cd4422f', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:26:53', '::1', NULL),
('681aa8cfe1c00', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:26:55', '::1', NULL),
('681aa8d5cf444', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:01', '::1', NULL),
('681aa8da41eaa', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:06', '::1', NULL),
('681aa8df75449', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:11', '::1', NULL),
('681aa901ce509', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:45', '::1', NULL),
('681aa902e67a3', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:46', '::1', NULL),
('681aa90387a02', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:27:47', '::1', NULL),
('681aa91201aa7', 'suad-001', 'Cierre de sesión', '2025-05-06 20:28:02', '::1', NULL),
('681aa913ef1f6', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:28:03', '::1', NULL),
('681aa914dee41', 'suad-001', 'Cierre de sesión', '2025-05-06 20:28:04', '::1', NULL),
('681aa916e86e4', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:28:06', '::1', NULL),
('681aa917f0f72', 'suad-001', 'Cierre de sesión', '2025-05-06 20:28:07', '::1', NULL),
('681aa9198e729', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:28:09', '::1', NULL),
('681aa91bd1891', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 20:28:11', '::1', NULL),
('681aa91f85055', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:28:15', '::1', NULL),
('681aaa3ea8b94', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:33:02', '::1', NULL),
('681aaa3f34c71', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:33:03', '::1', NULL),
('681aaa45d2e70', 'suad-001', 'Cierre de sesión', '2025-05-06 20:33:09', '::1', NULL),
('681aaa606dc75', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:33:36', '::1', NULL),
('681aaa628a0b6', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:33:38', '::1', NULL),
('681aaa65b2dfb', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:33:41', '::1', NULL),
('681aaa689a040', 'suad-001', 'Cierre de sesión', '2025-05-06 20:33:44', '::1', NULL),
('681aaa6d281ed', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-06 20:33:49', '::1', NULL),
('681aaa6f4aad0', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:33:51', '::1', NULL),
('681aaa75b7ddd', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-019 a habilitado', '2025-05-06 20:33:57', '::1', NULL),
('681aaa75ba7f6', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:33:57', '::1', NULL),
('681aaa77a8e48', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-019 a habilitado', '2025-05-06 20:33:59', '::1', NULL),
('681aaa77ab692', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:33:59', '::1', NULL),
('681aaa784d7f4', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: 572b95ad-125a-11f0-a a habilitado', '2025-05-06 20:34:00', '::1', NULL),
('681aaa78502e9', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:00', '::1', NULL),
('681aaa7964a56', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-004 a habilitado', '2025-05-06 20:34:01', '::1', NULL),
('681aaa79672b1', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:01', '::1', NULL),
('681aaa7c6a8ef', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-004 a nulo', '2025-05-06 20:34:04', '::1', NULL),
('681aaa7c6d327', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:04', '::1', NULL),
('681aaa7da679f', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-003 a habilitado', '2025-05-06 20:34:05', '::1', NULL),
('681aaa7da95c7', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:05', '::1', NULL),
('681aaa7ed0288', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-014 a habilitado', '2025-05-06 20:34:06', '::1', NULL),
('681aaa7ed2d07', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:06', '::1', NULL),
('681aaa7f6d16a', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-020 a habilitado', '2025-05-06 20:34:07', '::1', NULL),
('681aaa7f6ffbc', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:07', '::1', NULL),
('681aaa7fef23a', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: usr-010 a habilitado', '2025-05-06 20:34:07', '::1', NULL),
('681aaa7ff00b3', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:34:07', '::1', NULL),
('681aaa85244c4', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-05-06 20:34:13', '::1', NULL),
('681aab4ea35ea', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-06 20:37:34', '::1', NULL),
('681aab5030068', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:37:36', '::1', NULL),
('681aab5748777', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: 629ebd73-2adb-11f0-a a habilitado', '2025-05-06 20:37:43', '::1', NULL),
('681aab574b566', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:37:43', '::1', NULL),
('681aab66e2c7e', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-06 20:37:58', '::1', NULL),
('681aab7642cc7', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:38:14', '::1', NULL),
('681aab7907c0d', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-06 20:38:17', '::1', NULL);
INSERT INTO `log_auditoria` (`id_log`, `usuario`, `accion`, `fecha_hora`, `ip_origen`, `tipo_usuario`) VALUES
('681aab7dd5ec4', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-05-06 20:38:21', '::1', NULL),
('681aab816c79c', '629ebd73-2adb-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:38:25', '::1', NULL),
('681aab868c69b', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:38:30', '::1', NULL),
('681aab88c8f7f', '629ebd73-2adb-11f0-a', 'Votó en la elección: elecciones 2027', '2025-05-06 20:38:32', '::1', NULL),
('681aab88c9556', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:38:32', '::1', NULL),
('681aab8a25adf', '629ebd73-2adb-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:38:34', '::1', NULL),
('681aab8c2a8f5', '629ebd73-2adb-11f0-a', 'Cierre de sesión', '2025-05-06 20:38:36', '::1', NULL),
('681aab8f69f1f', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:38:39', '::1', NULL),
('681aab9189577', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:38:41', '::1', NULL),
('681aab963673e', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:38:46', '::1', NULL),
('681aab9ddfd5e', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:38:53', '::1', NULL),
('681aaba8e95d0', 'suad-001', 'Cierre de sesión', '2025-05-06 20:39:04', '::1', NULL),
('681aad339c875', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:45:39', '::1', NULL),
('681aad3f459b5', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:45:51', '::1', NULL),
('681aad4143d9c', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 20:45:53', '::1', NULL),
('681aad45ee451', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:45:57', '::1', NULL),
('681aad487b9e3', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:46:00', '::1', NULL),
('681aad4f560f5', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:46:07', '::1', NULL),
('681aad4fda01f', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:46:07', '::1', NULL),
('681aad507b4ec', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:46:08', '::1', NULL),
('681aad5225ec0', 'suad-001', 'Cierre de sesión', '2025-05-06 20:46:10', '::1', NULL),
('681aad53dc2d2', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-06 20:46:11', '::1', NULL),
('681aad55346d9', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:46:13', '::1', NULL),
('681aad5d93dc5', 'c45c76ea-13ee-11f0-9', 'Cambió estado del usuario ID: 91a74ae1-2adc-11f0-a a habilitado', '2025-05-06 20:46:21', '::1', NULL),
('681aad5d94276', 'c45c76ea-13ee-11f0-9', 'Accedió a gestión de usuarios', '2025-05-06 20:46:21', '::1', NULL),
('681aad5f6065a', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-05-06 20:46:23', '::1', NULL),
('681aad6b4236c', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:46:35', '::1', NULL),
('681aad6f83be8', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:46:39', '::1', NULL),
('681aad71612f7', '91a74ae1-2adc-11f0-a', 'Votó en la elección: elecciones 2027', '2025-05-06 20:46:41', '::1', NULL),
('681aad7161623', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:46:41', '::1', NULL),
('681aad7363427', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:46:43', '::1', NULL),
('681aad7439edf', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 20:46:44', '::1', NULL),
('681aad7571ebd', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-06 20:46:45', '::1', NULL),
('681aad76431bf', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-06 20:46:46', '::1', NULL),
('681aad8bda410', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 20:47:07', '::1', NULL),
('681aad8e35f83', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:47:10', '::1', NULL),
('681aad9151300', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:47:13', '::1', NULL),
('681aad9b6b15c', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:47:23', '::1', NULL),
('681aada866798', 'suad-001', 'Accedió a reportes globales', '2025-05-06 20:47:36', '::1', NULL),
('681aadbf22ddb', 'suad-001', 'Cierre de sesión', '2025-05-06 20:47:59', '::1', NULL),
('681ab31e102e5', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 21:10:54', '::1', NULL),
('681ab34f93ca7', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 21:11:43', '::1', NULL),
('681ab35082b19', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 21:11:44', '::1', NULL),
('681ab351ac57c', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-06 21:11:45', '::1', NULL),
('681ab354f1a3d', 'suad-001', 'Accedió a reportes globales', '2025-05-06 21:11:48', '::1', NULL),
('681ab35de81f7', 'suad-001', 'Cierre de sesión', '2025-05-06 21:11:57', '::1', NULL),
('681ab362acc17', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:12:02', '::1', NULL),
('681ab366e2c5c', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-06 21:12:06', '::1', NULL),
('681ab368b2ec5', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:12:08', '::1', NULL),
('681ab36a3cd29', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 21:12:10', '::1', NULL),
('681ab36d27013', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 21:12:13', '::1', NULL),
('681ab36f28e50', 'suad-001', 'Accedió a reportes globales', '2025-05-06 21:12:15', '::1', NULL),
('681ab372b28d1', 'suad-001', 'Accedió a reportes globales', '2025-05-06 21:12:18', '::1', NULL),
('681ab3774e9f8', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-06 21:12:23', '::1', NULL),
('681ab378c3002', 'suad-001', 'Accedió a gestión de administradores', '2025-05-06 21:12:24', '::1', NULL),
('681ab37b0c931', 'suad-001', 'Accedió a votaciones electorales', '2025-05-06 21:12:27', '::1', NULL),
('681ab390a974c', 'suad-001', 'Cierre de sesión', '2025-05-06 21:12:48', '::1', NULL),
('681ab39d3cf70', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:13:01', '::1', NULL),
('681ab39e5b246', '572b95ad-125a-11f0-a', 'Accedió a la página de votación: Alcaldia La Paz 2025', '2025-05-06 21:13:02', '::1', NULL),
('681ab3a054ba7', '572b95ad-125a-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:13:04', '::1', NULL),
('681ab3a5b71c6', '572b95ad-125a-11f0-a', 'Cierre de sesión', '2025-05-06 21:13:09', '::1', NULL),
('681ab3ad2b835', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:13:17', '::1', NULL),
('681ab3aeccd75', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-05-06 21:13:18', '::1', NULL),
('681ab3bd5126f', '91a74ae1-2adc-11f0-a', 'Votó en la elección: elecciones 2025', '2025-05-06 21:13:33', '::1', NULL),
('681ab3bd5165a', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2025', '2025-05-06 21:13:33', '::1', NULL),
('681ab3c015b72', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-06 21:13:36', '::1', NULL),
('681b56b147ac0', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 08:48:49', '::1', NULL),
('681b56b3dbd86', 'suad-001', 'Accedió a reportes globales', '2025-05-07 08:48:51', '::1', NULL),
('681b56b88f70a', 'suad-001', 'Accedió a reportes globales', '2025-05-07 08:48:56', '::1', NULL),
('681b56c242f95', 'suad-001', 'Accedió a reportes globales', '2025-05-07 08:49:06', '::1', NULL),
('681b56cd4732d', 'suad-001', 'Accedió a reportes globales', '2025-05-07 08:49:17', '::1', NULL),
('681b58eb03538', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 08:58:19', '::1', NULL),
('681b58ebd54aa', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 08:58:19', '::1', NULL),
('681b58ec50b58', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 08:58:20', '::1', NULL),
('681b58ec74c50', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 08:58:20', '::1', NULL),
('681b58edc3ee8', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 08:58:21', '::1', NULL),
('681b59626e2f4', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:00:18', '::1', NULL),
('681b59631ad11', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:00:19', '::1', NULL),
('681b5963500cf', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:00:19', '::1', NULL),
('681b596575962', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:00:21', '::1', NULL),
('681b598388647', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:00:51', '::1', NULL),
('681b5986adc72', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:00:54', '::1', NULL),
('681b598d3c536', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:01:01', '::1', NULL),
('681b59a5c54d2', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:01:25', '::1', NULL),
('681b59a9a02c7', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:01:29', '::1', NULL),
('681b59addf797', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-07 09:01:33', '::1', NULL),
('681b59b0bc6df', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:01:36', '::1', NULL),
('681b59b971d22', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:01:45', '::1', NULL),
('681b59ca23e28', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:02:02', '::1', NULL),
('681b59ce60ffe', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:02:06', '::1', NULL),
('681b59de0927f', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:02:22', '::1', NULL),
('681b59f0d836b', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:02:40', '::1', NULL),
('681b59f354a31', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:02:43', '::1', NULL),
('681b5d12ddf3c', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:02', '::1', NULL),
('681b5d1375a28', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:03', '::1', NULL),
('681b5d13a1ab0', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:03', '::1', NULL),
('681b5d13c722a', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:03', '::1', NULL),
('681b5d1422644', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:04', '::1', NULL),
('681b5d144cda2', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:04', '::1', NULL),
('681b5d146b61c', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:04', '::1', NULL),
('681b5d14b8e90', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:04', '::1', NULL),
('681b5d14df68b', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:04', '::1', NULL),
('681b5d1510888', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:05', '::1', NULL),
('681b5d1537c6e', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:05', '::1', NULL),
('681b5d15600d2', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:05', '::1', NULL),
('681b5d1692d05', 'suad-001', 'Accedió a votaciones electorales', '2025-05-07 09:16:06', '::1', NULL),
('681b5d1723296', 'suad-001', 'Accedió a gestión de administradores', '2025-05-07 09:16:07', '::1', NULL),
('681b5d2220aa2', 'suad-001', 'Accedió al panel de reclamos', '2025-05-07 09:16:18', '::1', NULL),
('681b5d2402ef8', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:16:20', '::1', NULL),
('681b5d25a9123', 'suad-001', 'Cierre de sesión', '2025-05-07 09:16:21', '::1', NULL),
('681b5d4a4b0ec', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:16:58', '::1', NULL),
('681b5d4cd5d5e', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:17:00', '::1', NULL),
('681b5d8858856', 'suad-001', 'Actualizó datos del usuario ID: usr-019', '2025-05-07 09:18:00', '::1', NULL),
('681b5d885b0e5', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:18:00', '::1', NULL),
('681b5d994b9e4', 'suad-001', 'Cierre de sesión', '2025-05-07 09:18:17', '::1', NULL),
('681b5da620a83', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:18:30', '::1', NULL),
('681b5de094cdd', '91a74ae1-2adc-11f0-a', 'Accedió a la página de perfil', '2025-05-07 09:19:28', '::1', NULL),
('681b5de46fe26', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:19:32', '::1', NULL),
('681b5dea41f5f', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-07 09:19:38', '::1', NULL),
('681b5ded143f1', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:19:41', '::1', NULL),
('681b5df53a0ca', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:19:49', '::1', NULL),
('681b5e15219cb', 'suad-001', 'Actualizó datos del usuario ID: 91a74ae1-2adc-11f0-a', '2025-05-07 09:20:21', '::1', NULL),
('681b5e1524781', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:20:21', '::1', NULL),
('681b5e1be54c3', 'suad-001', 'Cierre de sesión', '2025-05-07 09:20:27', '::1', NULL),
('681b5e21c0cc5', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:20:33', '::1', NULL),
('681b5e248af43', '91a74ae1-2adc-11f0-a', 'Accedió a la página de perfil', '2025-05-07 09:20:36', '::1', NULL),
('681b5e28dd19b', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:20:40', '::1', NULL),
('681b5e74912d2', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:21:56', '::1', NULL),
('681b5e75048ff', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:21:57', '::1', NULL),
('681b5e752b315', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:21:57', '::1', NULL),
('681b5e754d82c', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:21:57', '::1', NULL),
('681b5e7579c70', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:21:57', '::1', NULL),
('681b5e78dd7f6', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:22:00', '::1', NULL),
('681b5e7914d0a', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:22:01', '::1', NULL),
('681b5e793baeb', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:22:01', '::1', NULL),
('681b5e795c913', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:22:01', '::1', NULL),
('681b5e7c54927', '91a74ae1-2adc-11f0-a', 'Accedió a votaciones activas', '2025-05-07 09:22:04', '::1', NULL),
('681b5e8361a1e', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-07 09:22:11', '::1', NULL),
('681b5e8a497bd', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:22:18', '::1', NULL),
('681b5f9d280a3', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:26:53', '::1', NULL),
('681b5f9f82768', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:26:55', '::1', NULL),
('681b5f9faef2e', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:26:55', '::1', NULL),
('681b5f9fd4419', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 09:26:55', '::1', NULL),
('681b5fa09842f', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-07 09:26:56', '::1', NULL),
('681b5fa3449d6', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:26:59', '::1', NULL),
('681b5fcc51293', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:27:40', '::1', NULL),
('681b5fd00ecda', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:27:44', '::1', NULL),
('681b5fd32930d', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:27:47', '::1', NULL),
('681b6067a32cb', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:30:15', '::1', NULL),
('681b60695989f', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:30:17', '::1', NULL),
('681b606c2fb10', 'suad-001', 'Accedió a reportes globales', '2025-05-07 09:30:20', '::1', NULL),
('681b606e185a1', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 09:30:22', '::1', NULL),
('681b607f92543', 'suad-001', 'Cierre de sesión', '2025-05-07 09:30:39', '::1', NULL),
('681b609c27cab', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 09:31:08', '::1', NULL),
('681b67793efe9', 'suad-001', 'Cierre de sesión', '2025-05-07 10:00:25', '::1', NULL),
('681bceeaad35e', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 17:21:46', '::1', NULL),
('681bceff21918', 'suad-001', 'Accedió a votaciones electorales', '2025-05-07 17:22:07', '::1', NULL),
('681bcf2bae5b9', 'suad-001', 'Creó votación electoral: Presidencia 2025', '2025-05-07 17:22:51', '::1', NULL),
('681bcf2baf1f0', 'suad-001', 'Accedió a votaciones electorales', '2025-05-07 17:22:51', '::1', NULL),
('681bcf33282bb', 'suad-001', 'Accedió a asignar candidatos para elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:22:59', '::1', NULL),
('681bcf5b223df', 'suad-001', 'Asignó candidato ID: e853ee82-2ad9-11f0-a a elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:23:39', '::1', NULL),
('681bcf5b29d5e', 'suad-001', 'Accedió a asignar candidatos para elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:23:39', '::1', NULL),
('681bcf6346e27', 'suad-001', 'Asignó candidato ID: 4a4218f0-25ce-11f0-b a elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:23:47', '::1', NULL),
('681bcf6352437', 'suad-001', 'Accedió a asignar candidatos para elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:23:47', '::1', NULL),
('681bcfa50b114', 'suad-001', 'Creó nuevo candidato para elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:24:53', '::1', NULL),
('681bcfa51615b', 'suad-001', 'Accedió a asignar candidatos para elección ID: 6ec0bda0-2b89-11f0-9', '2025-05-07 17:24:53', '::1', NULL),
('681bcfb37b984', 'suad-001', 'Cierre de sesión', '2025-05-07 17:25:07', '::1', NULL),
('681bcfb789efa', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:25:11', '::1', NULL),
('681bcfc1d110c', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: elecciones 2027', '2025-05-07 17:25:21', '::1', NULL),
('681bcfc3abf69', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:25:23', '::1', NULL),
('681bcfc6320d7', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-07 17:25:26', '::1', NULL),
('681bcfc978ac2', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 17:25:29', '::1', NULL),
('681bcfcb3963a', 'suad-001', 'Accedió a votaciones electorales', '2025-05-07 17:25:31', '::1', NULL),
('681bcfd0c4ab1', 'suad-001', 'Cambió estado de votación ID: 6ec0bda0-2b89-11f0-9 a activa', '2025-05-07 17:25:36', '::1', NULL),
('681bcfd0c6883', 'suad-001', 'Accedió a votaciones electorales', '2025-05-07 17:25:36', '::1', NULL),
('681bcfdf45e1a', 'suad-001', 'Cierre de sesión', '2025-05-07 17:25:51', '::1', NULL),
('681bcfe25bb7f', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:25:54', '::1', NULL),
('681bcfea2b8bb', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-07 17:26:02', '::1', NULL),
('681bcff221cea', '91a74ae1-2adc-11f0-a', 'Votó en la elección: Presidencia 2025', '2025-05-07 17:26:10', '::1', NULL),
('681bcff222529', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-07 17:26:10', '::1', NULL),
('681bcff4e3aff', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:26:12', '::1', NULL),
('681bcff824194', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-07 17:26:16', '::1', NULL),
('681bcffcc54cc', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:26:20', '::1', NULL),
('681bd0059fb88', '91a74ae1-2adc-11f0-a', 'Accedió a la página de perfil', '2025-05-07 17:26:29', '::1', NULL),
('681bd0071f305', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:26:31', '::1', NULL),
('681bd00877dcb', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-07 17:26:32', '::1', NULL),
('681bd00d05696', '629ebd73-2adb-11f0-a', 'Acceso al panel de usuario', '2025-05-07 17:26:37', '::1', NULL),
('681bd00fece33', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-07 17:26:39', '::1', NULL),
('681bd03a22670', '629ebd73-2adb-11f0-a', 'Votó en la elección: Presidencia 2025', '2025-05-07 17:27:22', '::1', NULL),
('681bd03a231f4', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-07 17:27:22', '::1', NULL),
('681bd041001a9', '629ebd73-2adb-11f0-a', 'Cierre de sesión', '2025-05-07 17:27:29', '::1', NULL),
('681bd044ab2c2', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-07 17:27:32', '::1', NULL),
('681bd0467d8bd', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:27:34', '::1', NULL),
('681bd0600badd', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:28:00', '::1', NULL),
('681bd0861ccb2', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:28:38', '::1', NULL),
('681bd0a3c2d01', 'suad-001', 'Accedió a votaciones de alcaldía', '2025-05-07 17:29:07', '::1', NULL),
('681bd0b64cba7', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-07 17:29:26', '::1', NULL),
('681bd0c9add14', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:29:45', '::1', NULL),
('681bd0cd046ba', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:29:49', '::1', NULL),
('681bd0d132373', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:29:53', '::1', NULL),
('681bd0d4acca3', 'suad-001', 'Accedió a reportes globales', '2025-05-07 17:29:56', '::1', NULL),
('681cfd0f8d5f1', 'suad-001', 'Cierre de sesión', '2025-05-08 14:50:55', '::1', NULL),
('681cfd14f1777', '629ebd73-2adb-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:51:00', '::1', NULL),
('681cfd16cddc9', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-08 14:51:02', '::1', NULL),
('681cfd17c621f', '629ebd73-2adb-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:51:03', '::1', NULL),
('681cfd192ecde', '629ebd73-2adb-11f0-a', 'Accedió a la página de votación: Presidencia 2025', '2025-05-08 14:51:05', '::1', NULL),
('681cfd1e59866', '629ebd73-2adb-11f0-a', 'Cierre de sesión', '2025-05-08 14:51:10', '::1', NULL),
('681cfd219b1c2', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-08 14:51:13', '::1', NULL),
('681cfd24ad1e9', 'suad-001', 'Accedió a votaciones electorales', '2025-05-08 14:51:16', '::1', NULL),
('681cfd3626bc1', 'suad-001', 'Creó votación electoral: Presidencia 2029', '2025-05-08 14:51:34', '::1', NULL),
('681cfd3627a5c', 'suad-001', 'Accedió a votaciones electorales', '2025-05-08 14:51:34', '::1', NULL),
('681cfd3968fee', 'suad-001', 'Accedió a asignar candidatos para elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:37', '::1', NULL),
('681cfd3cc3300', 'suad-001', 'Asignó candidato ID: 33dbb197-25cd-11f0-b a elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:40', '::1', NULL),
('681cfd3cc5989', 'suad-001', 'Accedió a asignar candidatos para elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:40', '::1', NULL),
('681cfd4255554', 'suad-001', 'Asignó candidato ID: b7124f0d-2b89-11f0-9 a elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:46', '::1', NULL),
('681cfd425aed1', 'suad-001', 'Accedió a asignar candidatos para elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:46', '::1', NULL),
('681cfd45e1356', 'suad-001', 'Asignó candidato ID: e853ee82-2ad9-11f0-a a elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:49', '::1', NULL),
('681cfd45e417f', 'suad-001', 'Accedió a asignar candidatos para elección ID: 787058f3-2c3d-11f0-9', '2025-05-08 14:51:49', '::1', NULL),
('681cfd4b2aea6', 'suad-001', 'Accedió a votaciones electorales', '2025-05-08 14:51:55', '::1', NULL),
('681cfd4e71910', 'suad-001', 'Cambió estado de votación ID: 787058f3-2c3d-11f0-9 a activa', '2025-05-08 14:51:58', '::1', NULL),
('681cfd4e71f4c', 'suad-001', 'Accedió a votaciones electorales', '2025-05-08 14:51:58', '::1', NULL),
('681cfd544da1f', 'suad-001', 'Cierre de sesión', '2025-05-08 14:52:04', '::1', NULL),
('681cfd599f284', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:52:09', '::1', NULL),
('681cfd5c091e0', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2029', '2025-05-08 14:52:12', '::1', NULL),
('681cfd5dcaf4d', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:52:13', '::1', NULL),
('681cfd5f588d6', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2029', '2025-05-08 14:52:15', '::1', NULL),
('681cfd60b341f', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:52:16', '::1', NULL),
('681cfd63525bb', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-08 14:52:19', '::1', NULL),
('681cfde799bfe', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:54:31', '::1', NULL),
('681cfdece76cd', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2029', '2025-05-08 14:54:36', '::1', NULL),
('681cfdf14f10e', '91a74ae1-2adc-11f0-a', 'Votó en la elección: Presidencia 2029', '2025-05-08 14:54:41', '::1', NULL),
('681cfdf14f561', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2029', '2025-05-08 14:54:41', '::1', NULL),
('681cfdf36dd88', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:54:43', '::1', NULL),
('681cfdf48d61c', '91a74ae1-2adc-11f0-a', 'Accedió a la página de votación: Presidencia 2029', '2025-05-08 14:54:44', '::1', NULL),
('681cfe0dd1289', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:55:09', '::1', NULL),
('681cfe153fbea', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-08 14:55:17', '::1', NULL),
('681cfe1e2dc5c', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-08 14:55:26', '::1', NULL),
('681cfe2142826', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-08 14:55:29', '::1', NULL),
('681cfe41dae74', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-08 14:56:01', '::1', NULL),
('681cfe435eaf8', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-08 14:56:03', '::1', NULL),
('681cfe582ada8', 'suad-001', 'Accedió a reportes globales', '2025-05-08 14:56:24', '::1', NULL),
('681cfe676172f', 'suad-001', 'Accedió a reportes globales', '2025-05-08 14:56:39', '::1', NULL),
('681f4f74a9aa6', '91a74ae1-2adc-11f0-a', 'Acceso al panel de usuario', '2025-05-10 09:07:00', '::1', NULL),
('681f4f8859663', '91a74ae1-2adc-11f0-a', 'Cierre de sesión', '2025-05-10 09:07:20', '::1', NULL),
('681f4f8b439ab', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-10 09:07:23', '::1', NULL),
('681f4f99c7d25', 'suad-001', 'Cierre de sesión', '2025-05-10 09:07:37', '::1', NULL),
('681f4f9e63d76', 'c45c76ea-13ee-11f0-9', 'Acceso al panel de administrador', '2025-05-10 09:07:42', '::1', NULL),
('681f76e4c0906', 'c45c76ea-13ee-11f0-9', 'Cierre de sesión', '2025-05-10 11:55:16', '::1', NULL),
('681f76e77f94f', 'suad-001', 'Acceso al panel de superadministrador', '2025-05-10 11:55:19', '::1', NULL),
('681f76fa4a2aa', 'suad-001', 'Accedió a gestión de administradores y usuarios', '2025-05-10 11:55:38', '::1', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidos_politicos`
--

CREATE TABLE `partidos_politicos` (
  `id_partido` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sigla` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partidos_politicos`
--

INSERT INTO `partidos_politicos` (`id_partido`, `nombre`, `sigla`) VALUES
('PP-0001', 'Naranja', 'NJSm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `id_provincia` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_departamento` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`id_provincia`, `nombre`, `id_departamento`) VALUES
('prov-1', 'Murillo', 'dep-1'),
('prov-100', 'Abun?', 'dep-7'),
('prov-101', 'Federico Rom?n', 'dep-7'),
('prov-102', 'Manuripi', 'dep-7'),
('prov-103', 'Madre de Dios', 'dep-7'),
('prov-104', 'Nicol?s Su?rez', 'dep-7'),
('prov-105', 'Alonso de Ib??ez', 'dep-9'),
('prov-106', 'Antonio Quijarro', 'dep-9'),
('prov-107', 'Bernardino Bilbao', 'dep-9'),
('prov-108', 'Chayanta', 'dep-9'),
('prov-109', 'Cornelio Saavedra', 'dep-9'),
('prov-110', 'Daniel Campos', 'dep-9'),
('prov-111', 'Enrique Baldivieso', 'dep-9'),
('prov-112', 'Jos? Mar?a Linares', 'dep-9'),
('prov-113', 'Modesto Omiste', 'dep-9'),
('prov-114', 'Nor Chichas', 'dep-9'),
('prov-115', 'Nor L?pez', 'dep-9'),
('prov-116', 'Rafael Bustillo', 'dep-9'),
('prov-117', 'Sur Chichas', 'dep-9'),
('prov-118', 'Sur L?pez', 'dep-9'),
('prov-119', 'Tom?s Fr?as', 'dep-9'),
('prov-120', 'Andr?s Ib??ez', 'dep-3'),
('prov-121', '?ngel Sandoval', 'dep-3'),
('prov-122', 'Chiquitos', 'dep-3'),
('prov-123', 'Cordillera', 'dep-3'),
('prov-124', 'Germ?n Busch', 'dep-3'),
('prov-125', 'Guarayos', 'dep-3'),
('prov-126', 'Ignacio Warnes', 'dep-3'),
('prov-127', 'Ichilo', 'dep-3'),
('prov-128', 'Jos? Miguel de Velasco', 'dep-3'),
('prov-129', 'Manuel Mar?a Caballero', 'dep-3'),
('prov-130', '?uflo de Ch?vez', 'dep-3'),
('prov-131', 'Obispo Santistevan', 'dep-3'),
('prov-132', 'Sara', 'dep-3'),
('prov-133', 'Vallegrande', 'dep-3'),
('prov-17', 'Arani', 'dep-2'),
('prov-18', 'Arque', 'dep-2'),
('prov-19', 'Ayopaya', 'dep-2'),
('prov-2', 'Paleta', 'dep-2'),
('prov-20', 'Morochata', 'dep-2'),
('prov-21', 'Capinota', 'dep-2'),
('prov-22', 'Santiv??ez', 'dep-2'),
('prov-23', 'Sicaya', 'dep-2'),
('prov-24', 'Carrasco', 'dep-2'),
('prov-25', 'Cercado', 'dep-2'),
('prov-26', 'Chapare', 'dep-2'),
('prov-27', 'Villa Tunari', 'dep-2'),
('prov-28', 'Colomi', 'dep-2'),
('prov-29', 'Esteban Arce', 'dep-2'),
('prov-30', 'Campero', 'dep-2'),
('prov-31', 'Punata', 'dep-2'),
('prov-32', 'San Benito', 'dep-2'),
('prov-33', 'Villa Rivero', 'dep-2'),
('prov-34', 'Cuchumuela', 'dep-2'),
('prov-35', 'Tacachi', 'dep-2'),
('prov-36', 'Bol?var', 'dep-2'),
('prov-37', 'Tapacar?', 'dep-2'),
('prov-38', 'Tiraque', 'dep-2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_actividad`
--

CREATE TABLE `registro_actividad` (
  `id_registro` varchar(20) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `actividad` text NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_reporte` varchar(20) NOT NULL,
  `id_eleccion` varchar(20) NOT NULL,
  `id_ciudad` varchar(10) NOT NULL,
  `total_votos` int(11) NOT NULL,
  `fecha_generacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(2, 'Administrador'),
(1, 'Superadministrador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `super_admin`
--

CREATE TABLE `super_admin` (
  `id_super_admin` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `super_admin`
--

INSERT INTO `super_admin` (`id_super_admin`, `nombre`, `apellido`, `email`, `contrasena`, `id_rol`) VALUES
('suad-001', 'Admin', 'Sistema', 'superadmin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('suad-1', 'Aldo', 'Figueredo', 'aldofiguesal@gmail.com', '$2y$12$Ig22o0G3RPlTkpAPOgSdReGn1jJP1Q/97M9qxtmcs6Sm6N.IXI2dG', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_votacion`
--

CREATE TABLE `tipos_votacion` (
  `id_tipo` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `id_ciudad` varchar(10) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` enum('habilitado','no habilitado','nulo') DEFAULT 'habilitado',
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `ci`, `fecha_nacimiento`, `direccion`, `email`, `telefono`, `id_ciudad`, `id_rol`, `estado`, `contrasena`) VALUES
('572b95ad-125a-11f0-a', 'Aldo', 'Figueredo', '10093912', '2004-08-01', 'Llojeta', 'matadorsalvatierra@gmail.com', '71261748', 'ci-1', 3, 'habilitado', '$2y$12$Ig22o0G3RPlTkpAPOgSdReGn1jJP1Q/97M9qxtmcs6Sm6N.IXI2dG'),
('629ebd73-2adb-11f0-a', 'Juan', 'Ibanez', '103204234', '2004-02-18', 'Llojeta', 'juancopa@gmail.com', '78623456', 'ci-1', 3, 'habilitado', '$2y$12$lbZUY9d9eAwwIK.IhlFVu.qVg9eaTdpcB7Uv4HzoHOizR3GxTI5.2'),
('91a74ae1-2adc-11f0-a', 'Yuri', 'Rojas', '10093913', '2005-10-06', 'Llojeta', 'yuri@gmail.com', '78623456', 'ci-3', 3, 'habilitado', '$2y$12$/7j7a2R9/wNWFLFfIGSfX.sjDamdQHTYGuvxsvaNLoO7OiYzPRHrS'),
('usr-001', 'Juan Carlos', 'Mamani Quispe', '1234567', '1985-03-15', 'Av. Arce #1234', 'juan.mamani@mail.bo', '71234567', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-002', 'Mar?a Elena', 'Fern?ndez L?pez', '2345678', '1990-07-22', 'Calle Ballivi?n #456', 'maria.fernandez@correo.bo', '72345678', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-003', 'Carlos Andr?s', 'Guti?rrez Rojas', '3456789', '1982-11-30', 'Av. 16 de Julio #789', 'carlos.gutierrez@dominio.bo', '73456789', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-004', 'Ana Patricia', 'Vargas Mendoza', '4567890', '1978-05-18', 'Calle Potos? #321', 'ana.vargas@servicio.bo', '74567890', 'ci-1', 3, 'nulo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-005', 'Luis Fernando', 'P?rez Castro', '5678901', '1995-09-25', 'Av. Camacho #654', 'luis.perez@correo.bo', '75678901', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-006', 'Sof?a Alejandra', 'Rodr?guez Herrera', '6789012', '1989-12-10', 'Calle Loayza #987', 'sofia.rodriguez@mail.bo', '76789012', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-007', 'Jorge Alberto', 'G?mez Flores', '7890123', '1975-02-28', 'Av. 6 de Agosto #159', 'jorge.gomez@dominio.bo', '77890123', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-008', 'Gabriela Nicole', 'Torres Su?rez', '8901234', '1992-08-14', 'Calle Illimani #753', 'gabriela.torres@servicio.bo', '78901234', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-009', 'Roberto Carlos', 'Jim?nez Paredes', '9012345', '1980-04-05', 'Av. Busch #852', 'roberto.jimenez@correo.bo', '79012345', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-010', 'Daniela Valeria', 'Santos Miranda', '1234501', '1987-10-20', 'Calle Sag?rnaga #147', 'daniela.santos@mail.bo', '70123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-011', 'Pedro Pablo', 'R?os Velasco', '2345012', '1973-01-15', 'Av. Villaz?n #258', 'pedro.rios@dominio.bo', '71123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-012', 'Laura Beatriz', 'Aguilar M?ndez', '3450123', '1998-06-30', 'Calle Ja?n #369', 'laura.aguilar@servicio.bo', '72123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-013', 'Mario Augusto', 'C?rdenas Rocha', '4501234', '1984-03-22', 'Av. Mariscal Santa Cruz #741', 'mario.cardenas@correo.bo', '73123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-014', 'Carmen Rosa', 'Ortiz Salazar', '5012345', '1970-09-08', 'Calle Socabaya #963', 'carmen.ortiz@mail.bo', '74123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-015', 'Jos? Miguel', 'Navarro Guti?rrez', '6123450', '1993-11-17', 'Av. 20 de Octubre #357', 'jose.navarro@dominio.bo', '75123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-016', 'Patricia Andrea', 'L?pez Arce', '7234501', '1986-07-03', 'Calle Col?n #159', 'patricia.lopez@servicio.bo', '76123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-017', 'Ricardo Alfonso', 'Mart?nez Cordero', '8345012', '1979-04-26', 'Av. Montes #753', 'ricardo.martinez@correo.bo', '77123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-018', 'Ver?nica Isabel', 'Garc?a Poma', '9450123', '1991-12-12', 'Calle Bueno #951', 'veronica.garcia@mail.bo', '78123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-019', 'Alberto Jose', 'Ruiz Franco', '0561234', '1983-08-19', 'Av. Per? #357', 'alberto.ruiz@dominio.bo', '79123456', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('usr-020', 'Claudia Marcela', 'D?az Villarroel', '1672345', '1977-05-07', 'Calle Ingavi #852', 'claudia.diaz@servicio.bo', '70198765', 'ci-1', 3, 'habilitado', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE `votos` (
  `id_voto` varchar(20) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `id_eleccion` varchar(20) NOT NULL,
  `id_candidato` varchar(20) NOT NULL,
  `fecha_emision` datetime DEFAULT current_timestamp(),
  `hash_voto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `votos`
--

INSERT INTO `votos` (`id_voto`, `id_usuario`, `id_eleccion`, `id_candidato`, `fecha_emision`, `hash_voto`) VALUES
('0ff04de7-2b8a-11f0-9', '629ebd73-2adb-11f0-a', '6ec0bda0-2b89-11f0-9', 'e853ee82-2ad9-11f0-a', '2025-05-07 17:27:22', 'cb1ac1ce4cfde56372916b43682271e845bcb04f9c084fbbe49bdd309f86b45f'),
('13b503c7-25d0-11f0-b', '572b95ad-125a-11f0-a', '20335149-2487-11f0-b', 'ce0e4adf-25cf-11f0-b', '2025-04-30 10:33:26', 'dd77fcb876855664d541fa1796fbb60fb25a3067f5debdf2b4c578a799ef6651'),
('7e964c9a-2ae0-11f0-a', '91a74ae1-2adc-11f0-a', '20335149-2487-11f0-b', 'ce0e4adf-25cf-11f0-b', '2025-05-06 21:13:33', '37e4ceaad14b52d1081d1cde0e4d36df85ce75142021e85a05d4294a2ee57b03'),
('85bb4e65-2ad4-11f0-a', '572b95ad-125a-11f0-a', '5f591f52-2ad4-11f0-a', '777c7fe7-2ad4-11f0-a', '2025-05-06 19:47:51', '6d7663fb5c0fb27dccb1ead3a3c0ad3455e0e632202dbb62da056740f8339465'),
('9a961c10-2adb-11f0-a', '629ebd73-2adb-11f0-a', '5f591f52-2ad4-11f0-a', 'e853ee82-2ad9-11f0-a', '2025-05-06 20:38:32', '01d82c37a0471f5bbeea68e74a7e7c3dfea3da21d7ea6a596b1bdacf4f80bd95'),
('bdccbec4-2adc-11f0-a', '91a74ae1-2adc-11f0-a', '5f591f52-2ad4-11f0-a', 'e853ee82-2ad9-11f0-a', '2025-05-06 20:46:41', 'b8d03848918ba5b1865d77ca8785249064ac2521a9f35385aacd8c80d055b954'),
('e5058c65-2b89-11f0-9', '91a74ae1-2adc-11f0-a', '6ec0bda0-2b89-11f0-9', '4a4218f0-25ce-11f0-b', '2025-05-07 17:26:10', '331229ed83a8b2aa6a8176091666f518d13ad7086c5ef6dc4746bdf8f4fd1499'),
('e7ff85fa-2c3d-11f0-9', '91a74ae1-2adc-11f0-a', '787058f3-2c3d-11f0-9', 'e853ee82-2ad9-11f0-a', '2025-05-08 14:54:41', 'b1ad4e9d76249686d199f0a4754c7a7e2022441ef4b478f6f6efa2d5ebd7c4b6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `candidatos`
--
ALTER TABLE `candidatos`
  ADD PRIMARY KEY (`id_candidato`),
  ADD KEY `id_eleccion` (`id_eleccion`),
  ADD KEY `id_partido` (`id_partido`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id_ciudad`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Indices de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_departamento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `elecciones`
--
ALTER TABLE `elecciones`
  ADD PRIMARY KEY (`id_eleccion`),
  ADD KEY `fk_eleccion_ciudad` (`id_ciudad`);

--
-- Indices de la tabla `log_auditoria`
--
ALTER TABLE `log_auditoria`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `partidos_politicos`
--
ALTER TABLE `partidos_politicos`
  ADD PRIMARY KEY (`id_partido`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `sigla` (`sigla`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id_provincia`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `registro_actividad`
--
ALTER TABLE `registro_actividad`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `id_eleccion` (`id_eleccion`),
  ADD KEY `id_ciudad` (`id_ciudad`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id_super_admin`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `tipos_votacion`
--
ALTER TABLE `tipos_votacion`
  ADD PRIMARY KEY (`id_tipo`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `ci` (`ci`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_ciudad` (`id_ciudad`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`id_voto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_eleccion` (`id_eleccion`),
  ADD KEY `id_candidato` (`id_candidato`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_votacion`
--
ALTER TABLE `tipos_votacion`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `candidatos`
--
ALTER TABLE `candidatos`
  ADD CONSTRAINT `candidatos_ibfk_1` FOREIGN KEY (`id_eleccion`) REFERENCES `elecciones` (`id_eleccion`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatos_ibfk_2` FOREIGN KEY (`id_partido`) REFERENCES `partidos_politicos` (`id_partido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id_provincia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `elecciones`
--
ALTER TABLE `elecciones`
  ADD CONSTRAINT `fk_eleccion_ciudad` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudad`);

--
-- Filtros para la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD CONSTRAINT `provincias_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registro_actividad`
--
ALTER TABLE `registro_actividad`
  ADD CONSTRAINT `registro_actividad_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_eleccion`) REFERENCES `elecciones` (`id_eleccion`) ON DELETE CASCADE,
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `super_admin`
--
ALTER TABLE `super_admin`
  ADD CONSTRAINT `super_admin_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;

--
-- Filtros para la tabla `votos`
--
ALTER TABLE `votos`
  ADD CONSTRAINT `votos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `votos_ibfk_2` FOREIGN KEY (`id_eleccion`) REFERENCES `elecciones` (`id_eleccion`) ON DELETE CASCADE,
  ADD CONSTRAINT `votos_ibfk_3` FOREIGN KEY (`id_candidato`) REFERENCES `candidatos` (`id_candidato`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
