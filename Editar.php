<?php
error_reporting(0);
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}

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
    $u = $_REQUEST['user'];
    $p = $_REQUEST['pass'];
    $p= hash('sha512',$p);
    $n = $_REQUEST['nombre'];
    $t = $_REQUEST['tipo'];
    $f ='';
    $img= $_FILES['photo']['name'];

    if($img=="")
    {
        $f=$_REQUEST['fotoa'];

    }
    else
    {
        $f=$img;
        move_uploaded_file($_FILES['photo']['tmp_name'], "archivos/" . $u . $f);
    }


    $sql = ("UPDATE usuarios SET Clave='$p',Nombre='$n',Foto='$f',Tipo='$t'WHERE Email='".$_SESSION['user']."'"); 
    mysqli_query($cont,$sql);
    header("location:inicio.php");

    mysqli_free_result($resultado);
    mysqli_close($cont);
}
$decr_pw = hash("sha512", $a['Clave']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Editar Usuario</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>

    
    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar"href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    <div class="contenedor_flex">

		<form action="Editar.php" method="post" enctype="multipart/form-data" class="formu">

			<div class="form_seleccion">
				<label class="label1">Email</label>
				<input type="email" class="formu-input"name="user"  value="<?php echo $a['Email'];?>" readonly>
			</div>

			<div class="form_seleccion">
				<label class="label1">Password </label>
                <input type="password" class="formu-input"name="pass" placeholder="Contraseña" required>
			</div>


			<div class="form_seleccion">
				<label class="label1">Nombre </label>
				<input type="text" class="formu-input"name="nombre"value="<?php echo $a['Nombre']; ?>"required>
			</div>

			<div class="form_seleccion">
				<label class="label1">Foto Actual <?php echo $a['Foto'] ;?> </label>
				<input type="file" class="formu-input"name="photo" value="<?php echo $a['Foto'] ;?>">
			</div>

			<input type="hidden"class="formu-input" name="tipo" value="<?php echo $a['Tipo'] ;?>">
            <input type="hidden"class="formu-input" name="fotoa" value="<?php echo $a['Foto'] ;?>">


			<div class="form_seleccion">
				<input type="submit" name="Editar" VALUE="Editar" class="formu-button"></input>
			</div>

		</form>
    

        

</body>

</html>