<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

$sql = ("SELECT* FROM examen WHERE id='" . $_SESSION['idexam'] . "'");
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

$sql2 = ("SELECT* FROM preguntas WHERE idp='" . $_SESSION['idexam'] . "'");
$resultado2 = mysqli_query($cont, $sql2);
$n2 = mysqli_num_rows($resultado2);
$a2 = mysqli_fetch_assoc($resultado2);


$sql = "SELECT id,correcta FROM preguntas";
    $result = mysqli_query($cont, $sql);
    $respuestas_correctas = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $respuestas_correctas[$row['id']] = $row['correcta'];
    }


    // Procesar las respuestas enviadas desde el formulario
    $puntaje = 0;
    $nota = 0;
    $puntos = 0;
    foreach ($_POST as $key => $value) {
        if ($respuestas_correctas[$key] == $value) {
            $puntaje++;
        }
    }
    $puntos = 50 / $n2;
    $nota = $puntaje * $puntos;

    var_dump($nota);


    $c = $_SESSION['clave'];
    $ce = $_SESSION['idexam'];
    $ne = $a['bandera'];

    $u = $_SESSION['user'];

    $sql = ("SELECT* FROM plan WHERE clave='" . $_SESSION['clave'] . "' and bandera='" . $ne . "'");
    $resultado = mysqli_query($cont, $sql);
    $n = mysqli_num_rows($resultado);
    $aq = mysqli_fetch_assoc($resultado);
    $id= ($aq['idplan']);

    $sql = ("SELECT* FROM tareas WHERE usuario='" . $_SESSION['user'] . "' and idplan='" . $id . "'");
    $resultadtar = mysqli_query($cont, $sql);
    $tar = mysqli_num_rows($resultadtar);
    $atar = mysqli_fetch_assoc($resultadtar);
    $idtar=($atar['idtarea']);



    mysqli_query($cont, "UPDATE `tareas` SET evaluacion ='".$nota."'WHERE idtarea='" . $idtar . "'");
    unset($_SESSION['idexam']);
   header("location:examen.php");
