-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-04-2023 a las 03:18:35
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
-- Estructura de tabla para la tabla `admin_p`
--

CREATE TABLE `admin_p` (
  `id_admin` int(10) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `contrasenia` varchar(200) NOT NULL,
  `cargo` varchar(200) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `edad` int(2) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `baner` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `admin_p`
--

INSERT INTO `admin_p` (`id_admin`, `nombre`, `apellidos`, `email`, `contrasenia`, `cargo`, `telefono`, `edad`, `foto`, `baner`) VALUES
(1, 'José Francisco ', 'Garza Durón', 'pepegar@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'admin', '3147483647', 43, 'https://www.famousbirthdays.com/faces/garza-pepe-image.jpg', 'https://imagenes.muyinteresante.es/files/vertical_composte_image/uploads/2022/10/11/6345c1f0ad0b2.jpeg'),
(9, 'Valentin', 'Elizalde', 'ValentinElizalde@prueba.com', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', 'Rector', '+573154283865', 40, 'f638x638-19558_77725_5050.jpg', '22092.jpg'),
(10, 'Dolores Janney ', 'Rivera Saavedra', 'JenniRivera@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Coordinador', '+573225901805', 34, '500x500.jpg', 'Jenni Rivera.jpg');

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
(51, 'Guia-1-VERBO-TO-BE-PRESENTE.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '685151', 'AaoepivxOJ', 'RondaRousey@prueba.com'),
(52, '11. Desigualdades.pdf', 'application/pdf', '376572', 'K2KTuwcMoc', 'DeanAmbrose@prueba.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `idclase` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `imagen` varchar(200) NOT NULL,
  `grado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clase`
--

INSERT INTO `clase` (`idclase`, `nombre`, `clave`, `usuario`, `fecha`, `imagen`, `grado`) VALUES
(56, 'English Undecimo', 'AaoepivxOJ', 'RondaRousey@prueba.com', '2023-03-05 16:21:17', 'https://cdn-icons-png.flaticon.com/512/5388/5388698.png', 'undecimo'),
(58, 'Matemáticas Undécimo', 'K2KTuwcMoc', 'DeanAmbrose@prueba.com', '2023-03-05 16:21:26', 'https://cdn-icons-png.flaticon.com/512/2084/2084541.png', 'undecimo'),
(59, 'TECNOLOGÍA E INFORMÁTICA Undecimo', '5JRSiYZIjM', 'MartinOettel@prueba.com', '2023-03-05 16:21:31', 'https://cdn-icons-png.flaticon.com/512/90/90808.png', 'undecimo'),
(60, 'CIENCIAS NATURALESY EDUCACION AMBIENTAL Undecimo', 'l6RoqX9XPA', 'WalterWhite@prueba.com', '2023-03-05 16:21:37', 'https://cdn-icons-png.flaticon.com/512/6502/6502964.png', 'undecimo'),
(61, 'CIENCIAS SOCIALES Undecimo', '2pczTMwaVu', 'BritaniKnight@prueba.com', '2023-03-05 16:21:41', 'https://cdn-icons-png.flaticon.com/512/4389/4389236.png', 'undecimo'),
(63, 'EDUCACIÓN FÍSICA, RECREACIÓN Y DEPORTES Undecimo', 'gPsYLXLZnf', 'JohnCena@prueba.com', '2023-03-05 16:21:48', 'https://thumbs.dreamstime.com/b/ejemplo-colorido-sobre-deporte-y-educaci%C3%B3n-f%C3%ADsica-en-estilo-plano-moderno-icono-sujeto-de-la-universidad-108165153.jpg', 'undecimo'),
(64, 'HUMANIDADES LENGUA CASTELLANA Undecimo', '2wm2sidLJN', 'DavidBautista@prueba.com', '2023-03-05 16:21:54', 'https://cdn-icons-png.flaticon.com/512/1377/1377973.png', 'undecimo'),
(65, 'FILOSOFIA Y CIENCIAS RELIGIOSAS Undecimo', 'J1PmUuCbq2', 'StephanieMcMahon@prueba.com', '2023-03-05 16:22:00', 'https://cdn-icons-png.flaticon.com/512/4576/4576683.png', 'undecimo'),
(66, 'EDUCACIÓN ARTÍSTICA Undecimo', 'VctF3XOxBV', 'ReyMysterio@prueba.com', '2023-03-05 16:22:06', 'https://colsalle.edu.co/WEB/images/Zona-Academica/Iconos/EA.png', 'undecimo'),
(75, 'English Decimo', 'SPOJoHbPN7', 'RondaRousey@prueba.com', '2023-03-05 16:22:12', 'https://www.wallstreetenglish.com.ar/hs-fs/hubfs/icono%20social%20classes-1.png?width=227&name=icono%20social%20classes-1.png', 'decimo'),
(78, 'English Sexto', 'ojX1nmrFnN', 'RondaRousey@prueba.com', '2023-03-05 16:53:10', 'https://cdn-icons-png.flaticon.com/512/5388/5388604.png', 'sexto'),
(79, 'Decimo Ciencias Sociales ', 'Kxj8QrbZKe', 'BritaniKnight@prueba.com', '2023-03-05 16:22:27', 'https://cdn-icons-png.flaticon.com/512/4634/4634952.png', 'decimo'),
(92, 'English Octavo', 'G17ufnl2NW', 'RondaRousey@prueba.com', '2023-03-05 16:53:18', 'https://img.freepik.com/vector-gratis/fondo-lettering-usted-habla-ingles_23-2147871113.jpg?w=2000', 'octavo'),
(93, 'Ciencias sociales', 'mLxWbtHwYW', 'BritaniKnight@prueba.com', '2023-03-05 17:01:09', 'https://www.definicion.co/wp-content/uploads/2015/04/Dentro-de-las-Ciencias-Naturales-hay-distintas-ramas1.jpg', 'cuarto');

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
(28, '6', 'K2KTuwcMoc', 'ElMagallanes@prueba.com', '<h2 style=\"text-align:center\"><strong>&iquest;Qu&eacute; es Lorem Ipsum?</strong></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;es simplemente un texto ficticio de la industria de la impresi&oacute;n y la composici&oacute;n tipogr&aacute;fica. Lorem Ipsum ha sido el texto ficticio est&aacute;ndar de la industria desde la d&eacute;cada de 1500, cuando un impresor desconocido tom&oacute; una galera de tipo y la revolvi&oacute; para hacer un libro de espec&iacute;menes tipo. Ha sobrevivido no solo cinco siglos, sino tambi&eacute;n el salto a la composici&oacute;n tipogr&aacute;fica electr&oacute;nica, permaneciendo esencialmente sin cambios. Se populariz&oacute; en la d&eacute;cada de 1960 con el lanzamiento de hojas letraset que conten&iacute;an pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n como Aldus PageMaker que inclu&iacute;a versiones de Lorem Ipsum.</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;Por qu&eacute; lo usamos?</strong></h2>\r\n\r\n<p>Es un hecho establecido desde hace mucho tiempo que un lector se distraer&aacute; con el contenido legible de una p&aacute;gina al mirar su dise&ntilde;o. El punto de usar Lorem Ipsum es que tiene una distribuci&oacute;n de letras m&aacute;s o menos normal, en lugar de usar &#39;Contenido aqu&iacute;, contenido aqu&iacute;&#39;, lo que hace que parezca un ingl&eacute;s legible. Muchos paquetes de autoedici&oacute;n y editores de p&aacute;ginas web ahora usan Lorem Ipsum como su texto de modelo predeterminado, y una b&uacute;squeda de &#39;lorem ipsum&#39; descubrir&aacute; muchos sitios web a&uacute;n en su infancia. Varias versiones han evolucionado a lo largo de los a&ntilde;os, a veces por accidente, a veces a prop&oacute;sito (humor inyectado y similares).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;De d&oacute;nde viene?</strong></h2>\r\n\r\n<p>Contrariamente a la creencia popular, Lorem Ipsum no es simplemente un texto aleatorio. Tiene sus ra&iacute;ces en una pieza de literatura latina cl&aacute;sica del a&ntilde;o 45 aC, por lo que tiene m&aacute;s de 2000 a&ntilde;os de antig&uuml;edad. Richard McClintock, profesor de lat&iacute;n en el Hampden-Sydney College en Virginia, busc&oacute; una de las palabras latinas m&aacute;s oscuras, consectetur, de un pasaje de Lorem Ipsum, y revisando las citas de la palabra en la literatura cl&aacute;sica, descubri&oacute; la fuente indudable. Lorem Ipsum proviene de las secciones 1.10.32 y 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; (Los extremos del bien y del mal) de Cicer&oacute;n, escrito en el a&ntilde;o 45 a. C. Este libro es un tratado sobre la teor&iacute;a de la &eacute;tica, muy popular durante el Renacimiento. La primera l&iacute;nea de Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, proviene de una l&iacute;nea en la secci&oacute;n 1.10.32.</p>\r\n\r\n<p>El trozo est&aacute;ndar de Lorem Ipsum utilizado desde la d&eacute;cada de 1500 se reproduce a continuaci&oacute;n para los interesados. Las secciones 1.10.32 y 1.10.33 de &quot;de Finibus Bonorum et Malorum&quot; de Cicer&oacute;n tambi&eacute;n se reproducen en su forma original exacta, acompa&ntilde;adas de versiones en ingl&eacute;s de la traducci&oacute;n de 1914 de H. Rackham.</p>\r\n\r\n<h2 style=\"text-align:center\"><strong>&iquest;D&oacute;nde puedo conseguir algunos?</strong></h2>\r\n\r\n<p>Hay muchas variaciones de pasajes de Lorem Ipsum disponibles, pero la mayor&iacute;a han sufrido alteraciones de alguna forma, por humor inyectado o palabras aleatorias que no parecen ni siquiera ligeramente cre&iacute;bles. Si va a usar un pasaje de Lorem Ipsum, debe asegurarse de que no haya nada vergonzoso escondido en medio del texto. Todos los generadores Lorem Ipsum en Internet tienden a repetir trozos predefinidos seg&uacute;n sea necesario, lo que lo convierte en el primer generador verdadero en Internet. Utiliza un diccionario de m&aacute;s de 200 palabras latinas, combinado con un pu&ntilde;ado de estructuras de oraciones modelo, para generar Lorem Ipsum que parece razonable. Por lo tanto, el Lorem Ipsum generado siempre est&aacute; libre de repetici&oacute;n, humor inyectado o palabras no caracter&iacute;sticas, etc.</p>\r\n', '2022-03-21 16:22:47'),
(33, '7', 'AaoepivxOJ', 'RondaRousey@prueba.com', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/8iLPYo9p3I0?controls=0\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>', '2023-01-02 22:53:04'),
(45, '7', 'AaoepivxOJ', 'jesusortiz@prueba.com', '<p><strong>Sed ut perspiciatis unde omnis</strong></p>\r\n\r\n<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>\r\n', '2023-03-09 02:08:27'),
(46, '16', 'AaoepivxOJ', 'jesusortiz@prueba.com', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', '2023-03-09 02:11:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto`
--

CREATE TABLE `concepto` (
  `id` int(10) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `concepto` text NOT NULL,
  `user` varchar(200) NOT NULL,
  `clase` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `concepto`
--

INSERT INTO `concepto` (`id`, `titulo`, `concepto`, `user`, `clase`) VALUES
(3, 'TEMA1: VERBO TO BE', '<p><strong>COMPETENCIA1</strong>: Intercambio informaci&oacute;n personal empleando el verbo to be</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>INDICADORES DE COMPETENCIA&nbsp;</strong></p>\r\n\r\n<ul>\r\n	<li>DESEMPE&Ntilde;O COGNITIVO: Pregunto y respondo en forma adecuada empleando el verbo to be.</li>\r\n	<li>DESEMPE&Ntilde;O PROCEDIMENTAL: Realizo ejercicios de intercambio de informaci&oacute;n y haciendo uso de las estructuras del presente con el to be.</li>\r\n	<li>DESEMPE&Ntilde;O VALORATIVO: Participo activamente de las clases y presento los trabajos a tiempo</li>\r\n</ul>\r\n', 'RondaRousey@prueba.com', 'AaoepivxOJ'),
(4, 'TEMA2: HOW MANY, HOW MUCH', '<h3><img alt=\"\" src=\" https://www.bitgab.com/uploads/1618954998-how-much-how-many-1618954998.png\" style=\"float:left; height:100px; margin-right:10px; width:100px\" />COMPETENCIA2: Empleo los cuantificadores para expresarme en diferentes situaciones comunicativas.</h3>\r\n\r\n<p>INDICADORES DE COMPETENCIA</p>\r\n\r\n<ul>\r\n	<li>DESEMPE&Ntilde;O COGNITIVO: Uso adecuadamente las expresiones How many y How much, There is y There are al expresarme sobre cantidades.</li>\r\n	<li>DESEMPE&Ntilde;O PROCEDIMENTAL: Realizo ejercicios en los que aplico las estructuras vistas.</li>\r\n	<li>DESEMPE&Ntilde;O VALORATIVO: Participo activamente de los procesos de la clase.</li>\r\n</ul>\r\n', 'RondaRousey@prueba.com', 'AaoepivxOJ'),
(8, 'CÁLCULO', '<ul>\r\n	<li>&nbsp;Sistemas de n&uacute;meros reales y sus propiedades.</li>\r\n	<li> Propiedades de orden en el sistema de los n&uacute;meros Reales.</li>\r\n	<li> Inecuaciones y valor absoluto.</li>\r\n	<li> Concepto de funci&oacute;n, Funci&oacute;n lineal y funci&oacute;n af&iacute;n.</li>\r\n	<li> Funci&oacute;n cuadr&aacute;tica.</li>\r\n	<li> Funciones: polin&oacute;micas, racionales, radicales, exponenciales, logar&iacute;tmicas, valor absoluto, parte entera y por trazos.</li>\r\n	<li> Funciones inyectivas, sobreyectivas y biyectivas.</li>\r\n</ul>\r\n\r\n<p>Operaciones entre funciones. Interpreta y elabora el bosquejo de las diferentes funciones teniendo en cuenta los elementos b&aacute;sicos y las propiedades de cada una de ellas.</p>\r\n\r\n<p><strong>INTERPRETATIVO:</strong> Reconoce y clasifica las funciones teniendo en cuenta su estructura y las variables que la componen.</p>\r\n\r\n<p><strong>ARGUMENTATIVO:</strong> Gr&aacute;fica en el plano cartesiano las diferentes funciones con base a sus elementos y determina su Dominio y Rango.</p>\r\n\r\n<p><strong>PROPOSITIVO:</strong> Argumenta por medio de gr&aacute;ficas y expresiones algebraicas la clasificaci&oacute;n y elementos de una funci&oacute;n</p>\r\n', 'DeanAmbrose@prueba.com', 'K2KTuwcMoc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen`
--

CREATE TABLE `examen` (
  `id` int(10) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `imagen` varchar(200) NOT NULL DEFAULT 'https://cdn-icons-png.flaticon.com/512/4403/4403531.png',
  `periodo` varchar(3) NOT NULL,
  `cierre` date DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bandera` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `examen`
--

INSERT INTO `examen` (`id`, `nombre`, `clave`, `usuario`, `imagen`, `periodo`, `cierre`, `fecha`, `bandera`) VALUES
(22, 'verbo tobe', 'AaoepivxOJ', 'RondaRousey@prueba.com', 'https://aulaenjuego.com/wp-content/uploads/2020/05/Juego-verbo-To-Be-copia-1024x724.png', 'I', '2023-03-12', '2023-03-23 15:54:13', 'fLKi9kcJtqDBTR1DwrLk'),
(36, 'Was Were', 'AaoepivxOJ', 'RondaRousey@prueba.com', 'https://img.freepik.com/vector-premium/prueba-estilo-comic-pop-art-quiz-palabra-juego-inteligente-diseno-ilustracion-vectorial_180786-81.jpg?w=2000', 'I', '2023-03-26', '2023-03-18 23:13:20', 'zV3KRmRb8CwpH1Y0zZOT');

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
  `clave` varchar(50) NOT NULL,
  `file` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`ID`, `para`, `de`, `leido`, `fecha`, `asunto`, `texto`, `clave`, `file`) VALUES
(4, 'RondaRousey@prueba.com', 'ElMagallanes@prueba.com', 'si', '2023-03-26 17:40:52', ' tarea 1', '<h2>&iquest;Qu&eacute; es Lorem Ipsum?</h2>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas &quot;Letraset&quot;, las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>\r\n', 'AaoepivxOJ', 'vacio'),
(18, 'RondaRousey@prueba.com', 'Hassanlaija@prueba.co', 'si', '2023-03-26 17:36:56', 'entrega tarea', '<p>&quot;At vero eos et accusamus et iusto</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>odio dignissimos ducimus qui blanditiis praesentium</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus,</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.&quot;</p>\r\n', 'AaoepivxOJ', 'Guia-1-VERBO-TO-BE-PRESENTE-Ciclo-3-INGLES.-MONICA.pdf'),
(8, 'RondaRousey@prueba.com', 'ElMagallanes@prueba.com', 'si', '2023-03-26 17:41:01', 'prueba', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>\r\n', 'AaoepivxOJ', 'vacio'),
(9, 'EmilioGarra@prueba.com', 'jesusortiz@prueba.com', NULL, '2023-03-26 17:40:54', 'ayuda', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', 'vacio'),
(10, 'RondaRousey@prueba.com', 'EmilioGarra@prueba.com', 'si', '2023-03-26 17:40:43', 'prueba', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', 'vacio'),
(11, 'RondaRousey@prueba.com', 'EmilioGarra@prueba.com', 'si', '2023-03-26 17:40:41', 'prueba', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', 'vacio'),
(12, 'RondaRousey@prueba.com', 'EmilioGarra@prueba.com', 'si', '2023-03-26 17:40:38', 'prueba', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', 'vacio'),
(17, 'ArielCamacho@prueba.com', 'RondaRousey@prueba.com', 'si', '2023-03-26 18:14:30', 'trabajo gruoal', '<p>&quot;Lorem ipsum dolor sit amet,</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:center\"><strong>consectetur adipiscing elit,</strong></p>\r\n\r\n<ol>\r\n	<li>sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>\r\n	<li>Ut enim ad minim veniam,</li>\r\n</ol>\r\n\r\n<p>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', 'vacio'),
(16, 'Hassanlaija@prueba.co', 'RondaRousey@prueba.com', 'si', '2023-03-26 16:58:26', 'Entrada tardia', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error</p>\r\n\r\n<p><strong>sit voluptatem accusantium doloremque laudantium,</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img alt=\"Verbo To be - Nivel A1 - GCFGlobal Idiomas\" src=\"data:image/png;base64,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\" /></p>\r\n\r\n<p>totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Plan_estudios_individual_1010117306_201.pdf'),
(19, 'RondaRousey@prueba.com', 'CesarSanchez@prueba.com', 'si', '2023-04-06 23:28:27', 'examen was', '<p>cordial saludo</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>me gustar&iacute;a saber si hay la posibilidad de que amplie el tiempo para resolver el examen was were</p>\r\n', 'AaoepivxOJ', 'vacio'),
(20, 'Carolinaross@prueba.com', 'RondaRousey@prueba.com', 'si', '2023-04-06 23:30:29', 'prueba', '<p>&quot;Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.&quot;</p>\r\n', 'AaoepivxOJ', '11. Desigualdades.pdf'),
(21, 'ArielCamacho@prueba.com', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(22, 'CesarSanchez@prueba.com', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(23, 'ElMagallanes@prueba.com', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(24, 'EmilioGarra@prueba.com', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(25, 'jesusortiz@prueba.com', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(26, 'Hassanlaija@prueba.co', 'RondaRousey@prueba.com', NULL, '2023-04-06 23:41:58', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf'),
(27, 'Carolinaross@prueba.com', 'RondaRousey@prueba.com', 'si', '2023-04-06 23:42:31', 'Prueba grupal', '<p>&quot;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?&quot;</p>\r\n', 'AaoepivxOJ', 'Mi padre don Cristobal Colon - Felicitas Corbella.pdf');

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
(14, 'ArielCamacho@prueba.com', 'jyO7Hhqw2z'),
(15, 'ArielCamacho@prueba.com', 'K2KTuwcMoc'),
(16, 'ArielCamacho@prueba.com', '5JRSiYZIjM'),
(18, 'ArielCamacho@prueba.com', '2pczTMwaVu'),
(19, 'ArielCamacho@prueba.com', 'gPsYLXLZnf'),
(20, 'ArielCamacho@prueba.com', '2wm2sidLJN'),
(21, 'ArielCamacho@prueba.com', 'J1PmUuCbq2'),
(22, 'ArielCamacho@prueba.com', 'VctF3XOxBV'),
(24, 'ElMagallanes@prueba.com', 'K2KTuwcMoc'),
(30, 'ElMagallanes@prueba.com', 'KdlI2ouPV2'),
(33, 'ElMagallanes@prueba.com', 'wy8hQ7dOH5'),
(34, 'Marcoosuna@prueba.com', 'ojX1nmrFnN'),
(42, 'ArielCamacho@prueba.com', 'AaoepivxOJ'),
(43, 'ArielCamacho@prueba.com', 'l6RoqX9XPA'),
(44, 'CesarSanchez@prueba.com', 'AaoepivxOJ'),
(45, 'CesarSanchez@prueba.com', 'K2KTuwcMoc'),
(46, 'ElMagallanes@prueba.com', 'AaoepivxOJ'),
(47, 'EmilioGarra@prueba.com', 'AaoepivxOJ'),
(51, 'jesusortiz@prueba.com', 'AaoepivxOJ'),
(52, 'jesusortiz@prueba.com', 'K2KTuwcMoc'),
(53, 'jesusortiz@prueba.com', '5JRSiYZIjM'),
(54, 'jesusortiz@prueba.com', 'l6RoqX9XPA'),
(55, 'jesusortiz@prueba.com', '2pczTMwaVu'),
(56, 'jesusortiz@prueba.com', 'gPsYLXLZnf'),
(57, 'jesusortiz@prueba.com', '2wm2sidLJN'),
(58, 'jesusortiz@prueba.com', 'VctF3XOxBV'),
(59, 'jesusortiz@prueba.com', 'J1PmUuCbq2'),
(60, 'Hassanlaija@prueba.co', 'AaoepivxOJ'),
(61, 'Carolinaross@prueba.com', 'AaoepivxOJ');

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
  `periodo` varchar(3) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fechaentrega` date NOT NULL,
  `bandera` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`idplan`, `usuario`, `clave`, `titulo`, `texto`, `periodo`, `fecha`, `fechaentrega`, `bandera`) VALUES
(58, 'RondaRousey@prueba.com', 'AaoepivxOJ', '¿Cuál es el verbo to be?', '<p>El verbo to be es uno de los m&aacute;s camale&oacute;nicos del ingl&eacute;s, por lo que en muchas ocasiones su significado depende del contexto en el que te lo encuentres. Sin embargo, sus significados principales son los siguientes:&nbsp;<strong>Ser</strong>.</p>\r\n', 'I', '2023-03-14 21:06:02', '2023-03-12', 'xAxg8oqzwaDH9jWCyRay'),
(62, 'RondaRousey@prueba.com', 'AaoepivxOJ', 'verbo tobe', '<p>Examen</p>\r\n', 'I', '2023-03-23 15:54:13', '2023-03-12', 'fLKi9kcJtqDBTR1DwrLk'),
(89, 'RondaRousey@prueba.com', 'AaoepivxOJ', 'Was Were', 'Examen', 'I', '2023-03-18 23:13:20', '2023-03-26', 'zV3KRmRb8CwpH1Y0zZOT'),
(91, 'DeanAmbrose@prueba.com', 'K2KTuwcMoc', 'Desigualdades', '<p>Una desigualdad estricta&nbsp;<strong>se produce cuando ambos valores son diferentes y, por lo tanto, uno es mayor que el otro</strong>.</p>\r\n', 'I', '2023-03-26 18:30:15', '2023-03-31', 'qtBKguGbyeMO8QhvsQRz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(200) NOT NULL,
  `opciona` varchar(50) NOT NULL,
  `opcionb` varchar(50) NOT NULL,
  `obcionc` varchar(50) NOT NULL,
  `obciond` varchar(50) NOT NULL,
  `correcta` varchar(50) NOT NULL,
  `idp` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`, `opciona`, `opcionb`, `obcionc`, `obciond`, `correcta`, `idp`) VALUES
(12, '__ am busy?', 'you', 'it', 'she', 'i', 'D', 22),
(13, '__ are play soccer?', 'i', 'she', 'you', 'it', 'C', 22),
(14, '__ is works today?', 'he', 'i', 'they', 'we', 'A', 22),
(22, 'They ___ at school (Ellos estaban en el colegio)', 'Was', 'Were', 'Are', 'Is', 'B', 36),
(23, 'Paul __ my friend (Paul era mi amigo)', 'Was', 'Were', 'Are', 'I', 'A', 36),
(24, 'My brother and I __ at Brazil last month (Mi hermano y yo estuvimos en Brasil el mes pasado)', 'Are', 'Was', 'Were', 'IS', 'C', 36),
(25, 'Angela __ a good teacher (Ángela era una buena profesora)', 'Were', 'Are', 'Is', 'Was', 'D', 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias`
--

CREATE TABLE `referencias` (
  `id` int(10) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `referencia` text NOT NULL,
  `usuario` varchar(11) NOT NULL,
  `clave` varchar(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `referencias`
--

INSERT INTO `referencias` (`id`, `titulo`, `referencia`, `usuario`, `clave`, `fecha`) VALUES
(11, 'Verbo To be', '<p><em>Verbo To be - Nivel A1 - GCFGlobal Idiomas</em>. (2023). GCFGlobal Idiomas. <a href=\"https://idiomas.gcfglobal.org/es/curso/ingles/a1/verbo-to-be/\">https://idiomas.gcfglobal.org/es/curso/ingles/a1/verbo-to-be/</a></p>\r\n\r\n<p>&zwnj;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/6ToyS-u_YLw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allo', 'RondaRousey', 'AaoepivxOJ', '2023-03-07 17:37:01'),
(14, 'WAS AND WERE', '<p>Cuando usamos &ldquo;was&rdquo; (primera forma del pasado simple en verbo to be) empezamos las oraciones con I, He, She o It. Para&nbsp;<strong>usar</strong>&nbsp;el &ldquo;<strong>were</strong>&rdquo; en las oraciones, lo debemos de&nbsp;<strong>utilizar</strong>&nbsp;junto al You, They o We.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><iframe frameborder=\"0\" height=\"315\" src=\"https://www.youtube.com/embed/r8Hv3GOv_b0\" title=\"YouTube video player\" width=\"560\"></iframe></p>\r\n', 'RondaRousey', 'AaoepivxOJ', '2023-03-18 23:42:27'),
(15, 'Desigualdades y Programación Lineal', '<p>Luis, J., &amp; G&oacute;mez, D. (n.d.).&nbsp;<em>Problemas Resueltos de Desigualdades y Programaci&oacute;n Lineal Para el curso de C&aacute;lculo Diferencial de Qu&iacute;mico Bi&oacute;logo Desigualdades Contenido</em>. <a href=\"https://www.mat.uson.mx/~jldiaz/Documents/Desigualdades/SistemasN.pdf\">https://www.mat.uson.mx/~jldiaz/Documents/Desigualdades/SistemasN.pdf</a></p>\r\n\r\n<p>&zwnj;</p>\r\n', 'DeanAmbrose', 'K2KTuwcMoc', '2023-03-26 18:33:49'),
(16, 'DESIGUALDADES LINEALES ', '<p>julioprofe. (2009). DESIGUALDADES LINEALES - Ejercicio 1 [YouTube Video]. In&nbsp;<em>YouTube</em>. https://www.youtube.com/watch?v=jSZWvCh2PqI&amp;ab_channel=julioprofe</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&zwnj;</p>\r\n<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/jSZWvCh2PqI\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>', 'DeanAmbrose', 'K2KTuwcMoc', '2023-03-26 18:34:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reuniones`
--

CREATE TABLE `reuniones` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `observacion` mediumtext NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reuniones`
--

INSERT INTO `reuniones` (`id`, `usuario`, `clave`, `titulo`, `observacion`, `fecha`) VALUES
(7, 'RondaRousey@prueba.com', 'AaoepivxOJ', 'verbo tobe', '<p>Verbo tobe un decimo<br />\r\nS&aacute;bado, 11 de marzo &middot; 8:30 &ndash; 9:30pm<br />\r\nInformaci&oacute;n para unirse a Google Meet<br />\r\nEnlace a la videollamada: <a href=\"https://meet.google.com/fby-jhqp-svh\">https://meet.google.com/fby-jhqp-svh</a></p>\r\n', '2023-03-11 08:25:00');

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
  `periodo` varchar(3) NOT NULL,
  `evaluacion` varchar(200) DEFAULT NULL,
  `idplan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`idtarea`, `texto`, `usuario`, `clave`, `fecha`, `archivo`, `periodo`, `evaluacion`, `idplan`) VALUES
(54, '', 'ArielCamacho@prueba.com', 'AaoepivxOJ', '2023-03-25 01:40:46', '', 'I', '48', 58),
(55, '<p>no aplica</p>\r\n', 'CesarSanchez@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Jenni Rivera.jpg', 'I', '48', 58),
(59, '', 'ElMagallanes@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', '', 'I', '0', 58),
(60, '<p>no aplica</p>\r\n', 'EmilioGarra@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', '60Tarea1EmilioGarra.docx', 'I', '40', 58),
(71, 'verbo tobe', 'EmilioGarra@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Examen', 'I', '33.333333333333', 62),
(80, 'verbo tobe', 'CesarSanchez@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Examen', 'I', '50', 62),
(81, 'verbo tobe', 'ElMagallanes@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Examen', 'I', '50', 62),
(82, 'verbo tobe', 'ArielCamacho@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Examen', 'I', '50', 62),
(112, '<p>No Aplica</p>\r\n', 'jesusortiz@prueba.com', 'AaoepivxOJ', '2023-03-18 22:31:47', '112Jesús Ortiz Paz Tarea1.docx', 'I', '43', 58),
(132, 'verbo tobe', 'jesusortiz@prueba.com', 'AaoepivxOJ', '2023-03-14 21:56:43', 'Examen', 'I', '50', 62),
(158, 'Was Were', 'ArielCamacho@prueba.com', 'AaoepivxOJ', '2023-03-18 23:17:40', 'Examen', 'I', '37.5', 89),
(159, 'Was Were', 'CesarSanchez@prueba.com', 'AaoepivxOJ', '2023-03-18 23:13:20', 'Examen', 'I', '0', 89),
(160, 'Was Were', 'ElMagallanes@prueba.com', 'AaoepivxOJ', '2023-03-18 23:36:34', 'Examen', 'I', '50', 89),
(161, 'Was Were', 'EmilioGarra@prueba.com', 'AaoepivxOJ', '2023-03-18 23:13:20', 'Examen', 'I', '0', 89),
(162, 'Was Were', 'jesusortiz@prueba.com', 'AaoepivxOJ', '2023-03-18 23:13:20', 'Examen', 'I', '0', 89),
(168, '', 'Hassanlaija@prueba.co', 'AaoepivxOJ', '2023-03-23 16:21:06', '', 'I', '0', 58),
(169, 'Was Were', 'Hassanlaija@prueba.co', 'AaoepivxOJ', '2023-03-23 16:21:56', 'Examen', 'I', '50', 89),
(170, 'verbo tobe', 'Hassanlaija@prueba.co', 'AaoepivxOJ', '2023-03-25 19:34:48', 'Examen', 'I', '0', 62),
(171, '', 'ArielCamacho@prueba.com', 'K2KTuwcMoc', '2023-03-26 18:30:15', '', 'I', '0', 91),
(172, '', 'ElMagallanes@prueba.com', 'K2KTuwcMoc', '2023-03-26 18:30:15', '', 'I', '0', 91),
(173, '', 'CesarSanchez@prueba.com', 'K2KTuwcMoc', '2023-03-26 18:30:16', '', 'I', '0', 91),
(174, '', 'jesusortiz@prueba.com', 'K2KTuwcMoc', '2023-03-26 18:30:16', '', 'I', '0', 91),
(175, '', 'Carolinaross@prueba.com', 'AaoepivxOJ', '2023-04-06 23:11:18', '', 'I', '0', 58),
(176, 'Was Were', 'Carolinaross@prueba.com', 'AaoepivxOJ', '2023-04-06 23:11:20', 'Examen', 'I', '0', 89),
(177, 'verbo tobe', 'Carolinaross@prueba.com', 'AaoepivxOJ', '2023-04-06 23:11:27', 'Examen', 'I', '0', 62);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE `temas` (
  `idtema` int(11) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `tema` text NOT NULL,
  `cierre` date DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`idtema`, `clave`, `usuario`, `tema`, `cierre`, `fecha`) VALUES
(2, 'a?cHBtbeSp', 'baironmartin2020@gmail.com', 'Fase 1 - Operaciones Aritméticas', '2023-03-26', '2023-03-09 23:11:00'),
(6, 'K2KTuwcMoc', 'DeanAmbrose@prueba.com', 'Distribuciones De Frecuencia', '2023-03-26', '2023-03-09 23:11:00'),
(7, 'AaoepivxOJ', 'RondaRousey@prueba.com', 'Repaso general de los temas de grado décimo', '2023-03-26', '2023-03-09 23:11:00'),
(8, '5JRSiYZIjM', 'MartinOettel@prueba.com', 'PRESABERES ELECTRÓNICOS', '2023-03-26', '2023-03-09 23:11:00'),
(9, 'l6RoqX9XPA', 'WalterWhite@prueba.com', 'Proceso Químico', '2023-03-26', '2023-03-09 23:11:00'),
(10, '2pczTMwaVu', 'BritaniKnight@prueba.com', 'Izquierda política', '2023-03-26', '2023-03-09 23:11:00'),
(11, 'gPsYLXLZnf', 'JohnCena@prueba.com', 'Deporte específico: “Fútbol sala”', '2023-03-26', '2023-03-09 23:11:00'),
(12, '2wm2sidLJN', 'DavidBautista@prueba.com', 'Origen del Castellano', '2023-03-26', '2023-03-09 23:11:00'),
(13, 'J1PmUuCbq2', 'StephanieMcMahon@prueba.com', 'COMPRENDER LAS RAICES HISTORICAS, EPISTEMICAS, ONTOLOGICAS, AXIOLOGICAS, RELIGIOSAS', '2023-03-26', '2023-03-09 23:11:00'),
(14, 'VctF3XOxBV', 'ReyMysterio@prueba.com', 'CALIGRAFIA ARTISTICA', '2023-03-26', '2023-03-09 23:11:00'),
(16, 'AaoepivxOJ', 'RondaRousey@prueba.com', 'verbo tobe', '2023-03-26', '2023-03-09 23:11:00'),
(27, 'AaoepivxOJ', 'RondaRousey@prueba.com', 'Was Were', '2023-03-26', '2023-03-09 23:21:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Email` varchar(200) NOT NULL,
  `Clave` varchar(200) NOT NULL,
  `Nombre` varchar(200) NOT NULL,
  `cc` int(12) NOT NULL,
  `Foto` varchar(200) NOT NULL,
  `Tipo` varchar(200) NOT NULL,
  `grado` varchar(200) NOT NULL,
  `baner` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Email`, `Clave`, `Nombre`, `cc`, `Foto`, `Tipo`, `grado`, `baner`) VALUES
('AlfredoOlivas@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'José Alfredo Olivas Rojas', 1, '0c7f0cfcf328d927ddae0acedf44d5e1.jpg', 'Estudiante', 'decimo', ''),
('ArielCamacho@prueba.com', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', 'Jose Ariel Camacho  Barraza', 2, 'ariel_camacho_muerte_7.jpg', 'Estudiante', 'undecimo', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRK3u6XRwDta9x3xIEF0DcdK1843eHe7U9pLQ&usqp=CAU'),
('BritaniKnight@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Saraya-Jade Bevis', 4, 'esp.-luchadora-jpg.jpg', 'Docente', 'noaplica', ''),
('CarlosUlisesGomez@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Carlos Ulises Gomez', 5, 'default.jpg', 'Estudiante', 'noveno', ''),
('Carolinaross@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Carolina Ross', 10636345, '267693.jpg', 'Estudiante', 'undecimo', 'https://www.floraqueen.es/blog/wp-content/uploads/sites/4/2017/06/Banner-colores-rosas-min.png'),
('CesarSanchez@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'César Sánchez', 6, '-wacxCdO_400x400.jpg', 'Estudiante', 'undecimo', 'https://i.pinimg.com/originals/12/5f/24/125f240406307e86944af329408638c8.jpg'),
('DavidBautista@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'David Michael Bautista Jr.', 7, '181718_1073911.jpg', 'Docente', 'noaplica', ''),
('DeanAmbrose@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Jonathan David Good ', 8, 'Dean-Ambrose-8.jpg', 'Docente', 'noaplica', 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/blue-mathematics-google-classroom-banner-design-template-64e5d3a7fecfcec23715e41f980c1dab_screen.jpg?ts=1613983242'),
('ElMagallanes@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Oscar Magallanes', 10936475, 'unnamed.jpg', 'Estudiante', 'undecimo', 'https://cdn.shopify.com/s/files/1/0009/0492/1148/files/JimenezBAJO_d8329de2-c0c3-465b-afa3-dc0ab1cc2ab7.jpg?v=1522078507'),
('EmilioGarra@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Emilio Garra', 10, '3611caf92fa36ecdc2203964a301ef2c.png', 'Estudiante', 'undecimo', ''),
('Hassanlaija@prueba.co', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', 'Hassan Kabande Laija', 12345678, 'hasam.jpg', 'Estudiante', 'undecimo', 'https://pbs.twimg.com/media/FBwJ-CpVUAIQEC5.jpg'),
('jesusortiz@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Jesús Ortiz Paz', 10744523, 'Jesus-Ortiz.jpg', 'Estudiante', 'undecimo', ''),
('JohnCena@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'John Felix Anthony Cena, Jr.', 11, '640px-John_Cena_July_2018.jpg', 'Docente', 'noaplica', ''),
('Marcoosuna@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Marco Antonio Osuna Terriquez', 12345, '236153524_234884955303371_3876567502113208338_n.jpg', 'Estudiante', 'octavo', ''),
('MartinOettel@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Martín Oettel', 12, 'images (1).jpg', 'Docente', 'noaplica', ''),
('ReyMysterio@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Óscar Gutiérrez', 13, '6ZOPLQVVTFCSNGNSK6VSZU4BUQ.jpg', 'Docente', 'noaplica', ''),
('RondaRousey@prueba.com', '3627909a29c31381a071ec27f7c9ca97726182aed29a7ddd2e54353322cfb30abb9e3a6df2ac2c20fe23436311d678564d0c8d305930575f60e2d3d048184d79', 'Ronda Jean Rousey', 14, 'peakpx.jpg', 'Docente', 'noaplica', 'https://gamespot.com/a/uploads/original/1179/11799911/2416685-20140102_banner_networklogo.jpg'),
('StephanieMcMahon@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Stephanie McMahon Levesque​​ ', 15, '1200px-Stephanie_McMahon_November_2018.jpg', 'Docente', 'noaplica', ''),
('WalterWhite@prueba.com', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'Walter Hartwell White', 16, 'breaking_bad_vince_gilligan_amc.jpg', 'Docente', 'noaplica', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_p`
--
ALTER TABLE `admin_p`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indices de la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`idarchivos`);

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`idclase`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`idcomentario`);

--
-- Indices de la tabla `concepto`
--
ALTER TABLE `concepto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examen`
--
ALTER TABLE `examen`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `referencias`
--
ALTER TABLE `referencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reuniones`
--
ALTER TABLE `reuniones`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `admin_p`
--
ALTER TABLE `admin_p`
  MODIFY `id_admin` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `archivos`
--
ALTER TABLE `archivos`
  MODIFY `idarchivos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `clase`
--
ALTER TABLE `clase`
  MODIFY `idclase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `idcomentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `concepto`
--
ALTER TABLE `concepto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `examen`
--
ALTER TABLE `examen`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `ID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `misclases`
--
ALTER TABLE `misclases`
  MODIFY `idmiclase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `idplan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `referencias`
--
ALTER TABLE `referencias`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `reuniones`
--
ALTER TABLE `reuniones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `idtarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT de la tabla `temas`
--
ALTER TABLE `temas`
  MODIFY `idtema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
