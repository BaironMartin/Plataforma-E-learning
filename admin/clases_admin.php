<?php

include('../includes/conectar.php');
include('../includes/csrf.php');

session_start();

// Configuración de sesión segura
if (!isset($_SESSION['user_admin'])) {
    header("location:index.php");
    exit;
} else {
    if ((time() - $_SESSION['time']) > 1800) {
        session_destroy();
        header("location:index.php");
        exit;
    }
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
    exit;
}

// Consulta segura para admin_p
$sql = "SELECT * FROM admin_p WHERE email = ?";
$stmt = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_admin']);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$a = mysqli_fetch_assoc($resultado);

// Consulta segura para clases
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
                while ($ac = mysqli_fetch_assoc($resultadoc)) {
                    $name = $ac['nombre'];
            ?>
                    <tbody>
                        <tr>
                            <?php
                            echo " <td class='center'><img src='" . htmlspecialchars($ac['imagen']) . "' width='50px' alt='Imagen clase'></td> ";
                            echo " <td class='center'>" . htmlspecialchars(substr($name, 0, 20)) . "...</td>";
                            echo " <td class='center'>" . htmlspecialchars($ac['grado']) . "</td>";
                            echo " <td class='center'>" . htmlspecialchars($ac['usuario']) . "</td>";
                            ?>

                        </tr>
                    </tbody>

            <?php
                }
                echo "</table>";
            } else {
                echo "<p class='center'>No hay clases</p>";
            }
            ?>
        </div>
    </div>
</div>

<script src="js/buscador.js"></script>

<?php
mysqli_free_result($resultado);
mysqli_free_result($resultadoc);
mysqli_close($cont);
include('includes/footer.php');
?>
