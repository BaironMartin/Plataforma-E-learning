<?php

include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

$varsession = $_SESSION['user'];

if ($varsession == null || $varsession == '') {
    echo 'acceso denegado';
    die();
}


$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['pass']) && !empty($_REQUEST['pass'])) {


    $p = $_REQUEST['pass'];
    $p = hash('sha512', $p);
    if ($p == $a['Clave']) {

        $u = $_REQUEST['user'];
        $n = $_REQUEST['nombre'];
        $t = $_REQUEST['tipo'];
        $bn = $_REQUEST['baner'];
        $grado = $_REQUEST['grado'];
        $cc = $_REQUEST['cc'];
        $f = '';
        $img = $_FILES['photo']['name'];

        if ($img == "") {
            $f = $_REQUEST['fotoa'];
        } else {
            $f = $img;
            move_uploaded_file($_FILES['photo']['tmp_name'], "archivos/" . $u . $f);
        }


        $sql = ("UPDATE usuarios SET Nombre='$n',Foto='$f', cc='$cc',Tipo='$t', baner = '$bn' WHERE Email='" . $_SESSION['user'] . "'");
        mysqli_query($cont, $sql);
        header("location:inicio.php");

        mysqli_free_result($resultado);
        mysqli_close($cont);
    } else {
        header("location:inicio.php");
    }
}
$decr_pw = hash("sha512", $a['Clave']);

$sql = ("SELECT * FROM `seguridad` WHERE user ='". $_SESSION['user']."'");
$res=mysqli_query($cont, $sql);
$nm=mysqli_num_rows($res);


include('includes/encabezado.php')
?>

<body>
    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php') ?></h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    <div class="contenedor_flex">
        <?php
        if($nm != 1){
            ?>
        <a class='crear' style="text-align:center ;" href='seguridad.php'> Crear preguntas de seguridad</a>
        <?php
        }
        ?>

        <form action="Editar.php" method="post" enctype="multipart/form-data" class="formu">
            <BR>

            <div class="form_seleccion">
                <label class="label1">Email</label>
                <input type="email" class="formu-input" name="user" value="<?php echo $a['Email']; ?>" readonly>
            </div>
            <BR>


            <div class="form_seleccion">
                <label class="label1">Nombre </label>
                <input type="text" class="formu-input" name="nombre" value="<?php echo $a['Nombre']; ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1">Codigo </label>
                <input type="text" class="formu-input" name="cc" value="<?php echo $a['cc']; ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1">Foto Actual <?php echo $a['Foto']; ?> </label><BR>
                <?php
                echo "<img src ='archivos/" . $_SESSION['user'] . "" . $a['Foto'] . "'width=30% > ";
                ?>
                <input type="file" class="formu-input" name="photo" value="<?php echo $a['Foto']; ?>">
            </div>

            <input type="hidden" class="formu-input" name="tipo" value="<?php echo $a['Tipo']; ?>">
            <input type="hidden" class="formu-input" name="fotoa" value="<?php echo $a['Foto']; ?>">
            <br>
            <div class="form_seleccion">
                <label class="label1">Banner </label>
                <br>
                <label class="label1">Banner Actual <?php echo $a['Foto']; ?> </label><BR>
                <?php
                echo "<img src ='" . $a['baner'] . "'width=30% > ";
                ?>
                <input type="text" class="formu-input" name="baner" value="<?php echo $a['baner']; ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1" style="color: orange ;">Ingrese su Password </label>
                <input type="password" class="formu-input" name="pass" placeholder="Contraseña" required>
            </div>
            <BR>


            <div class="form_seleccion">
                <input type="submit" name="Editar" VALUE="Editar" class="formu-button"></input>
            </div>



        </form>

    </div>

    <?php

    ?>

</body>

</html>