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


$query = mysqli_query($cont, ('SELECT * FROM `usuarios` WHERE tipo ="docente"'));
$coun = mysqli_num_rows($query);
$content = mysqli_fetch_assoc($query);


$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['e'])) {
    echo $_REQUEST['e'];
    $sql2 = ("DELETE FROM usuarios WHERE Email= '" . $_REQUEST['e'] . "'");
    mysqli_query($cont, $sql2);
    header("location:docentes_admin.php");
}

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Docentes</h1>
    <br>
    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix green-text">search</i>
            <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" placeholder="">
            <label for="icon_telephone">Buscar docente</label>
        </div>
    </div>
    <div class="row   ">
        <?php
        if ($coun > 0) {
        ?>
            <table class="table table-striped table-dark table_id">
                <thead>
                    <tr class="green">
                        <th class=''>Name</th>
                        <th class=''>Email</th>
                        <th class=''>Eliminar</th>
                        <th class=''>Materias</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    do {
                        echo "<tr>";
                            echo "<td>".$content['Nombre']."</td>";
                            echo "<td>".$content['Email']."</td>";
                            echo"<th ><a class='btn red white-text' href='docentes_admin.php?e=".$content['Email']."'>Eliminar</a></th>";
                            echo"<th ><a class='btn blue white-text' href='docente_clase.php?clave=".$content['Email']."' >Ver Materias</a></th>";
                        echo "</tr>";
                    } while ($content = mysqli_fetch_assoc($query));
                    ?>

                </tbody>
            </table>




        <?php
        } else {
        }

        ?>



    </div>
</div>






<script src="js/buscador.js"></script>
<?php
mysqli_free_result($resultado);
mysqli_free_result($query);
mysqli_close($cont);
include('includes/footer.php');
?>