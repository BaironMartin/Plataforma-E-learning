<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}


if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['clave'] = $_REQUEST['clave'];
}

$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);


if (isset($_REQUEST['titulo']) && !isset($_REQUEST['modificar'])) {
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $sql = ("INSERT INTO referencias VALUES(NULL,'$t','$tx','$u','$c',NULL)");
    mysqli_query($cont, $sql);
    header("location:biblioteca.php");
}
if (isset($_REQUEST['modificar'])) {

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $sql = ("UPDATE referencias SET titulo = '$t', referencia = '$tx' WHERE id =  " . $_REQUEST['modificar']);
    mysqli_query($cont, $sql);
    header("location:biblioteca.php");
}

if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM referencias WHERE id=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
    header("location:biblioteca.php");
}

$sql = ("SELECT* FROM referencias WHERE clave='" . $_SESSION['clave'] . "' ORDER BY fecha DESC");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);


if (isset($_REQUEST['ma'])) {
    $sql3 = ("SELECT* FROM referencias WHERE id=" . $_REQUEST['ma']);
    $mn = mysqli_query($cont, $sql3);
    $mplan = mysqli_fetch_assoc($mn);
   
}

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultad = mysqli_query($cont, $sql);
$as = mysqli_fetch_assoc($resultad);


$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));

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
    <h1>Biblioteca</h1>
        <form action="biblioteca.php" method="post" autocomplete="off" class="formu">
            <input class="formu-input" type="text" name="titulo" placeholder="Titulo" <?php if (isset($_REQUEST['ma'])) {echo "value='" . $mplan['titulo'] . "'";} ?> required> <br><br>
            <textarea id="ckeditor" class="formu-input ckeditor" type="text" name="texto" placeholder="Texto" cols='30' rows="10" required><?php if (isset($_REQUEST['ma'])) {echo $mplan['referencia'];} ?></textarea><br>
            <?php if (isset($_REQUEST['ma'])) {
                echo "<input type='hidden' name ='modificar' value='" . $_REQUEST['ma'] . "'> ";
            } ?>
            <input class="formu-button" type="submit" <?php if (isset($_REQUEST['ma'])) {echo "value='Guardar'";} else {echo "value='Agregar'";} ?>>
        </form>
    <?php

    }

    ?>


    <hr>
    <h1 style="margin-bottom: -50px">Lista de Referencias</h1>
    

    <div class="menu">
<h1><?php include('includes/menu.php')?></h1>
    </div>
    <div class="tabla">
        <?php
        if ($n> 0) {
            echo "<div class='contenedor'>";
            do {
                echo "<div class='contenedor_interno'>";
                echo "<artricle class='articulo'>";
                
                if ($atipo['Tipo'] == 'Docente') {
                    echo "<br><a class='cerrar' href='biblioteca.php?e=" . $a['id'] . "'>Eliminar</a>";
                    echo "<a class='editar' href='biblioteca.php?ma=" . $a['id'] . "'>Modificar</a><br>";
                }
                echo "<br><p><strong>" . $a['titulo'] . "</strong></p>";
                echo "<p>Fecha: " . $a['fecha']."</p> ";
                ?>
                <div class="refer">
                <?php
                echo "<br><p>" . $a['referencia'] . "</p><br>";
                ?>
                </div>
                <?php
               echo "</artricle><br>";
               echo "</div>";
            } while ($a = mysqli_fetch_assoc($resultado));
        } else {
            echo "<div class='contenedor_interno'>";
            echo "<artricle>";
            echo "<h3>Sin referencias bibliograficas</h3>";
            echo "</artricle>";
            echo "</div>";
        }
        echo "</div>";

        ?>

    </div>
    
    
</body>

</html>

<?php

mysqli_free_result($resultado1);
mysqli_free_result($resultad);
mysqli_free_result($resultado);
mysqli_close($cont);

?>