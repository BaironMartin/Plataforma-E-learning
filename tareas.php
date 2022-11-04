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
if (!isset($_SESSION['clave'])) {
    header("Location: 404.php");
}

$qtareas = mysqli_query($cont, "SELECT * FROM plan WHERE clave ='" . $_SESSION['clave']."' ORDER BY  fecha ASC ");
$ntares = mysqli_num_rows($qtareas);
$atareas = mysqli_fetch_assoc($qtareas);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Seguimiento y Evaluacion</title>
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

    <h1 style="margin-bottom: -50px;">Entrega de Actividades</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">

    <table>
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Fecha Entrega</th>
                    <th>Accion</th>
                    
                    
                </tr>
            </thead>
        <?php
        if($ntares>0){
            do{
                echo"<tr>";
                    echo"<td>".$atareas['titulo']."</td>";
                    echo"<td>".$atareas['fechaentrega']."</td>";
                    echo"<td><a href='entregatarea.php?tarea=".$atareas['idplan']."'>Entregar Actividad</a>";
            }
            while($atareas = mysqli_fetch_assoc($qtareas));
        }
        else{

            echo"<tr>";
                    echo"<td>Sin Actividaes Pendientes</td>";
                    echo"</tr>";
        }
            
        ?>

        </script>
        </table>
    </div>

</body>

</html>