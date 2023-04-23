<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['example']) && !empty($_REQUEST['example'])) {
    $_SESSION['idexam'] = $_REQUEST['example'];

}

if (!isset($_SESSION['idexam'])) {
    header("Location: examen.php");
}



$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);


$sql = ("SELECT* FROM examen WHERE id='" . $_SESSION['idexam'] . "'");
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);



if (isset($_REQUEST['correcta'])) {

    $pregunta = $_REQUEST['pregunta'];
    $obciona = $_REQUEST['RespuestaA'];
    $obcionb = $_REQUEST['RespuestaB'];
    $obcionc = $_REQUEST['RespuestaC'];
    $obciond = $_REQUEST['RespuestaD'];
    $corrcta = $_REQUEST['correcta'];
    $idp = $_SESSION['idexam'];


    $sql = "INSERT INTO `preguntas`(`id`, `pregunta`, `opciona`, `opcionb`, `obcionc`, `obciond`, `correcta`, `idp`)
            VALUES (NULL,'$pregunta','$obciona','$obcionb','$obcionc','$obciond','$corrcta','$idp')";
    mysqli_query($cont, $sql);
    header("location:preguntas.php");
}

$sql2 = ("SELECT* FROM preguntas WHERE idp='" . $_SESSION['idexam'] . "'");
$resultado2 = mysqli_query($cont, $sql2);
$n2 = mysqli_num_rows($resultado2);
$a2 = mysqli_fetch_assoc($resultado2);


if (isset($_REQUEST['e'])) {
    mysqli_query($cont, "DELETE FROM preguntas WHERE  id=" . $_REQUEST['e']);
    header("location:preguntas.php");
}
$bandera = $a['bandera'];
if($bandera!="")

$sql = ("SELECT* FROM plan WHERE clave='" . $_SESSION['clave'] . "' and bandera='" . $bandera . "'");
$resultado = mysqli_query($cont, $sql);
$nb = mysqli_num_rows($resultado);
$ab = mysqli_fetch_assoc($resultado);

$idp = $ab['idplan'];


$sql = ("SELECT* FROM tareas WHERE usuario='" . $_SESSION['user'] . "' and idplan='" . $idp . "'");
$resultado = mysqli_query($cont, $sql);
$nt = mysqli_num_rows($resultado);
$at = mysqli_fetch_assoc($resultado);





include('includes/encabezado.php');
?>

