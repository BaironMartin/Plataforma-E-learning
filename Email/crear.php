<?php 

include('config.php'); 
session_start();
if(isset($_SESSION['logueado']) != "SI"){
header('location: index.php');
exit();
}
if (isset($_POST['enviar'])) 
{
	if(!empty($_POST['para']) && !empty($_POST['asunto']) && !empty($_POST['texto']))
	{
		$fecha = date("j/m/Y, g:i a");
		$sql = "INSERT INTO mensaje (para,de,fecha,asunto,texto) VALUES ('".$_POST['para']."','".$_SESSION['usuario']."','".$fecha."','".$_POST['asunto']."','".$_POST['texto']."')";
		mysqli_query($cont,$sql);
		echo "Mensaje enviado correctamente.";
	}
}
?>
Menu: <a href="listar.php">Ver mensajes</a> | <a href="crear.php">Crear mensajes</a> | <a href="cerrar.php">Cerrar sesion</a><br /><br />

<form method="post" action="" >
<center><strong>Usuarios para la pruba: juan, luis, lina</strong></center></<br /><br />
Para:<br />
<input type="text" name="para" /><br />
Asunto:<br />
<input type="text" name="asunto" /><br />
Mensaje:<br />
<textarea name="texto"></textarea>
<br /><br />
<input type="submit" name="enviar" value="Enviar" />
</form>