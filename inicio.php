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

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Inicio</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>
    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar"  href="Editar.php">Editar</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    <div class="contenedor_flex" style="margin-top: 20px;">
        <div class="left-side">
            <?php
            echo "<img src ='archivos/" . $_SESSION['user'] . "" . $a['Foto'] . "'width=80% > ";
            ?>
        </div>

        <div class=" right-side">
            <?php

            echo "Nombre: " . $a['Nombre'];
            echo "<br>";
            echo "Email: " . $a['Email'];
            echo "<br>";
            echo "Tipo De Usuario: " . $a['Tipo'];
            echo "<br>";
            
            if($a['Tipo']=='Docente'){
                echo "<br><a class='crear'  href='crearClase.php'> Gestionar Clases</a>"; 
            }

            if($a['Tipo']!='Docente')  {
                echo "<br><a class='crear'  href='unirse.php'> Gestionar Clases</a>"; 
            
            }
            ?>

        </div>

    </div>


</body>


</html>