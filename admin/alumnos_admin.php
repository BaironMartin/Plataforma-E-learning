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

if (isset($_REQUEST['gradoup']) && !empty($_REQUEST['gradoup']) && (isset($_REQUEST['ide']) && !empty($_REQUEST['ide']) )) {
    mysqli_query($cont, "UPDATE `usuarios` SET `grado`='".$_REQUEST['gradoup']."' WHERE`Email`='".$_REQUEST['ide']."'");
    header("location:alumnos_admin.php");
}

$query = mysqli_query($cont, ('SELECT * FROM `usuarios` WHERE tipo ="Estudiante" order by grado desc'));
$coun = mysqli_num_rows($query);
$content = mysqli_fetch_assoc($query);


$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['e'])) {
    echo $_REQUEST['e'];
    $sql2 = ("DELETE FROM usuarios WHERE Email= '" . $_REQUEST['e'] . "'");
    mysqli_query($cont, $sql2);
    header("location:alumnos_admin.php");
}

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Alumnos</h1>

    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix green-text">search</i>
            <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" placeholder="">
            <label for="icon_telephone">Buscar Alumno</label>
        </div>
    </div>


    <br>
    <div class="row   ">
        <?php
        if ($coun > 0) {
        ?>
            <table class="table table-striped table-dark table_id ">
                <thead>
                    <tr class="green">
                        <th>Codigo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Grado</th>
                        <th>Modificar Grado</th>
                        <th>Eliminar</th>
                        <th>Materias</th>
                    </tr >
                </thead>
                <tbody>
                    <?php
                    do {
                        echo "<tr>";
                        echo "<td>" . $content['cc'] . "</td>";
                        echo "<td>" . $content['Nombre'] . "</td>";
                        echo "<td>" . $content['Email'] . "</td>";
                        echo "<td>" . $content['grado'] . "</td>";
                        $em=$content['Email'] ;
                        echo "<td class='text-center text-warning'>" ;
                        echo "<select class='' name ='grado' onChange='grado(\"parent\",this,1)'>";
                        echo "<option value='' disabled selected>Modificar Grado</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=primero '>primero</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=prescolar '>Prescolar</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=segundo '>segundo</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=tercero '>tercero</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=cuarto '>cuarto</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=quinto '>quinto</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=sexto '>sexto</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=septimo '>septimo</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=octavo '>octavo</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=noveno '>noveno</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=decimo '>decimo</option>";
                        echo "<option value='alumnos_admin.php?ide=".$em."&gradoup=undecimo '>undecimo</option>";
                        echo "</select>";

                        echo "</td>" ;
                        echo "<th ><a class='btn red white-text' href='alumnos_admin.php?e=".$content['Email']."'>Eliminar</a></th>";
                        echo "<th ><a  href='alumno_clase.php?clave=".$content['Email']."' class='btn blue white-text'>Ver Materias</a></th>";
                        echo "</tr>";
                    } while ($content = mysqli_fetch_assoc($query));
                    ?>
                    <script>
                        function grado(targ, selObj, restore) {
                    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
                    if (restore) selObj.selectedIndex = 0;
                }
                    </script>

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