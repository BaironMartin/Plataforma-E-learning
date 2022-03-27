<?php
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['subir']) && !empty($_REQUEST['subir'])) {
    $clave = $_SESSION['clave'];
    $usuario = $_SESSION['user'];
    $namefile = $_FILES['archivo']['name'];
    $tipo = $_FILES['archivo']['type'];
    $tamanio = $_FILES['archivo']['size'];

    $resultado = mysqli_query($cont, "SELECT * FROM archivos WHERE nombre ='" . $namefile . "'");
    if (mysqli_num_rows($resultado) == 1) {

        echo '<script type="text/javascript">
        alert("El Documento ya Existe, Por favor Cambie el Nombre del Documento y Vuelva a Cargarlo ");
        window.location.href="archivos.php";
        </script>';
        
    } else {
        
    mysqli_query($cont, "INSERT INTO archivos VALUE (NULL,'$namefile','$tipo','$tamanio','$clave','$usuario')");
        $sql = "SELECT * FROM archivos WHERE nombre ='" . $namefile . "'";
        $resultad = mysqli_query($cont, $sql);
        $as = mysqli_fetch_assoc($resultad);
        $idar = $as['idarchivos'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], "archivosClases/" . $idar . $namefile);
        header("location:archivos.php");
    }
}
$qarchivos = mysqli_query($cont, "SELECT *FROM archivos WHERE clave='" . $_SESSION['clave'] . "'");
$numArchivos = mysqli_num_rows($qarchivos);
$archivosbase = mysqli_fetch_assoc($qarchivos);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);


if (isset($_REQUEST['e'])) {

    $as = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM archivos WHERE idarchivos='" . $_REQUEST['e'] . "'"));
    $name = 'archivosClases/' . $as['idarchivos'] . $as['nombre'];
    echo $name;
    unlink($name);

    mysqli_query($cont, "DELETE FROM archivos WHERE idarchivos=" . $_REQUEST['e']);
    header("location:archivos.php");
}
$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Guia de Actividades</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>
    <?php
    include('includes/header.php');
    ?>
    <?php
    echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

    ?>
    <hr>

    <h1 style="margin-bottom: -50px;">Guia de Actividades</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <?php
        if ($atipo['Tipo'] == 'Docente') { ?>
            <form action="archivos.php" enctype="multipart/form-data" method="post" autocomplete="off" class="formu" style="margin-top: -50px;">
                <label style="font-size: 25px;color:aliceblue;" for="archivo">Subir Archivo</label>
                <input class="formu-input" type="file" name="archivo" placeholder="" required> <br>
                <input type="hidden" name="subir" value="ok">
                <input class="formu-button" type="submit" value="Subir Archivo">
            </form>
        <?php
        } ?>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Tamaño</th>
                    <?php if ($atipo['Tipo'] == 'Docente') {
                        echo "<th>Eliminar</th>";
                    } ?>
                    <th>Descargar</th>

                </tr>
            </thead>

            <?php
            if ($numArchivos > 0) {
                do {
                    echo "<tr>";
                    echo "<td>" . $archivosbase['idarchivos'] . "</td>";
                    echo "<td>" . $archivosbase['nombre'] . "</td>";
                    echo "<td>" . $archivosbase['tipo'] . "</td>";
                    echo "<td>" . $archivosbase['tamanio'] . "</td>";
                    if ($atipo['Tipo'] == 'Docente') {
                        echo "<td><a href='archivos.php?e=" . $archivosbase['idarchivos'] . "'>Eliminar</a></td>";
                    }
                    echo "<td><a href='archivosClases/" . $archivosbase['idarchivos'] . $archivosbase['nombre'] . "'>Descargas</a></td>";
                    echo "</tr>";
                } while ($archivosbase = mysqli_fetch_assoc($qarchivos));
            } else {
                echo "<tr>";
                echo "<td>No hay archivos</td>";
                echo "</tr>";
            }

            ?>

            </script>
        </table>
    </div>


</body>

</html>