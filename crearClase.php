<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}


function generaPass()
{
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 10;
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

if (isset($_REQUEST['clase'])) {
    $name = $_REQUEST['clase'];
    $imagen = $_REQUEST['imagen'];
    $clave = generaPass();
    $u = $_SESSION['user'];
    $gra=$_REQUEST['grado'];
    $sql1 = ("INSERT INTO clase VALUES(NULL,'$name','$clave','$u',NULL,'$imagen','$gra')");
    mysqli_query($cont, $sql1);
    header("location:crearClase.php");
}



$sql = ("SELECT* FROM clase WHERE usuario='" . $_SESSION['user'] . "'");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);

include('includes/encabezado.php')

?>

<script>
    function confirmar() {
        var respuesta = confirm("segurio de eliminar la clase");

        if (respuesta == true) {
            return true;
        } else {
            return false;
        }
    }
</script>

<body>

    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php') ?></h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <h1>Gestionar Clases</h1>

    <form action="crearClase.php" method="post" autocomplete="off" class="formu">
        <input class="formu-input" type="text" name="clase" placeholder="Nombre de Clase" required>
        <input class="formu-input" type="text" name="imagen" placeholder="enlace de la imagen" required>
        <select class="formu-input" name='grado' id="grado">
            <option style="color:white;" value="">grado</option>
            <option value="Prescolar">Prescolar</option>
            <option value="primero">primero</option>
            <option value="segundo">segundo</option>
            <option value="tercero">tercero</option>
            <option value="cuarto">cuarto</option>
            <option value="quinto">quinto</option>
            <option value="sexto">sexto</option>
            <option value="septimo">septimo</option>
            <option value="octavo">octavo</option>
            <option value="noveno">noveno</option>
            <option value="decimo">decimo</option>
            <option value="undecimo">undecimo</option>
            <option value="noaplica">No aplica</option>
        </select>
        <input class="formu-button" type="submit" value="Crear Clase">
    </form>
    <hr>
    <h1>Lista de Clases</h1>
    <div class="page-content">


        <?php

        if ($n > 0) {
            do {

                $name = $a['nombre'];
                echo "<div class='product-container'>";
                echo "<h3>" . substr($name, 0, 20), '...' . "</h3><br>";
                echo "<img src='" . $a['imagen'] . "' />";
                echo "<div class='container2'>";
                echo "<p>Clave Unica: " . $a['clave']  . "</p><br>";
                echo "<p>Grado: " . $a['grado']  . "</p><br>";
                echo "<p>Fecha De Creacion: " . $a['fecha']  . "</p><br>";

                echo "<button onclick= 'return confirmar()' ><a class='cerrar' href='eliminar_prod.php?e=" . $a['idclase'] . "'>Eliminar</a></button>";
                echo "<button ><a class='editar' href='plan.php?clave=" . $a['clave'] . "'>Ver Plan</a></button>";
                echo "</div><br>";
                echo "</div>";
            } while ($a = mysqli_fetch_assoc($resultado));
        } else {
            echo "<tr><td>No hay clases creadas</td></tr>";
        }

        ?>


        </table>
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