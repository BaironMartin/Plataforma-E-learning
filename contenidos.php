<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['clave'] = $_REQUEST['clave'];
}
if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

if (isset($_REQUEST['titulo'])) {
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $sql = ("INSERT INTO concepto VALUES(NULL,'$t','$tx','$u','$c')");
    mysqli_query($cont, $sql);
    header("location:contenidos.php");
}

$sql = ("SELECT* FROM concepto WHERE clase='" . $_SESSION['clave'] . "'");
$resultado2 = mysqli_query($cont, $sql);
$n2 = mysqli_num_rows($resultado2);
$a2 = mysqli_fetch_assoc($resultado2);


if (isset($_REQUEST['e'])) {
    mysqli_query($cont, "DELETE FROM concepto WHERE  id=" . $_REQUEST['e']);
    header("location:contenidos.php");
}

include('includes/encabezado.php');
?>


<body>

    <?php
    include('includes/header.php');
    ?>


    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");

    if ($as['Tipo'] == 'Docente') {



    ?>
        <h1>Conceptos</h1>

        <form action="contenidos.php" method="post" autocomplete="off" class="formu">

        <input class="formu-input" type="text" name="titulo" placeholder="Titulo" required> <br><br>
            <textarea id="ckeditor" class="formu-input ckeditor" type="text" name="texto" placeholder="Texto" cols='30' rows="10" required></textarea><br>

            <input class="formu-button" type="submit" value="Agregar Concepto">
        </form>
        <hr>
    <?php
    }
    ?>
    <h1 style="margin-bottom: -50px;">Conceptos</h1>


    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <?php
    if ($n2 > 0) {
            echo "<div class='contenedor'>";
            do {
                echo "<div class='contenedor_interno'>";
                echo "<artricle class='articulo'>";
                echo "<p><strong>" . $a2['titulo'] . "</strong></p>";
                
                echo "<br><p style='padding: -10px !important;'>" . $a2['concepto'] . "</p><br>";
                $atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));
                if ($atipo['Tipo'] == 'Docente') {
                    echo "<a href='contenidos.php?e=" . $a2['id'] . "'>Eliminar</a></td>";
                }
                echo "</artricle><br>";
                echo "</div>";
            } while ($a2 = mysqli_fetch_assoc($resultado2));
        } else {
            echo "<div class='contenedor_interno'>";
            echo "<artricle>";
            echo "<h3>Sin Contenido</h3>";
            echo "</artricle>";
            echo "</div>";
        }
        echo "</div>";
        ?>


    </div>
</body>

</html>