<body>

    <?php
    include('includes/header.php');
    ?>


    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");
    echo ("<h1>Examen " . $a['nombre'] . "</h1>");

    if ($as['Tipo'] == 'Docente') {


    ?>


        <form action="preguntas.php" method="post" autocomplete="off" class="formu">
            <input class="formu-input" type="text" name="pregunta" placeholder="pregunta" required>

            <div class="pregunta">
                <input class="UNP" type="text" name="valorA" value="A" readonly>
                <input class="UNP1" type="text" name="RespuestaA" placeholder="Opcion A" required>
            </div>

            <div class="pregunta">
                <input class="UNP" type="text" name="valorB" value="B" readonly>
                <input class="UNP1" type="text" name="RespuestaB" placeholder="Opcion B" required>
            </div>

            <div class="pregunta">
                <input class="UNP" type="text" name="valorC" value="C" readonly>
                <input class="UNP1" type="text" name="RespuestaC" placeholder="Opcion C" required>
            </div>

            <div class="pregunta">
                <input class="UNP" type="text" name="valorD" value="D" readonly>
                <input class="UNP1" type="text" name="RespuestaD" placeholder="Opcion D" required>
            </div>

            <select required  name="correcta">
                <option value="">Seleccione una Opcion</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>

            <input class="formu-button" type="submit" value="Agregar Pregunta">
        </form>
        <hr>
    <?php
    }
    ?>
    <h1 style="margin-bottom: -50px;">preguntas</h1>


    <div class="menu">
        <?php
        if ($as['Tipo'] == 'Docente') { ?>
            <h1><?php include('includes/menu.php') ?></h1>

        <?php
        } else {
            echo "<h3>Menu no disponible</h3>";
        }
        ?>




    </div>
    <div class="tabla">
        <?php

        if ($n2 > 0) {
            if ($as['Tipo'] == 'Docente') {

                echo "<div class='contenedor'>";
                $num = 1;
                do {

                    echo "<div class='contenedor_interno'>";
                    echo "<artricle class='articulo'>";
                    echo "<p><strong>" . $num++ . ". " . $a2['pregunta'] . "</strong></p>";
                    echo "<br><p> A. " . $a2['opciona'] . "</p><br>";
                    echo "<br><p> B. " . $a2['opcionb'] . "</p><br>";
                    echo "<br><p> C. " . $a2['obcionc'] . "</p><br>";
                    echo "<br><p> D. " . $a2['obciond'] . "</p><br>";
                    echo "<a href='preguntas.php?e=" . $a2['id'] . "'>Eliminar</a></td>";
                    echo "</div>";
                } while ($a2 = mysqli_fetch_assoc($resultado2));
            } else {

                if ($nt == 1  && $at['evaluacion'] == 0) {
        ?>
                    <div class="countdown">
                    <div id="countdown"></div>
                    </div>
                    <?php

                    $num = 1;
                    echo "<div  id='obj1' class='contenedor'>";


                    ?>
                    <form action="enviare.php" name="formulario1" id="formulario1" method="POST">
                        <?php

                        do {
                            echo "<div  class='contenedor_interno'>";
                            echo "<artricle class='articulo'>";
                            echo "<br><p> <strong>" . $num++ . ". " . $a2['pregunta'] . "</p><br>";
                            echo "<input type='radio' name='" . $a2['id'] . "' value='A'>" . $a2['opciona'] . "<br>";
                            echo "<input type='radio' name='" . $a2['id'] . "' value='B'>" . $a2['opcionb'] . "<br>";
                            echo "<input type='radio' name='" . $a2['id'] . "' value='C'>" . $a2['obcionc'] . "<br>";
                            echo "<input type='radio' name='" . $a2['id'] . "' value='D'>" . $a2['obciond'] . "<br>";
                            echo "</artricle> <br>";
                            echo "</div>";
                        } while ($a2 = mysqli_fetch_assoc($resultado2));
                        ?>
    </div>

    <input id="stop" class='env' name='env' type="submit" value="enviar">


    </form>
<?php
                } else {
                    echo "<div class='contenedor_interno'>";
                    echo "<artricle>";
                    echo "<h3>Examenes ya resuelto </h3>";
                    echo "</artricle>";
                }
            }
        } else {
            echo "<div class='contenedor_interno'>";
            echo "<artricle>";
            echo "<h3>Examenes actualmente sin preguntas </h3>";
            echo "</artricle>";
            echo "</div>";
        }


        echo "</div>";

?>

</div>
<script>
    // Define el tiempo inicial de la cuenta regresiva (en segundos)
    const initialTime = 15 * 60;

    // Obtener el tiempo restante de localStorage, si existe
    let remainingTime = localStorage.getItem("remainingTime");
    if (!remainingTime) {
        remainingTime = initialTime;
    }

    // Obtener el elemento HTML del contador regresivo
    const countdownEl = document.getElementById("countdown");
    const stopBtn = document.getElementById("stop");

    // Actualizar el tiempo restante cada segundo
    const countdownInterval = setInterval(() => {
        // Restar un segundo al tiempo restante
        remainingTime--;

        // Actualizar el texto del contador regresivo
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        countdownEl.innerText = ` ${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

        // Guardar el tiempo restante en localStorage
        localStorage.setItem("remainingTime", remainingTime);

        // Si se alcanza el final del tiempo, detener el intervalo y mostrar un mensaje
        if (remainingTime <= 0) {
            clearInterval(countdownInterval);
            countdownEl.innerText = "Tiempo terminado";
            document.getElementById('obj1').style.display = 'none';
            var form = document.getElementById('formulario1');
            clearInterval(countdownInterval);

            // Borrar el tiempo restante de localStorage
            localStorage.removeItem("remainingTime");

            // Mostrar un mensaje de que la cuenta regresiva ha sido detenida
            countdownEl.innerText = "Cuenta regresiva detenida";
            form.submit();
        }
    }, 1000);

    stopBtn.addEventListener("click", () => {
        // Detener el intervalo
        clearInterval(countdownInterval);

        // Borrar el tiempo restante de localStorage
        localStorage.removeItem("remainingTime");

        // Mostrar un mensaje de que la cuenta regresiva ha sido detenida
        countdownEl.innerText = "Cuenta regresiva detenida";
    });

    document.addEventListener("contextmenu", function(event) {
        event.preventDefault();
    }, false);

    document.addEventListener("copy", function(event) {
        // Change the copied text if you want
        event.clipboardData.setData("text/plain", "No se permite copiar en esta página web");

        // Prevent the default copy action
        event.preventDefault();
    }, false);
</script>

</body>

</html>