-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-10-2026 a las 01:58:16
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
-- Base de datos: `proyectoweb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artistas`
--

CREATE TABLE `artistas` (
  `id_artista` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `biografia` text DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `artistas`
--

INSERT INTO `artistas` (`id_artista`, `nombre`, `biografia`, `imagen_url`) VALUES
(1, 'Charli xcx', 'Cantante y compositora británica pionera del pop experimental y del movimiento hyperpop, aclamada por su estilo innovador y producciones audaces.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Charli_XCX_2024.jpg/800px-Charli_XCX_2024.jpg'),
(2, 'Frank Ocean', 'Cantautor y productor estadounidense, figura clave del R&B contemporáneo e influente por sus narrativas introspectivas y sonido vanguardista.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Frank_Ocean_2017.jpg/800px-Frank_Ocean_2017.jpg'),
(3, 'Tame Impala', 'Proyecto musical de rock psicodélico liderado por el compositor y multiinstrumentista australiano Kevin Parker, famoso por sus paisajes sonoros analógicos.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Tame_Impala_2019.jpg/800px-Tame_Impala_2019.jpg'),
(4, 'Bad Bunny', 'Cantante y compositor puertorriqueño, fenómeno global de la música urbana que ha fusionado el reggaetón y el trap con múltiples géneros tropicales e independientes.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Bad_Bunny_2023.jpg/800px-Bad_Bunny_2023.jpg'),
(5, 'Rosalía', 'Cantautora y productora española galardonada internacionalmente por reinventar el flamenco tradicional al combinarlo con pop, ritmos urbanos y electrónica.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Rosalia_Grammys_2020.jpg/800px-Rosalia_Grammys_2020.jpg'),
(6, 'Radiohead', 'Banda británica de rock alternativo e influente en la música moderna por su constante evolución, desde el art rock hasta la experimentación electrónica.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Radiohead_2017.jpg/800px-Radiohead_2017.jpg'),
(7, 'Gustavo Cerati', 'Cantautor, guitarrista y compositor argentino, líder de la emblemática banda Soda Stereo y referente indiscutido del rock latinoamericano y la música en español.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Cerati_en_Mendoza.jpg/800px-Cerati_en_Mendoza.jpg'),
(8, 'Daft Punk', 'Dúo francés de música electrónica integrado por Guy-Manuel de Homem-Christo y Thomas Bangalter, pioneros del synthpop y el movimiento French Touch.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Daft_Punk_2013.jpg/800px-Daft_Punk_2013.jpg'),
(9, 'Portishead', 'Banda británica de trip hop formada en Bristol, célebre por sus composiciones oscuras, atmósferas cinematográficas y la voz melancólica de Beth Gibbons.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Portishead_2013.jpg/800px-Portishead_2013.jpg'),
(10, 'Arctic Monkeys', 'Banda británica de indie rock liderada por Alex Turner, referente generacional por su agudeza lírica y transformación estilística a lo largo de los años.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Arctic_Monkeys_2018.jpg/800px-Arctic_Monkeys_2018.jpg'),
(11, 'Kendrick Lamar', 'Rapero, compositor y productor estadounidense, primer artista ajeno a la música clásica y al jazz en recibir el Premio Pulitzer de Música por sus relatos líricos.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Kendrick_Lamar_2018.jpg/800px-Kendrick_Lamar_2018.jpg'),
(12, 'Charly García', 'Pianista, compositor y cantautor argentino, una de las figuras más importantes, prolíficas e influyentes de la historia de la música popular en español.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Charly_Garcia_1983.jpg/800px-Charly_Garcia_1983.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones`
--

CREATE TABLE `canciones` (
  `id_cancion` int(11) NOT NULL,
  `id_disco` int(11) NOT NULL,
  `numero_pista` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `duracion` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `canciones`
--

INSERT INTO `canciones` (`id_cancion`, `id_disco`, `numero_pista`, `titulo`, `duracion`) VALUES
(1, 1, 1, '360', '2:13'),
(2, 1, 2, 'Club classics', '2:33'),
(3, 1, 3, 'Sympathy is a knife', '2:38'),
(4, 1, 4, 'I might say something stupid', '1:49'),
(5, 1, 5, 'Talk talk', '2:41'),
(6, 1, 6, 'Von dutch', '2:44'),
(7, 1, 7, 'Everything is romantic', '3:23'),
(8, 1, 8, 'Rewind', '2:48'),
(9, 1, 9, 'So I', '3:31'),
(10, 1, 10, 'Girl, so confusing', '2:54'),
(11, 1, 11, 'Apple', '2:31'),
(12, 1, 12, 'B2b', '2:58'),
(13, 1, 13, 'Mean girls', '3:09'),
(14, 1, 14, 'I think about it all the time', '2:15'),
(15, 1, 15, '365', '3:23'),
(16, 2, 1, 'Nikes', '5:14'),
(17, 2, 2, 'Ivy', '4:09'),
(18, 2, 3, 'Pink + White', '3:04'),
(19, 2, 4, 'Be Yourself', '1:26'),
(20, 2, 5, 'Solo', '4:17'),
(21, 2, 6, 'Skyline To', '3:04'),
(22, 2, 7, 'Self Control', '4:09'),
(23, 2, 8, 'Good Guy', '1:06'),
(24, 2, 9, 'Nights', '5:07'),
(25, 2, 10, 'Solo (Reprise)', '1:18'),
(26, 2, 11, 'Pretty Sweet', '2:38'),
(27, 2, 12, 'Facebook Story', '1:08'),
(28, 2, 13, 'Close to You', '1:25'),
(29, 2, 14, 'White Ferrari', '4:08'),
(30, 2, 15, 'Seigfried', '5:34'),
(31, 2, 16, 'Godspeed', '2:57'),
(32, 2, 17, 'Futura Free', '9:24'),
(33, 3, 1, 'Let It Happen', '7:46'),
(34, 3, 2, 'Nangs', '1:47'),
(35, 3, 3, 'The Moment', '4:15'),
(36, 3, 4, 'Yes I\'m Changing', '4:30'),
(37, 3, 5, 'Eventually', '5:19'),
(38, 3, 6, 'Gossip', '0:55'),
(39, 3, 7, 'The Less I Know the Better', '3:36'),
(40, 3, 8, 'Past Life', '3:47'),
(41, 3, 9, 'Disciples', '1:48'),
(42, 3, 10, 'Cause I\'m a Man', '4:01'),
(43, 3, 11, 'Reality in Motion', '4:12'),
(44, 3, 12, 'Love/Paranoia', '3:06'),
(45, 3, 13, 'New Person, Same Old Mistakes', '6:02'),
(46, 4, 1, 'Moscow Mule', '4:05'),
(47, 4, 2, 'Después de la Playa', '3:50'),
(48, 4, 3, 'Me Porto Bonito', '2:58'),
(49, 4, 4, 'Tití Me Preguntó', '4:03'),
(50, 4, 5, 'Un Ratito', '2:56'),
(51, 4, 6, 'Yo No Soy Celoso', '3:50'),
(52, 4, 7, 'Tarot', '3:57'),
(53, 4, 8, 'Neverita', '3:10'),
(54, 4, 9, 'La Corriente', '2:55'),
(55, 4, 10, 'Efecto', '3:33'),
(56, 4, 11, 'Party', '3:47'),
(57, 4, 12, 'Aguacero', '3:30'),
(58, 4, 13, 'Enséñame a Bailar', '2:56'),
(59, 4, 14, 'Ojitos Lindos', '4:18'),
(60, 4, 15, 'Dos Mil 16', '3:28'),
(61, 4, 16, 'El Apagón', '3:21'),
(62, 4, 17, 'Otro Atardecer', '4:04'),
(63, 4, 18, 'Un Cocodrilo en la Selva', '2:40'),
(64, 4, 19, 'Agosto', '2:19'),
(65, 4, 20, 'Callaíta', '3:10'),
(66, 4, 21, 'Andrea', '5:39'),
(67, 4, 22, 'Me Fui de Vacaciones', '3:00'),
(68, 4, 23, 'Un Verano Sin Ti', '2:28'),
(69, 5, 1, 'MALAMENTE (Cap.1: Augurio)', '2:29'),
(70, 5, 2, 'QUE NO SALGA LA LUNA (Cap.2: Boda)', '4:29'),
(71, 5, 3, 'PIENSO EN TU MIRÁ (Cap.3: Celos)', '3:13'),
(72, 5, 4, 'DE AQUÍ NO SALES (Cap.4: Disputa)', '2:24'),
(73, 5, 5, 'RENIEGO (Cap.5: Lamento)', '3:28'),
(74, 5, 6, 'PRESO (Cap.6: Clausura)', '1:30'),
(75, 5, 7, 'BAGDAD (Cap.7: Liturgia)', '3:02'),
(76, 5, 8, 'DI MI NOMBRE (Cap.8: Éxtasis)', '2:42'),
(77, 5, 9, 'NANA (Cap.9: Concepción)', '3:17'),
(78, 5, 10, 'MALDICIÓN (Cap.10: Cordura)', '2:55'),
(79, 5, 11, 'A NINGÚN HOMBRE (Cap.11: Poder)', '1:34'),
(80, 6, 1, '15 Step', '3:57'),
(81, 6, 2, 'Bodysnatchers', '4:02'),
(82, 6, 3, 'Nude', '4:15'),
(83, 6, 4, 'Weird Fishes/Arpeggi', '5:18'),
(84, 6, 5, 'All I Need', '3:48'),
(85, 6, 6, 'Faust Arp', '2:09'),
(86, 6, 7, 'Reckoner', '4:50'),
(87, 6, 8, 'House of Cards', '5:28'),
(88, 6, 9, 'Jigsaw Falling Into Place', '4:09'),
(89, 6, 10, 'Videotape', '4:39'),
(90, 7, 1, 'Tabú', '4:43'),
(91, 7, 2, 'Bocanada', '4:07'),
(92, 7, 3, 'Puente', '4:33'),
(93, 7, 4, 'Río Babel', '4:42'),
(94, 7, 5, 'Beautiful', '4:24'),
(95, 7, 6, 'Perdonar es divino', '5:14'),
(96, 7, 7, 'Verbo carne', '4:00'),
(97, 7, 8, 'Raíz', '4:08'),
(98, 7, 9, 'Y si el humo está en foco', '1:56'),
(99, 7, 10, 'Paseo inmoral', '4:02'),
(100, 7, 11, 'Aquí y ahora (Los primeros tres minutos)', '2:55'),
(101, 7, 12, 'Aquí y ahora (Y después)', '2:35'),
(102, 7, 13, 'Alma', '4:31'),
(103, 7, 14, 'Ritmo', '4:20'),
(104, 7, 15, 'Balsa', '3:52'),
(105, 8, 1, 'Give Life Back to Music', '4:34'),
(106, 8, 2, 'The Game of Love', '5:22'),
(107, 8, 3, 'Giorgio by Moroder', '9:04'),
(108, 8, 4, 'Within', '3:48'),
(109, 8, 5, 'Instant Crush', '5:37'),
(110, 8, 6, 'Lose Yourself to Dance', '5:53'),
(111, 8, 7, 'Touch', '8:18'),
(112, 8, 8, 'Get Lucky', '4:08'),
(113, 8, 9, 'Beyond', '4:50'),
(114, 8, 10, 'Motherboard', '5:41'),
(115, 8, 11, 'Fragments of Time', '4:39'),
(116, 8, 12, 'Doin\' It Right', '4:11'),
(117, 8, 13, 'Contact', '6:21'),
(118, 9, 1, 'Airbag', '4:44'),
(119, 9, 2, 'Paranoid Android', '6:23'),
(120, 9, 3, 'Subterranean Homesick Alien', '4:27'),
(121, 9, 4, 'Exit Music (For a Film)', '4:24'),
(122, 9, 5, 'Let Down', '4:59'),
(123, 9, 6, 'Karma Police', '4:21'),
(124, 9, 7, 'Fitter Happier', '1:57'),
(125, 9, 8, 'Electioneering', '3:50'),
(126, 9, 9, 'Climbing Up the Walls', '3:44'),
(127, 9, 10, 'No Surprises', '3:48'),
(128, 9, 11, 'Lucky', '4:19'),
(129, 9, 12, 'The Tourist', '5:24'),
(130, 10, 1, 'Mysterons', '5:02'),
(131, 10, 2, 'Sour Times', '4:11'),
(132, 10, 3, 'Strangers', '3:55'),
(133, 10, 4, 'It Could Be Sweet', '4:16'),
(134, 10, 5, 'Wandering Star', '4:51'),
(135, 10, 6, 'Numb', '3:54'),
(136, 10, 7, 'Roads', '5:02'),
(137, 10, 8, 'Pedestal', '3:39'),
(138, 10, 9, 'Biscuit', '5:01'),
(139, 10, 10, 'Glory Box', '5:06'),
(140, 11, 1, 'One More Time', '5:20'),
(141, 11, 2, 'Aerodynamic', '3:27'),
(142, 11, 3, 'Digital Love', '4:58'),
(143, 11, 4, 'Harder, Better, Faster, Stronger', '3:44'),
(144, 11, 5, 'Crescendolls', '3:31'),
(145, 11, 6, 'Nightvision', '1:44'),
(146, 11, 7, 'Superheroes', '3:57'),
(147, 11, 8, 'High Life', '3:22'),
(148, 11, 9, 'Something About Us', '3:51'),
(149, 11, 10, 'Voyager', '3:47'),
(150, 11, 11, 'Veridis Quo', '4:54'),
(151, 11, 12, 'Short Circuit', '3:26'),
(152, 11, 13, 'Face to Face', '3:58'),
(153, 11, 14, 'Too Long', '10:00'),
(154, 12, 1, 'Do I Wanna Know?', '4:32'),
(155, 12, 2, 'R U Mine?', '3:21'),
(156, 12, 3, 'One for the Road', '3:26'),
(157, 12, 4, 'Arabella', '3:27'),
(158, 12, 5, 'I Want It All', '3:05'),
(159, 12, 6, 'No.1 Party Anthem', '4:03'),
(160, 12, 7, 'Mad Sounds', '3:35'),
(161, 12, 8, 'Fireside', '3:01'),
(162, 12, 9, 'Why\'d You Only Call Me When You\'re High?', '2:41'),
(163, 12, 10, 'Snap Out of It', '3:13'),
(164, 12, 11, 'Knee Socks', '4:17'),
(165, 12, 12, 'I Wanna Be Yours', '3:04'),
(166, 13, 1, 'Everything in Its Right Place', '4:11'),
(167, 13, 2, 'Kid A', '4:44'),
(168, 13, 3, 'The National Anthem', '5:51'),
(169, 13, 4, 'How to Disappear Completely', '5:56'),
(170, 13, 5, 'Treefingers', '3:42'),
(171, 13, 6, 'Optimistic', '5:15'),
(172, 13, 7, 'In Limbo', '3:31'),
(173, 13, 8, 'Idioteque', '5:09'),
(174, 13, 9, 'Morning Bell', '4:35'),
(175, 13, 10, 'Motion Picture Soundtrack', '7:01'),
(176, 14, 1, 'Burn the Witch', '3:40'),
(177, 14, 2, 'Daydreaming', '6:24'),
(178, 14, 3, 'Decks Dark', '4:41'),
(179, 14, 4, 'Desert Island Disk', '3:44'),
(180, 14, 5, 'Ful Stop', '6:07'),
(181, 14, 6, 'Glass Eyes', '2:52'),
(182, 14, 7, 'Identikit', '4:26'),
(183, 14, 8, 'The Numbers', '5:45'),
(184, 14, 9, 'Present Tense', '5:06'),
(185, 14, 10, 'Tinker Tailor Soldier Sailor Rich Man Poor Man Beggar Man Thief', '5:03'),
(186, 14, 11, 'True Love Waits', '4:43'),
(187, 15, 1, 'Amor amarillo', '5:37'),
(188, 15, 2, 'Lisa', '4:24'),
(189, 15, 3, 'Te llevo para que me lleves', '3:44'),
(190, 15, 4, 'Cabeza de medusa', '5:09'),
(191, 15, 5, 'Av. Alcorta', '4:38'),
(192, 15, 6, 'Bajan', '4:11'),
(193, 15, 7, 'Rombos', '4:23'),
(194, 15, 8, 'Ahora es nunca', '4:45'),
(195, 15, 9, 'A merced', '4:40'),
(196, 15, 10, 'Tortval', '6:00'),
(197, 16, 1, 'Cosas imposibles', '5:05'),
(198, 16, 2, 'No te creo', '4:01'),
(199, 16, 3, 'Artefacto', '4:16'),
(200, 16, 4, 'Nací para esto', '5:09'),
(201, 16, 5, 'Amo dejarte ir', '5:25'),
(202, 16, 6, 'Tu cicatriz en mí', '4:17'),
(203, 16, 7, 'Señales', '4:30'),
(204, 16, 8, 'Fantasma', '3:55'),
(205, 16, 9, 'Edición de lujo', '4:27'),
(206, 16, 10, 'Casa', '4:32'),
(207, 16, 11, 'Camuflaje', '3:55'),
(208, 16, 12, 'Altar', '4:41'),
(209, 16, 13, 'Torre de marfil', '4:38'),
(210, 16, 14, 'Fantasma (reprise)', '1:41'),
(211, 16, 15, 'Vivo', '4:20'),
(212, 16, 16, 'Tu locura', '2:55'),
(213, 16, 17, 'Especie', '3:42'),
(214, 17, 1, 'Alfin', '3:52'),
(215, 17, 2, 'La excepción', '4:11'),
(216, 17, 3, 'Uno entre 1000', '4:07'),
(217, 17, 4, 'Crimen', '3:48'),
(218, 17, 5, 'Otra piel', '4:37'),
(219, 17, 6, 'Medium', '5:30'),
(220, 17, 7, 'Adiós', '3:51'),
(221, 17, 8, 'Me quedo aquí', '3:27'),
(222, 17, 9, 'Lago en el cielo', '5:05'),
(223, 17, 10, 'Dios nos libre', '4:29'),
(224, 17, 11, 'Jugo de luna', '4:03'),
(225, 17, 12, 'Caravana', '4:08'),
(226, 17, 13, 'Convoy', '3:15'),
(227, 18, 1, 'Fuerza natural', '4:50'),
(228, 18, 2, 'Déjà vu', '3:20'),
(229, 18, 3, 'Magia', '4:28'),
(230, 18, 4, 'Amor sin rodeos', '3:53'),
(231, 18, 5, 'Tracción a sangre', '4:16'),
(232, 18, 6, 'Desastre', '3:36'),
(233, 18, 7, 'Rapto', '3:56'),
(234, 18, 8, 'Cactus', '3:54'),
(235, 18, 9, 'Naturaleza muerta', '3:53'),
(236, 18, 10, 'Dominó', '3:21'),
(237, 18, 11, 'Sal', '4:17'),
(238, 18, 12, 'Convoy', '3:16'),
(239, 18, 13, 'He visto a Lucy', '5:31'),
(240, 18, 14, '# (Numeral)', '3:49'),
(241, 19, 1, 'Wesley\'s Theory', '4:47'),
(242, 19, 2, 'For Free? (Interlude)', '2:10'),
(243, 19, 3, 'King Kunta', '3:54'),
(244, 19, 4, 'Institutionalized', '4:31'),
(245, 19, 5, 'These Walls', '5:00'),
(246, 19, 6, 'u', '4:28'),
(247, 19, 7, 'Alright', '3:39'),
(248, 19, 8, 'For Sale? (Interlude)', '4:51'),
(249, 19, 9, 'Momma', '4:43'),
(250, 19, 10, 'Hood Politics', '4:52'),
(251, 19, 11, 'How Much a Dollar Cost', '4:21'),
(252, 19, 12, 'Complexion (A Zulu Love)', '4:23'),
(253, 19, 13, 'The Blacker the Berry', '5:28'),
(254, 19, 14, 'You Ain\'t Gotta Lie (Momma Said)', '4:01'),
(255, 19, 15, 'i', '3:52'),
(256, 19, 16, 'Mortal Man', '12:07'),
(257, 20, 1, 'Nos siguen pegando abajo', '3:30'),
(258, 20, 2, 'No soy un extraño', '3:18'),
(259, 20, 3, 'Dos cero uno (Transas)', '2:09'),
(260, 20, 4, 'Nuevos trapos', '4:08'),
(261, 20, 5, 'Bancate ese defecto', '4:56'),
(262, 20, 6, 'No me dejan salir', '4:21'),
(263, 20, 7, 'Los dinosaurios', '3:28'),
(264, 20, 8, 'Plateado sobre plateado', '3:02'),
(265, 20, 9, 'Ojos de video tape', '3:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discos`
--

CREATE TABLE `discos` (
  `id_disco` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `sello` varchar(50) DEFAULT NULL,
  `portada_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `discos`
--

INSERT INTO `discos` (`id_disco`, `titulo`, `id_artista`, `fecha_lanzamiento`, `descripcion`, `genero`, `sello`, `portada_url`) VALUES
(1, 'BRAT', 1, '2024-06-07', 'El sexto álbum de estudio de Charli XCX explora sonidos de club, hyperpop y líricas introspectivas sobre la fama y las relaciones.', 'Pop / Dance', 'Atlantic Records', 'https://upload.wikimedia.org/wikipedia/commons/6/60/Charli_XCX_-_Brat_%28album_cover%29.png'),
(2, 'Blonde', 2, '2016-08-20', 'Una obra maestra contemporánea que mezcla pop experimental, soul y letras vanguardistas sobre el amor, la identidad y la nostalgia.', 'R&B / Neo-Soul', 'Boys Don\'t Cry', 'https://upload.wikimedia.org/wikipedia/en/a/a0/Blonde_-_Frank_Ocean.jpeg'),
(3, 'Currents', 3, '2015-07-17', 'Tame Impala lleva el rock psicodélico hacia sintetizadores ochenteros y ritmos de disco/funk en este aclamado álbum.', 'Rock Psicodélico', 'Modular / Interscope', 'https://upload.wikimedia.org/wikipedia/en/9/9b/Tame_Impala_-_Currents.png'),
(4, 'Un Verano Sin Ti', 4, '2022-05-06', 'Álbum conceptual que captura la vibra veraniega del Caribe combinando reggaetón, indie, mambo y ritmos tropicales.', 'Urbano / Reggaetón', 'Rimas Entertainment', 'https://upload.wikimedia.org/wikipedia/en/4/4f/Bad_Bunny_-_Un_Verano_Sin_Ti.png'),
(5, 'El Mal Querer', 5, '2018-11-02', 'Álbum conceptual basado en la novela del siglo XIII Flamenca, fusionando la tradición del flamenco con pop experimental.', 'Flamenco / Pop', 'Sony Music', 'https://upload.wikimedia.org/wikipedia/en/3/30/Rosal%C3%ADa_-_El_Mal_Querer.png'),
(6, 'In Rainbows', 6, '2007-10-10', 'Uno de los lanzamientos más emblemáticos de Radiohead, combinando instrumentaciones cálidas, electrónica sutil y letras profundas.', 'Art Rock', 'XL Recordings', 'https://upload.wikimedia.org/wikipedia/en/1/14/Inrainbows_cover.png'),
(7, 'Bocanada', 7, '1999-06-28', 'El segundo álbum solista de Cerati abre un universo de texturas, samples y melodías que respiran. Un puente entre el rock y la electrónica.', 'Rock Alternativo', 'BMG', 'Img/bocanada.jpg'),
(8, 'Random Access Memories', 8, '2013-05-17', 'Un tributo épico a la música de los años 70 y 80 grabado con instrumentos analógicos, orquestas y colaboraciones históricas.', 'Synthpop / Disco', 'Columbia Records', 'https://upload.wikimedia.org/wikipedia/en/a/a7/Random_Access_Memories.jpg'),
(9, 'OK Computer', 6, '1997-05-21', 'Álbum hito de los años 90 que retrata la alienación moderna, el consumismo y el avance tecnológico con guitarras complejas.', 'Rock Alternativo', 'Parlophone', 'https://upload.wikimedia.org/wikipedia/en/a/a1/Radiohead.okcomputer.albumart.jpg'),
(10, 'Dummy', 9, '1994-08-22', 'Obra cumbre del trip hop noventero, cargada de oscuros samples de vinilo, atmósferas cinematográficas y la emotiva voz de Beth Gibbons.', 'Trip Hop', 'Go! Discs', 'https://upload.wikimedia.org/wikipedia/en/8/84/Portishead_-_Dummy.png'),
(11, 'Discovery', 8, '2001-03-12', 'Clásico absoluto del house francés que definió el sonido electrónico de los 2000 mediante samplers retro y sintetizadores melódicos.', 'French Touch / House', 'Virgin Records', 'https://upload.wikimedia.org/wikipedia/en/a/ae/Daft_Punk_-_Discovery.png'),
(12, 'AM', 10, '2013-09-09', 'Álbum consagratorio de Arctic Monkeys que fusiona riffs pesados de guitarra con ritmos inspirados en el R&B y el hip hop.', 'Indie Rock', 'Domino Recording Co.', 'https://upload.wikimedia.org/wikipedia/commons/e/e7/Something_AM.jpg'),
(13, 'Kid A', 6, '2000-10-02', 'Reinvención radical de Radiohead hacia la música electrónica, el krautrock y el jazz experimental al inicio del nuevo milenio.', 'Electrónica / Art Rock', 'Parlophone', 'https://upload.wikimedia.org/wikipedia/en/a/a2/Radioheadkida.png'),
(14, 'A Moon Shaped Pool', 6, '2016-05-08', 'Novenos álbum de estudio de la banda que combina arreglos de cuerdas cinematográficos con instrumentación acústica y sutiles toques electrónicos.', 'Art Rock / Orquestal', 'XL Recordings', 'https://upload.wikimedia.org/wikipedia/en/8/87/Amoonshapedpool.png'),
(15, 'Amor Amarillo', 7, '1993-11-01', 'Debut solista de Gustavo Cerati grabado durante su residencia en Santiago de Chile, caracterizado por una atmósfera acústica, íntima y luminosa.', 'Rock Alternativo / Pop', 'BMG', 'Img/amor_amarillo.jpg'),
(16, 'Siempre es hoy', 7, '2002-11-26', 'Álbum doble ambicioso y ecléctico donde Cerati explora ritmos electrónicos, pop luminoso y letras sobre el presente y las sensaciones.', 'Rock / Pop / Electrónica', 'BMG', 'Img/siempre_es_hoy.jpg'),
(17, 'Ahí vamos', 7, '2006-04-04', 'Retorno directo al rock de guitarras potentes y melodías directas, considerado uno de los trabajos más sólidos e icónicos de Cerati.', 'Rock / Power Pop', 'Sony BMG', 'Img/ahi_vamos.jpg'),
(18, 'Fuerza natural', 7, '2009-09-01', 'Último álbum de estudio de Cerati, un viaje sonoro impulsado por la guitarra acústica, el folk psicodélico y lirismo poético.', 'Rock / Folk / Psicodelia', 'Sony Music', 'Img/fuerza_natural.jpg'),
(19, 'To Pimp a Butterfly', 11, '2015-03-15', 'Obra maestra del hip hop contemporáneo que entrelaza jazz, funk, spoken word y potentes reflexiones sociales y raciales.', 'Hip Hop / Jazz Fusion', 'Top Dawg / Interscope', 'https://upload.wikimedia.org/wikipedia/en/f/f6/Kendrick_Lamar_-_To_Pimp_a_Butterfly.png'),
(20, 'Clics Modernos', 12, '1983-11-05', 'Obra fundamental del rock argentino grabada en Nueva York, incorporando cajas de ritmos, samplers y letras cargadas de ironía y libertad.', 'New Wave / Synthpop', 'SG Discos', 'https://upload.wikimedia.org/wikipedia/en/c/c2/Clics_modernos.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_usuario` int(11) NOT NULL,
  `id_disco` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas`
--

CREATE TABLE `listas` (
  `id_lista` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_discos`
--

CREATE TABLE `lista_discos` (
  `id_lista` int(11) NOT NULL,
  `id_disco` int(11) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_disco` int(11) NOT NULL,
  `calificacion` decimal(2,1) DEFAULT NULL CHECK (`calificacion` >= 0 and `calificacion` <= 5),
  `texto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `biografia` varchar(240) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `artistas`
--
ALTER TABLE `artistas`
  ADD PRIMARY KEY (`id_artista`);

--
-- Indices de la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD PRIMARY KEY (`id_cancion`),
  ADD KEY `id_disco` (`id_disco`);

--
-- Indices de la tabla `discos`
--
ALTER TABLE `discos`
  ADD PRIMARY KEY (`id_disco`),
  ADD KEY `id_artista` (`id_artista`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_usuario`,`id_disco`),
  ADD KEY `id_disco` (`id_disco`);

--
-- Indices de la tabla `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id_lista`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `lista_discos`
--
ALTER TABLE `lista_discos`
  ADD PRIMARY KEY (`id_lista`,`id_disco`),
  ADD KEY `id_disco` (`id_disco`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_disco` (`id_disco`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `artistas`
--
ALTER TABLE `artistas`
  MODIFY `id_artista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `canciones`
--
ALTER TABLE `canciones`
  MODIFY `id_cancion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT de la tabla `discos`
--
ALTER TABLE `discos`
  MODIFY `id_disco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `listas`
--
ALTER TABLE `listas`
  MODIFY `id_lista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD CONSTRAINT `canciones_ibfk_1` FOREIGN KEY (`id_disco`) REFERENCES `discos` (`id_disco`) ON DELETE CASCADE;

--
-- Filtros para la tabla `discos`
--
ALTER TABLE `discos`
  ADD CONSTRAINT `discos_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artistas` (`id_artista`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_disco`) REFERENCES `discos` (`id_disco`) ON DELETE CASCADE;

--
-- Filtros para la tabla `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `listas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lista_discos`
--
ALTER TABLE `lista_discos`
  ADD CONSTRAINT `lista_discos_ibfk_1` FOREIGN KEY (`id_lista`) REFERENCES `listas` (`id_lista`) ON DELETE CASCADE,
  ADD CONSTRAINT `lista_discos_ibfk_2` FOREIGN KEY (`id_disco`) REFERENCES `discos` (`id_disco`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_disco`) REFERENCES `discos` (`id_disco`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
