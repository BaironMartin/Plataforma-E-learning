<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}



if (isset($_REQUEST['clave'])) {
    $sql2 = ("SELECT * FROM clase WHERE clave='" . $_REQUEST['clave'] . "'");
    $resulado1 = mysqli_query($cont, $sql2);
    $n = mysqli_num_rows($resulado1);
    if ($n > 0) {
        $sql3 = ("SELECT * FROM misclases WHERE clave='" . $_REQUEST['clave'] . "' AND usuario='" . $_SESSION['user'] . "'");
        $resultado2 = mysqli_query($cont, $sql3);
        $nc = mysqli_num_rows($resultado2);

        if ($nc == 0) {
            $u = $_SESSION["user"];
            $c = $_REQUEST['clave'];
            $sql = ("INSERT INTO misclases VALUES(NULL,'$u','$c')");
            mysqli_query($cont, $sql);
            header("location:unirse.php");
        } else {
            echo '<script type="text/javascript">alert("Ya te Uniste a esta clase ");
        window.location.href="unirse.php";
        </script>';
        }
        mysqli_free_result($resultado2);
    } else {
        echo '<script type="text/javascript">alert("La clase no Existe");
        window.location.href="unirse.php";
        </script>';
    }
    mysqli_free_result($resultado1);
}
$sql = ("SELECT clase.nombre,clase.imagen, misclases.idmiclase, clase.clave FROM clase,misclases WHERE clase.clave = misclases.clave and misclases.usuario= '" . $_SESSION['user'] . "'");
$resultado = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);


if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM misclases WHERE idmiclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
}
include('includes/encabezado.php')
?>


<body>

    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php') ?></h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <h1>Unirse a Clases</h1>

    <form action="unirse.php" method="post" autocomplete="off" class="formu">
        <input class="formu-input" type="text" name="clave" placeholder="Clave de Clase" required>
        <input class="formu-button" type="submit" value="Unirse a Clase">
    </form>
    <hr>
    <h1>Mis Clases</h1>
    <div class="containerclasemenu">

        <div class="page-content">


            <?php

            if ($n1 > 0) {
                do {
                    $name = $a['nombre'];
                    echo "<div class='product-container'>";
                    echo "<h3>" . substr($name, 0, 20), '...' . "</h3><br>";
                    echo "<img src='" . $a['imagen'] . "' />";
                    echo "<div class='container2'>";
                    echo "<br>";
                    echo "<button ><a class='cerrar' href='unirse.php?e=" . $a['idmiclase'] . "'>Eliminar</a></button>";
                    echo "<button ><a class='editar' href='plan.php?clave=" . $a['clave'] . "'>Ver Plan</a></button>";
                    echo "</div><br>";
                    echo "</div>";
                } while ($a = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay clases creadas</td></tr>";
            }

            ?>


        </div>
    </div>
    
    <?php
    include('includes/footer copy.php');
    ?>


</body>

</html>
<?php



mysqli_free_result($resultado);
mysqli_close($cont);

?>