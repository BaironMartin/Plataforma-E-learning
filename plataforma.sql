-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-03-2022 a las 18:49:16
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

CREATE TABLE `archivos` (
  `idarchivos` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `tipo` varchar(200) NOT NULL,
  `tamanio` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `archivos`
--

INSERT INTO `archivos` (`idarchivos`, `nombre`, `tipo`, `tamanio`, `clave`, `usuario`) VALUES
(42, 'comprobante_de_pago.pdf', 'application/pdf', '17280', 'AaoepivxOJ', 'RondaRousey@prueba.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `idclase` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clase`
--

INSERT INTO `clase` (`idclase`, `nombre`, `clave`, `usuario`, `fecha`) VALUES
(56, 'English Undecimo', 'AaoepivxOJ', 'RondaRousey@prueba.com', '2022-02-03 01:05:06'),
(58, 'Matemáticas Undécimo', 'K2KTuwcMoc', 'DeanAmbrose@prueba.com', '2022-02-03 00:54:45'),
(59, 'TECNOLOGÍA E INFORMÁTICA Undecimo', '5JRSiYZIjM', 'MartinOettel@prueba.com', '2022-02-03 01:01:00'),
(60, 'CIENCIAS NATURALESY EDUCACION AMBIENTAL Undecimo', 'l6RoqX9XPA', 'WalterWhite@prueba.com', '2022-02-03 01:04:56'),
(61, 'CIENCIAS SOCIALES Undecimo', '2pczTMwaVu', 'BritaniKnight@prueba.com', '2022-02-03 01:19:12'),
(63, 'EDUCACIÓN FÍSICA, RECREACIÓN Y DEPORTES Undecimo', 'gPsYLXLZnf', 'JohnCena@prueba.com', '2022-02-03 01:13:44'),
(64, 'HUMANIDADES LENGUA CASTELLANA Undecimo', '2wm2sidLJN', 'DavidBautista@prueba.com', '2022-02-03 01:19:19'),
(65, 'FILOSOFIA Y CIENCIAS RELIGIOSAS Undecimo', 'J1PmUuCbq2', 'StephanieMcMahon@prueba.com', '2022-02-03 01:23:45'),
(66, 'EDUCACIÓN ARTÍSTICA Undecimo', 'VctF3XOxBV', 'ReyMysterio@prueba.com', '2022-02-03 01:26:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `idcomentario` int(11) NOT NULL,
  `idtema` varchar(11) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`idcomentario`, `idtema`, `clave`, `usuario`, `comentario`, `fecha`) VALUES
(12, '6', 'K2KTuwcMoc', 'DeanAmbrose@prueba.com', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2022-02-03 00:58:33'),
(13, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '2022-02-03 01:00:00'),
(14, '8', '5JRSiYZIjM', 'MartinOettel@prueba.com', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '2022-02-03 01:03:15'),
(15, '9', 'l6RoqX9XPA', 'WalterWhite@prueba.com', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"', '2022-02-03 01:07:39'),
(16, '10', '2pczTMwaVu', 'BritaniKnight@prueba.com', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.', '2022-02-03 01:11:37'),
(17, '11', 'gPsYLXLZnf', 'JohnCena@prueba.com', '\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"', '2022-02-03 01:16:28'),
(18, '13', 'J1PmUuCbq2', 'StephanieMcMahon@prueba.com', '\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"', '2022-02-03 01:25:24'),
(19, '14', 'VctF3XOxBV', 'ReyMysterio@prueba.com', '\"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"', '2022-02-03 01:29:16'),
(20, '6', 'K2KTuwcMoc', 'ArielCamacho@prueba.com', '\"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.\"', '2022-02-03 01:38:16'),
(21, '7', 'AaoepivxOJ', 'CesarSanchez@prueba.com', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2022-02-03 01:47:18'),
(22, '6', 'K2KTuwcMoc', 'ElMagallanes@prueba.com', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '2022-02-03 02:09:43'),
(23, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', '<h1 style=\"text-align:center\"><strong>Lorem Ipsum</strong></h1>\r\n\r\n<p style=\"text-align:justify\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut a nisl dolor. Nulla facilisi. Ut efficitur, lorem id dictum hendrerit, nisl elit volutpat tortor, id volutpat neque neque ac risus. Donec dictum accumsan orci, placerat efficitur lacus porttitor a. Cras convallis nulla massa, id congue odio aliquet non. Cras non bibendum est. Vivamus porta, ipsum non sollicitudin lacinia, eros enim rutrum quam, vel tincidunt sem risus sed lacus. Suspendisse semper scelerisque risus at scelerisque. Nulla facilisis molestie risus vitae rhoncus. Suspendisse potenti. Pellentesque sagittis et erat sed rhoncus. Phasellus tellus massa, sollicitudin at dictum eu, varius a urna.</p>\r\n', '2022-03-21 15:11:16'),
(24, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', '<h1 style=\"text-align:center\"><span style=\"color:#1abc9c\"><strong>Lorem ipsum dolor sit amet,</strong></span></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>consectetur adipiscing elit. Ut a nisl dolor. Nulla facilisi. Ut efficitur, lorem id dictum hendrerit, nisl elit volutpat tortor, id volutpat neque neque ac risus. Donec dictum accumsan orci, placerat efficitur lacus porttitor a. Cras convallis nulla massa, id congue odio aliquet non. Cras non bibendum est. Vivamus porta, ipsum non sollicitudin lacinia, eros enim rutrum quam, vel tincidunt sem risus sed lacus. Suspendisse semper scelerisque risus at scelerisque. Nulla facilisis molestie risus vitae rhoncus. Suspendisse potenti. Pellentesque sagittis et erat sed rhoncus. Phasellus tellus massa, sollicitudin at dictum eu, varius a urna.</p>\r\n', '2022-03-21 15:11:58'),
(26, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', '<p><a href=\"https://dam.ngenespanol.com/wp-content/uploads/2019/10/perros-personalidad-2.jpg\" target=\"_blank\"><img alt=\"\" src=\"https://dam.ngenespanol.com/wp-content/uploads/2019/10/perros-personalidad-2.jpg\" style=\"height:205px; width:400px\" /></a></p>\r\n', '2022-03-21 16:16:56'),
(27, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', '<p><a href=\"https://www.youtube.com/watch?v=g_UtsCOqHvo&amp;ab_channel=SoyAlexis\">https://www.youtube.com/watch?v=g_UtsCOqHvo&amp;ab_channel=SoyAlexis</a></p>\r\n', '2022-03-21 16:17:56'),
(28, '6', 'K2KTuwcMoc', 'ElMagallanes@prueba.com', '<h2 style=\"text-align:center\"><strong>&iquest;Qu&eacute; es Lorem Ipsum?</strong></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;es simplemente un texto ficticio de la industria de la impresi&oacute;n y la composici&oacute;n tipogr&aacute;fica. Lorem Ipsum ha sido el texto ficticio est&aacute;ndar de la industria desde la d&eacute;cada de 1500, cuando un impresor desconocido tom&oacute; una galera de tipo y la revolvi&oacute; para hacer un libro de espec&iacute;menes tipo. Ha sobrevivido no solo cinco siglos, sino tambi&eacute;n el salto a la composici&oacute;n tipogr&aacute;fica electr&oacute;nica, permaneciendo esencialmente sin cambios. Se populariz&oacute; en la d&eacute;cada de 1960 con el lanzamiento de hojas letraset que conten&iacute;an pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n como Aldus PageMaker que inclu&iacute;a versiones de Lorem Ipsum.</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;Por qu&eacute; lo usamos?</strong></h2>\r\n\r\n<p>Es un hecho establecido desde hace mucho tiempo que un lector se distraer&aacute; con el contenido legible de una p&aacute;gina al mirar su dise&ntilde;o. El punto de usar Lorem Ipsum es que tiene una distribuci&oacute;n de letras m&aacute;s o menos normal, en lugar de usar &#39;Contenido aqu&iacute;, contenido aqu&iacute;&#39;, lo que hace que parezca un ingl&eacute;s legible. Muchos paquetes de autoedici&oacute;n y editores de p&aacute;ginas web ahora usan Lorem Ipsum como su texto de modelo predeterminado, y una b&uacute;squeda de &#39;lorem ipsum&#39; descubrir&aacute; muchos sitios web a&uacute;n en su infancia. Varias versiones han evolucionado a lo largo de los a&ntilde;os, a veces por accidente, a veces a prop&oacute;sito (humor inyectado y similares).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;De d&oacute;nde viene?</strong></h2>\r\n\r\n<p>Contrariamente a la creencia popular, Lorem Ipsum no es simplemente un texto aleatorio. Tiene sus ra&iacute;ces en una pieza de literatura latina cl&aacute;sica del a&ntilde;o 45 aC, por lo que tiene m&aacute;s de 2000 a&ntilde;os de antig&uuml;edad. Richard McClintock, profesor de lat&iacute;n en el Hampden-Sydney College en Virginia, busc&oacute; una de las palabras latinas m&aacute;s oscuras, consectetur, de un pasaje de Lorem Ipsum, y revisando las citas de la palabra en la literatura cl&aacute;sica, descubri&oacute; la fuente indudable. Lorem Ipsum proviene de las secciones 1.10.32 y 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; (Los extremos del bien y del mal) de Cicer&oacute;n, escrito en el a&ntilde;o 45 a. C. Este libro es un tratado sobre la teor&iacute;a de la &eacute;tica, muy popular durante el Renacimiento. La primera l&iacute;nea de Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, proviene de una l&iacute;nea en la secci&oacute;n 1.10.32.</p>\r\n\r\n<p>El trozo est&aacute;ndar de Lorem Ipsum utilizado desde la d&eacute;cada de 1500 se reproduce a continuaci&oacute;n para los interesados. Las secciones 1.10.32 y 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicer&oacute;n tambi&eacute;n se reproducen en su forma original exacta, acompa&ntilde;adas de versiones en ingl&eacute;s de la traducci&oacute;n de 1914 de H. Rackham.</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;D&oacute;nde puedo conseguir algunos?</strong></h2>\r\n\r\n<p>Hay muchas variaciones de pasajes de Lorem Ipsum disponibles, pero la mayor&iacute;a han sufrido alteraciones de alguna forma, por humor inyectado o palabras aleatorias que no parecen ni siquiera ligeramente cre&iacute;bles. Si va a usar un pasaje de Lorem Ipsum, debe asegurarse de que no haya nada vergonzoso escondido en medio del texto. Todos los generadores Lorem Ipsum en Internet tienden a repetir trozos predefinidos seg&uacute;n sea necesario, lo que lo convierte en el primer generador verdadero en Internet. Utiliza un diccionario de m&aacute;s de 200 palabras latinas, combinado con un pu&ntilde;ado de estructuras de oraciones modelo, para generar Lorem Ipsum que parece razonable. Por lo tanto, el Lorem Ipsum generado siempre est&aacute; libre de repetici&oacute;n, humor inyectado o palabras no caracter&iacute;sticas, etc.</p>\r\n', '2022-03-21 16:22:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `ID` int(9) UNSIGNED NOT NULL,
  `para` varchar(180) NOT NULL,
  `de` varchar(180) NOT NULL,
  `leido` varchar(180) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `asunto` varchar(180) NOT NULL,
  `texto` text NOT NULL,
  `clave` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`ID`, `para`, `de`, `leido`, `fecha`, `asunto`, `texto`, `clave`) VALUES
(3, 'ElMagallanes@prueba.com', 'ArielCamacho@prueba.com', 'si', '2022-03-27 16:34:36', 'fg', '<p>ElMagallanes@prueba.comElMagallanes@prueba.comElMagallanes@prueba.comv</p>\r\n', 'AaoepivxOJ'),
(2, 'ArielCamacho@prueba.com', 'JohnCena@prueba.com', NULL, '2022-03-27 15:59:40', 'tarea 1', '<p>lorem ipsun</p>\r\n', 'gPsYLXLZnf'),
(4, 'RondaRousey@prueba.com', 'ElMagallanes@prueba.com', 'si', '2022-03-27 16:46:10', ' tarea 1', '<h2>&iquest;Qu&eacute; es Lorem Ipsum?</h2>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas &quot;Letraset&quot;, las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>\r\n', 'AaoepivxOJ'),
(5, 'RondaRousey@prueba.com ,ArielCamacho@prueba.com', 'ElMagallanes@prueba.com', NULL, '2022-03-27 16:44:25', 'yf', '<p>yyy</p>\r\n', 'AaoepivxOJ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `misclases`
--

CREATE TABLE `misclases` (
  `idmiclase` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `misclases`
--

INSERT INTO `misclases` (`idmiclase`, `usuario`, `clave`) VALUES
(12, 'ArielCamacho@prueba.com', 'a?cHBtbeSp'),
(13, 'ArielCamacho@prueba.com', 'AaoepivxOJ'),
(14, 'ArielCamacho@prueba.com', 'jyO7Hhqw2z'),
(15, 'ArielCamacho@prueba.com', 'K2KTuwcMoc'),
(16, 'ArielCamacho@prueba.com', '5JRSiYZIjM'),
(17, 'ArielCamacho@prueba.com', 'l6RoqX9XPA'),
(18, 'ArielCamacho@prueba.com', '2pczTMwaVu'),
(19, 'ArielCamacho@prueba.com', 'gPsYLXLZnf'),
(20, 'ArielCamacho@prueba.com', '2wm2sidLJN'),
(21, 'ArielCamacho@prueba.com', 'J1PmUuCbq2'),
(22, 'ArielCamacho@prueba.com', 'VctF3XOxBV'),
(23, 'CesarSanchez@prueba.com', 'AaoepivxOJ'),
(24, 'ElMagallanes@prueba.com', 'K2KTuwcMoc'),
(25, 'ElMagallanes@prueba.com', 'AaoepivxOJ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `idplan` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `texto` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fechaentrega` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`idplan`, `usuario`, `clave`, `titulo`, `texto`, `fecha`, `fechaentrega`) VALUES
(1, 'baironmartin2020@gmail.com', 'a?cHBtbeSp', 'Fase 1 - Operaciones Aritméticas ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2021-12-30 01:44:48', '2021-12-31'),
(2, 'baironmartin2020@gmail.com', 'a?cHBtbeSp', 'Fase 2  - Números Primos', ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-01-06 02:08:55', '2022-01-15'),
(5, 'baironmartin2020@gmail.com', 'CPHlpALWNK', 'FASE1 - EVOLUCION', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2021-12-30 02:36:58', '2022-01-16'),
(6, 'baironmartin2020@gmail.com', '4OP!0BIEaO', 'Fase 1 - Socialismo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-01-03 02:40:00', '2021-12-31'),
(8, 'baironmartin2020@gmail.com', '4OP!0BIEaO', 'Fase 2 - Capitalismo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-01-03 03:21:47', '2022-01-30'),
(9, 'baironmartin2020@gmail.com', 'a?cHBtbeSp', 'Fase 3 - Operaciones Avanzadas', 'voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-01-06 02:49:47', '2022-01-29'),
(11, 'baironmartin2020@gmail.com', 'WaxH(Cl87M', 'Lorem ipsum dolor sit amet, ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur aliquam enim eget eleifend dictum. Maecenas ut dapibus leo, et suscipit quam. Integer lacinia elementum nunc at vehicula. Vivamus eu urna pellentesque, luctus ex efficitur, efficitur justo. Aenean blandit diam sem, in porttitor est rhoncus id. Suspendisse vel lacus pulvinar, ultricies eros nec, fermentum ipsum. Cras tellus eros, tincidunt vitae purus id, maximus facilisis urna. Donec ullamcorper eget odio eu vulputate. Nunc ornare tristique tortor sed egestas. In semper orci sem, in elementum nibh luctus semper.', '2022-01-11 22:01:22', '2022-01-30'),
(13, 'RondaRousey@prueba.com', 'AaoepivxOJ', 'Tarea 2', 'Categorías gramaticales (adjetivos, preposiciones, conjunciones, adverbios, conectores, etc).', '2022-02-03 00:52:53', '2022-03-10'),
(14, 'DeanAmbrose@prueba.com', 'K2KTuwcMoc', 'Distribuciones De Frecuencia', '-Representaciones Graficas\r\n-Medidas De Tendencia Central Y Posicionamiento', '2022-02-03 00:56:23', '2022-02-20'),
(15, 'MartinOettel@prueba.com', '5JRSiYZIjM', 'PRESABERES ELECTRÓNICOS', 'Historia\r\nPower Point: Interfaz y utilidad. \r\nElementos básicos y funciones. \r\nVentana de PowerPoint (barras y elementos)\r\nInsertar diapositivas\r\nFondo de las diapositivas (color y estilos de \r\nfondo, degradado)\r\n', '2022-02-03 01:02:38', '2022-02-20'),
(16, 'WalterWhite@prueba.com', 'l6RoqX9XPA', 'Proceso Químico', 'Aspectos Fisicoquímicos de mezclas\r\nSoluciones\r\nCinética Química:\r\nFactores que influyen en la velocidad de una \r\nreacción\r\nEquilibrio Químico\r\n', '2022-02-03 01:06:56', '2022-02-20'),
(17, 'BritaniKnight@prueba.com', '2pczTMwaVu', 'Izquierda política', 'el laborismo, el socialismo, el comunismo', '2022-02-03 01:11:16', '2022-02-20'),
(18, 'JohnCena@prueba.com', 'gPsYLXLZnf', 'Deporte específico: “Fútbol  sala” ', 'Desarrollo y mejora de las \r\ncapacidades condicionales a \r\ntravés del proyecto transversal \r\nde “Juegos interclases 2015 ”. \r\nBeneficios de la actividad física \r\npara la salud. ', '2022-02-03 01:15:27', '2022-02-20'),
(19, 'DavidBautista@prueba.com', '2wm2sidLJN', 'Origen del Castellano', '1. Invasiones de la península Ibérica.\r\n2. Evolución de la lengua castellana.\r\n', '2022-02-03 01:20:55', '2022-02-20'),
(20, 'StephanieMcMahon@prueba.com', 'J1PmUuCbq2', 'COMPRENDER LAS RAICES  HISTORICAS, EPISTEMICAS,  ONTOLOGICAS,  AXIOLOGICAS, RELIGIOSAS', 'I: El entorno cultural, social y político al inicio de la  revolución científica\r\nII: Entre la moral la fe y la razón: conflicto cultural, debate  ideológico\r\nIII: Introducción a la historia de la filosofía de la ciencia. Lectura asignada\r\nIV: Virtualidad en lo filosófico y religioso entorno a la revolución científica\r\n', '2022-02-03 01:25:05', '2022-02-20'),
(21, 'ReyMysterio@prueba.com', 'VctF3XOxBV', 'CALIGRAFIA ARTISTICA', 'Expresividad de la letra.', '2022-02-03 01:28:03', '2022-02-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `idtarea` int(11) NOT NULL,
  `texto` text NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archivo` varchar(200) NOT NULL,
  `evaluacion` varchar(200) DEFAULT NULL,
  `idplan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`idtarea`, `texto`, `usuario`, `clave`, `fecha`, `archivo`, `evaluacion`, `idplan`) VALUES
(28, 'sin novedad', 'ElMagallanes@prueba.com', 'AaoepivxOJ', '2022-02-04 00:02:46', 'ComprobantePSEDaviplata20220124180537.pdf', '49', 12),
(29, 'sin novedad', 'ElMagallanes@prueba.com', 'AaoepivxOJ', '2022-02-04 00:04:47', 'comprobante_de_pago.pdf', '50', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE `temas` (
  `idtema` int(11) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `tema` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`idtema`, `clave`, `usuario`, `tema`, `fecha`) VALUES
(2, 'a?cHBtbeSp', 'baironmartin2020@gmail.com', 'Fase 1 - Operaciones Aritméticas', '2022-01-28 01:37:13'),
(6, 'K2KTuwcMoc', 'DeanAmbrose@prueba.com', 'Distribuciones De Frecuencia', '2022-02-03 00:58:24'),
(7, 'AaoepivxOJ', 'RondaRousey@prueba.com', 'Repaso general de los temas de grado décimo', '2022-02-03 00:59:49'),
(8, '5JRSiYZIjM', 'MartinOettel@prueba.com', 'PRESABERES ELECTRÓNICOS', '2022-02-03 01:02:46'),
(9, 'l6RoqX9XPA', 'WalterWhite@prueba.com', 'Proceso Químico', '2022-02-03 01:07:07'),
(10, '2pczTMwaVu', 'BritaniKnight@prueba.com', 'Izquierda política', '2022-02-03 01:11:25'),
(11, 'gPsYLXLZnf', 'JohnCena@prueba.com', 'Deporte específico: “Fútbol sala”', '2022-02-03 01:15:49'),
(12, '2wm2sidLJN', 'DavidBautista@prueba.com', 'Origen del Castellano', '2022-02-03 01:21:05'),
(13, 'J1PmUuCbq2', 'StephanieMcMahon@prueba.com', 'COMPRENDER LAS RAICES HISTORICAS, EPISTEMICAS, ONTOLOGICAS, AXIOLOGICAS, RELIGIOSAS', '2022-02-03 01:25:12'),
(14, 'VctF3XOxBV', 'ReyMysterio@prueba.com', 'CALIGRAFIA ARTISTICA', '2022-02-03 01:28:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Email` varchar(200) NOT NULL,
  `Clave` varchar(200) NOT NULL,
  `Nombre` varchar(200) NOT NULL,
  `Foto` varchar(200) NOT NULL,
  `Tipo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Email`, `Clave`, `Nombre`, `Foto`, `Tipo`) VALUES
('AlfredoOlivas@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'José Alfredo Olivas Rojas', '0c7f0cfcf328d927ddae0acedf44d5e1.jpg', 'Estudiante'),
('ArielCamacho@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Jose Ariel Camacho  Barraza', 'descargar.jpg', 'Estudiante'),
('BritaniKnight@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Saraya-Jade Bevis', 'esp.-luchadora-jpg.jpg', 'Docente'),
('CarlosUlisesGomez@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Carlos Ulises Gomez', 'default.jpg', 'Estudiante'),
('CesarSanchez@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'César Sánchez', '-wacxCdO_400x400.jpg', 'Estudiante'),
('DavidBautista@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'David Michael Bautista Jr.', '181718_1073911.jpg', 'Docente'),
('DeanAmbrose@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Jonathan David Good ', 'Dean-Ambrose-8.jpg', 'Docente'),
('ElMagallanes@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Oscar Magallanes', 'unnamed.jpg', 'Estudiante'),
('EmilioGarra@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Emilio Garra', 'descargar (1).jpg', 'Estudiante'),
('JohnCena@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'John Felix Anthony Cena, Jr.', '640px-John_Cena_July_2018.jpg', 'Docente'),
('MartinOettel@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Martín Oettel', 'images (1).jpg', 'Docente'),
('ReyMysterio@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Óscar Gutiérrez', '6ZOPLQVVTFCSNGNSK6VSZU4BUQ.jpg', 'Docente'),
('RondaRousey@prueba.com', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', 'Ronda Jean Rousey', 'images.jpg', 'Docente'),
('StephanieMcMahon@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Stephanie McMahon Levesque​​ ', '1200px-Stephanie_McMahon_November_2018.jpg', 'Docente'),
('WalterWhite@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Walter Hartwell White', 'breaking_bad_vince_gilligan_amc.jpg', 'Docente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`idarchivos`);

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`idclase`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`idcomentario`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `misclases`
--
ALTER TABLE `misclases`
  ADD PRIMARY KEY (`idmiclase`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`idplan`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`idtarea`);

--
-- Indices de la tabla `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`idtema`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos`
--
ALTER TABLE `archivos`
  MODIFY `idarchivos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `clase`
--
ALTER TABLE `clase`
  MODIFY `idclase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `idcomentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `ID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `misclases`
--
ALTER TABLE `misclases`
  MODIFY `idmiclase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `idplan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `idtarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `temas`
--
ALTER TABLE `temas`
  MODIFY `idtema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
