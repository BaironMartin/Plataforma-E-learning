<?php

include('includes/conectar.php');

if (isset($_REQUEST['rec'])) {
    $p1 = $_REQUEST['security_question1'];
    $p2 = $_REQUEST['security_question2'];
    $r1 = $_REQUEST['respuesta1'];
    $r2 = $_REQUEST['respuesta2'];
    $mail = $_REQUEST['Email'];
    $pas1 = $_REQUEST['pass1'];
    $pas2 = $_REQUEST['pass2'];


    $sql = "SELECT * FROM `seguridad` WHERE user ='" . $mail . "'";
    $resultado = mysqli_query($cont, $sql);
    $row = mysqli_num_rows($resultado);
    $array = mysqli_fetch_assoc($resultado);



    if ($row != 0) {
        if (($p1 == $array['p1']) && ($r1 == $array['r1']) && ($p2 == $array['p2']) && ($r2 == $array['r2'])) {

            if ($pas1 == $pas2) {
                $p = hash('sha512', $pas1);
                mysqli_query($cont, "UPDATE usuarios SET Clave = '$p' WHERE Email ='" . $mail . "'");
                header("Location: index.php");
            } else {
                echo '<script language="javascript">alert("La contrasenio no cioncide");</script>';
            }
        } else {
            header("Location:Errors/errorlogin5.php");
        }
    } else {
        header("Location:Errors/errorlogin4.php");
    }
}

include('includes/encabezado.php');



?>

<body>



    <h1>Restaurar Password</h1>

    <h3 style='background:white;color:red;text-align:center;  padding: 10px; margin: 20px;'>⚠️Advertencia⚠️<br>
        Estimado usuario, está iniciando el proceso para recuperar su contraseña<br>
        para esto debe dar respuesta a las preguntas de seguridad y si esas coinciden <br>
        con las ingresadas podrá realizar el cambio de contraseña.<br>
        De lo contrario comuníquese, con soporte

    </h3>"

    <form action="restaurarPasword.php" method="post" class="formu">

        <div class="contenedor-principal">
            <div class="contenedor-izquierdo">
                <label class="text_rec" for="security_question">Pregunta de seguridad:</label>
                <select id="security_question" name="security_question1" required autocomplete="off">
                    <option value="">-- Seleccionar --</option>
                    <option value="1">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                    <option value="2">¿Cuál es el apellido de soltera de tu madre?</option>
                    <option value="3">¿Cuál fue tu ciudad natal?</option>
                    <option value="4">¿Cuál es tu deporte favorito?</option>
                    <option value="5">¿Cuál es tu comida favorita?</option>
                    <option value="6">¿Cuál es tu personaje histórico favorito?</option>
                    <option value="7">¿Cuál es tu libro favorito?</option>
                    <option value="8">¿Cuál es tu canción favorita de la infancia?</option>
                    <option value="9">¿Cuál es tu película favorita de terror?</option>
                    <option value="10">¿Cuál fue el nombre de tu primer/a novio/a?</option>
                </select>

                <input type="text" class="formu-input" name="respuesta1" required>

                <label class="text_rec" for="security_question">Pregunta de seguridad:</label>
                <select id="security_question" name="security_question2" autocomplete="off">
                    <option value="">-- Seleccionar --</option>
                    <option value="11">¿Cuál es el nombre de tu mascota?</option>
                    <option value="12">¿Cuál es tu color favorito?</option>
                    <option value="13">¿Cuál fue tu primer coche?</option>
                    <option value="14">¿Cuál es tu película favorita?</option>
                    <option value="15">¿Cuál es tu canción favorita?</option>
                </select>

                <input type="text" class="formu-input" name="respuesta2" required>
            </div>

            <div class="contenedor-derecho">

                <label class="text_rec" for="email">Ingrese su Email:</label>
                <input type="text" class="formu-input" name="Email" required>

                <label class="text_rec" for="pas1">Ingrese su Nueva Passwoer:</label>
                <input class="formu-input" type="password" name="pass1" id="input1" placeholder="Contraseña">

                <label class="text_rec" for="pas2">Confirme su Password:</label>
                <input class="formu-input" type="password" name="pass2" id="input2" placeholder="Contraseña">
                <div id="countdown" style="background-color:white ;"></div>
            </div>

        </div>
        <input type="submit" name="rec" value="Guardar" class="formu-button"></input>
    </form>



    <script>
        const countdownEl = document.getElementById("countdown");
        const input1 = document.getElementById("input1");
        const input2 = document.getElementById("input2");

        input1.addEventListener("input", function() {
            if (input1.value === input2.value) {
                countdownEl.innerText = ("Los valores son iguales");
                countdownEl.style.color = "green";
            } else {
                countdownEl.innerText = ("Los valores son diferentes");
                countdownEl.style.color = "red";
            }
        });

        input2.addEventListener("input", function() {
            if (input1.value === input2.value) {
                countdownEl.innerText = ("Los valores son iguales");
                countdownEl.style.color = "green";

            } else {
                countdownEl.innerText = ("Los valores son diferentes");
                countdownEl.style.color = "red";
            }
        });
    </script>


</body>

</html>