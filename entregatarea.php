<?php
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}
if (!isset($_SESSION['clave'])) {
    header("Location: 404.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}


$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));


if (isset($_REQUEST['tarea'])) {
    $_SESSION['tarea'] = $_REQUEST['tarea'];
}

if (isset($_REQUEST['idtarea']) && !empty($_REQUEST['idtarea']) && (isset($_REQUEST['cal']) && !empty($_REQUEST['cal']))) {
    mysqli_query($cont, "UPDATE tareas SET evaluacion='" . $_REQUEST['cal'] . "' WHERE idtarea='" . $_REQUEST['idtarea'] . "'");
    header("location:entregatarea.php");
}

$tarea1 = mysqli_query($cont, "SELECT * FROM plan WHERE idplan ='" . $_SESSION['tarea'] . "'");
$ntarea1 = mysqli_num_rows($tarea1);
$atarea1 = mysqli_fetch_assoc($tarea1);

$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

if (isset($_REQUEST['texto']) && !empty($_REQUEST['texto'])) {
    $clave = $_SESSION['clave'];
    $usuario = $_SESSION['user'];
    $namefile = $_FILES['archivo']['name'];
    $t = $_REQUEST['texto'];
    $id = $_SESSION['tarea'];

    $resultado = mysqli_query($cont, "SELECT * FROM tareas WHERE archivo ='" . $namefile . "'");
    if (mysqli_num_rows($resultado) == 1) {

        echo '<script type="text/javascript">
        alert("El Documento ya Existe, Por favor Cambie el Nombre del Documento y Vuelva a Cargarlo ");
        window.location.href="entregatarea.php";
        </script>';
        
    } else {
        
        mysqli_query($cont, "INSERT INTO tareas VALUE (NULL,'$t','$usuario','$clave',NULL,'$namefile',NULL,'$id')");
        $sql = "SELECT * FROM tareas WHERE archivo ='" . $namefile . "'";
        $resultad = mysqli_query($cont, $sql);
        $as = mysqli_fetch_assoc($resultad);
        $idtar = $as['idtarea'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], "archivosTareas/" . $idtar . $namefile);
        header("location:entregatarea.php");
    }

   
}

$tarea = mysqli_query($cont, "SELECT * FROM tareas WHERE idplan=" . $_SESSION['tarea'] . " AND clave='" . $_SESSION['clave'] . "' AND  usuario='" . $_SESSION['user'] . "'");
$ntarea = mysqli_num_rows($tarea);
$atarea = mysqli_fetch_assoc($tarea);


