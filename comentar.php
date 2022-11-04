<?php
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}
if (!isset($_SESSION['clave'])) {
    header("Location: 404.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['idtema'])) {
    $_SESSION['tema'] = $_REQUEST['idtema'];
}

if (isset($_REQUEST['comentario'])) {
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $id = $_SESSION['tema'];
    $co = $_REQUEST['comentario'];
    mysqli_query($cont, "INSERT INTO comentario VALUES(NULL,'$id','$c','$u','$co',NULL)");
    header("location:comentar.php");
}

$qtema = mysqli_query($cont, "SELECT * FROM temas WHERE idtema ='" . $_SESSION['tema'] . "' ORDER BY  fecha ASC ");
$ntema = mysqli_num_rows($qtema);
$atema = mysqli_fetch_assoc($qtema);

$qcomentario = mysqli_query($cont, "SELECT * FROM comentario,usuarios WHERE comentario.usuario= usuarios.Email AND idtema ='" . $_SESSION['tema'] . "' ORDER BY  fecha ASC ");
$ncomentario = mysqli_num_rows($qcomentario);
$acomentario = mysqli_fetch_assoc($qcomentario);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));


if (isset($_REQUEST['e'])) {
    mysqli_query($cont, "DELETE FROM comentario WHERE  idcomentario=" . $_REQUEST['e']);
    header("location:comentar.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Foro de Discusión</title>
    <link rel="icon" href="img/ico/free bsd.ico">
    <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
    
</head>

<body>
<div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar"  href="foro.php">volver</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <?php
    echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

    ?>
    <hr>

    <h1 style="margin-bottom: -50px;">Foro de Discusión</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">

        <?php echo ("<h1 > "  . $atema['tema'] . "</h1><br>"); ?>
        <form action="comentar.php" method="post" autocomplete="off" class="formu1" style="margin-top: -50px;">
            <textarea id="ckeditor" class="formu-input ckeditor" name="comentario" placeholder="Agregar Nuevo Comentario" cols="30" rows="10" required></textarea>
            <input class="formu-button" type="submit" value="Agregar Comentario">
        </form>
        <?php
        if ($ncomentario > 0) {
            echo "<div class='contenedor'>";
            do {
                echo "<div class='contenedor_interno'>";
                echo "<artricle class='articulo'>";
                echo "<img src ='archivos/" . $acomentario['Email'] . "" . $acomentario['Foto'] . "'class='imgg' > ";
                echo "<p><strong>" . $acomentario['Nombre'] . "</strong></p>";
                if ($acomentario['Tipo'] == 'Docente') {
                    echo "<p style='color: red;'>" . $acomentario['Tipo'] . "</p>";
                } else {
                    echo "<p style='color: green;'>" . $acomentario['Tipo'] . "</p>";
                }
                echo "<br><p>" . $acomentario['comentario'] . "</p><br>";
                echo "<span>Fecha: </span> " . $acomentario['fecha'];
                if ($atipo['Tipo'] == 'Docente') {
                    echo "<a href='comentar.php?e=" . $acomentario['idcomentario'] . "'>Eliminar</a></td>";
                }
                echo "</artricle><br>";
                echo "</div>";
            } while ($acomentario = mysqli_fetch_assoc($qcomentario));
        } else {
            echo "<div class='contenedor_interno'>";
            echo "<artricle>";
            echo "<h3>Sin Comentarios en el Foros de Discusion</h3>";
            echo "</artricle>";
            echo "</div>";
        }
        echo "</div>";

        ?>


        </table>
    </div>
    <p>


</p>
</body>

</html>