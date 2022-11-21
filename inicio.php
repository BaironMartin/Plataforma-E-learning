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


mysqli_free_result($resultado);
mysqli_close($cont);
include('includes/encabezado.php')
?>


<body>
    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php')?></h2>
        <div class="container2">
            <a class=" editar"  href="Editar.php">Editar</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    <div class="contenedor_flex" style="margin-top: 10px;">
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