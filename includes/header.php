<?php
$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultad = mysqli_query($cont, $sql);
$as = mysqli_fetch_assoc($resultad);
?>
<div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php')?></h2>
        <div class="container2">
        <?php
            if ($as['Tipo'] == 'Docente') {
                echo "<a class=' editar' href='crearClase.php'>Gestionar Clase</a>";
            } else 

            ?>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>