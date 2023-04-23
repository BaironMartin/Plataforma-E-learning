<?php

include('../includes/conectar.php');


session_start();
if (!isset($_SESSION['user_admin'])) {
    header("location:index.php");
} else {
    if ((time() - $_SESSION['time']) > 1800) {
        session_destroy();
        header("location:index.php");
    }
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);


$sql = "SELECT * FROM clase ORDER BY grado ASC";
$resultadoc = mysqli_query($cont, $sql);
$cpountc = mysqli_num_rows($resultadoc);
$ac = mysqli_fetch_assoc($resultadoc);



include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <div class="row">
        
    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix green-text">search</i>
            <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" placeholder="">
            <label for="icon_telephone">Buscar clase</label>
        </div>
    </div>

        <table class="table table-striped table-dark table_id ">
            <thead>
                <tr>
                    <th class="center">Imagen</th>
                    <th class="center">Nombre de la Clase</th>
                    <th class="center">Grado</th>
                    <th class="center">Docente</th>
                </tr>
            </thead>
            <?php
            if ($cpountc > 0) {
                do {
                    $name = $ac['nombre'];
            ?>
                    <tbody>
                        <tr>
                            <?php
                            echo " <td class='center'><img  src=" . $ac['imagen'] . " width='50px' ></td> ";
                            echo " <td class='center'>" . substr($name, 0, 20), "..." . "</td>";
                            echo " <td class='center'>" . $ac['grado'] . "</td>";
                            echo " <td class='center'>" . $ac['usuario'] . "</td>";
                            ?>

                        </tr>
                    </tbody>

            <?php
                } while ($ac = mysqli_fetch_assoc($resultadoc));
                echo "</table>";
            } else {
                echo "No hay clases";
            }

            ?>
    </div>
</div>

<script src="js/buscador.js"></script>
<?php
mysqli_free_result($resultadoc);
mysqli_free_result($resultado);
mysqli_close($cont);
include('includes/footer.php');
?>