if (isset($_REQUEST['e'])) {
    
    

    $as = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM tareas WHERE idtarea='" . $_REQUEST['e']. "'"));
    $name='archivosTareas/' . $as['idtarea'] . $as['archivo'];
    unlink($name);
    mysqli_query($cont, "DELETE FROM tareas WHERE  idtarea=" . $_REQUEST['e']);

    header("location:entregatarea.php");
    
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Entrega Tareas</title>
    <link rel="icon" href="img/ico/free bsd.ico">
    <script type="text/javascript" src="js/observacionesbasicas/ckeditor/ckeditor.js"></script>
</head>

<body>
    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar" href="tareas.php">Volver</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <?php
    echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

    ?>
    <hr>

    <h1 style="margin-bottom: -50px;">Entrega de Tareas</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <?php
        if ($atipo['Tipo'] == 'Docente') {
            $sql = ("SELECT * FROM  tareas,usuarios WHERE usuarios.Email=tareas.usuario AND tareas.idplan=" . $_SESSION['tarea'] . " AND tareas.clave='" . $_SESSION['clave'] . "'");
            $resultado = mysqli_query($cont, $sql);
            $at = mysqli_fetch_assoc($resultado);
            $nt = mysqli_num_rows($resultado);
        ?>

            <h1 style="margin-bottom: 20px;"><?php echo $atarea1['titulo'] ?></h1><br>
            <table>

                <?php
                if ($nt > 0) {
                ?>

                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Fecha</th>
                            <th>Descripcion</th>
                            <th>Descarga</th>
                            <th>Evaluacion</th>

                        </tr>
                    </thead>
                    <?php
                    do {
                        echo "<tr>";
                        echo "<td>" . $at['Nombre'] . "</td>";
                        echo "<td>" . $at['fecha'] . "</td>";
                        echo "<td>"  . $at['texto'] . "</td>";
                        echo "<td><a href='archivosTareas/" . $at['idtarea'] . $at['archivo'] . "'>Descargas</a></td>";

                        if ($at['evaluacion'] == "") {
                            echo "<td>";
                            echo "<select class='select' name ='evaluar' onChange='evaluar(\"parent\",this,1)'>";
                            echo "<option value =''>Seleccione Evaluacion</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=50'>50</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=49'>49</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=48'>48</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=47'>47</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=46'>46</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=45'>45</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=44'>44</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=43'>42</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=42'>42</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=41'>41</option>";
                            //40
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=40'>40</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=39'>39</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=38'>38</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=37'>37</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=36'>36</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=35'>35</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=34'>34</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=33'>32</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=32'>32</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=31'>31</option>";
                            //30
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=30'>30</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=29'>29</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=28'>28</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=27'>27</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=26'>26</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=25'>25</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=24'>24</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=23'>22</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=22'>22</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=21'>21</option>";
                            //30
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=20'>20</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=19'>19</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=18'>18</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=17'>17</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=16'>16</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=15'>15</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=14'>14</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=13'>12</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=12'>12</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=11'>11</option>";
                            echo "<option value='entregatarea.php?idtarea=" . $at['idtarea'] . "&cal=10'>10</option>";
                            echo "</select>";
                            echo "</td>";
                        } else {
                            echo "<td style='text-align: center;'><strong>";
                            echo  $at['evaluacion'];
                            echo "</strong></td>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    } while ($at = mysqli_fetch_assoc($resultado));
                    ?>
            </table>
            <script>
                function evaluar(targ, selObj, restore) {
                    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
                    if (restore) selObj.selectedIndex = 0;
                }
            </script>
        <?php
                } else {
                    echo "<thead><tr><th>Tareas</th></tr></thead>";
                    echo "<tr><td>Tareas entregadas 0</td></tr>";
                }
            } else {
        ?>
        <h1 style="margin-bottom: 20px;"><?php echo $atarea1['titulo'] ?></h1><br>
        <?php
                if ($ntarea == 0 && $atipo['Tipo'] == 'Estudiante') { ?>
            <form action="entregatarea.php" enctype="multipart/form-data" method="post" autocomplete="off" class="formu" style="margin-top: -50px;">
                <label style="font-size: 25px;color:aliceblue;" for="archivo">Subir Archivo</label><br>
                <label style="font-size: 18px;color:aliceblue;" for="archivo">Observaciones</label><br>
                <textarea id="ckeditor" class="formu-input ckeditor" name="texto" placeholder="Observaciones" cols="30" rows="10" required></textarea>
                <input class="formu-input" type="file" name="archivo" placeholder="" required> <br>
                <input class="formu-button" type="submit" value="Entregar">
            </form>
        <?php
                } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>texto</th>
                        <?php
                        if ($atarea['evaluacion'] != '') {
                            echo "<th><span>Calificacion </span></th>";
                        }

                        ?>
                        <th>fecha</th>
                        <th>archivo</th>
                        <th>Descargar</th>
                        <?php
                        if ($atarea['evaluacion'] == '') {
                            echo "<th><span>Eliminar</span></th>";
                        }

                        ?>


                    </tr>
                </thead>
            <?php
                    echo "<tr>";
                    echo "<td>" . $atarea['texto'] . "</td>";
                    if ($atarea['evaluacion'] != '') {
                        echo "<th><span>" . $atarea['evaluacion'] . "</span></th>";
                    }
                    echo "<td>" . $atarea['fecha'] . "</td>";
                    echo "<td>"  . $atarea['archivo'] . "</td>";
                    echo "<td><a href='archivosTareas/" . $atarea['idtarea'] . $atarea['archivo']  . "'>Descargas</a></td>";
                    if ($atarea['evaluacion'] == '') {
                        echo "<td><a href='entregatarea.php?e=" . $atarea['idtarea'] . "'>Eliminar</a></td>";
                    }
                    echo "</tr>";
                    echo "<h1 >Tarea Entregada</h1><br>";
                } ?>
            </table>

    </div>
<?php
            }
?>


</body>

</html>