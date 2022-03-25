<?php 
include('config.php'); 
session_start();
if(isset($_SESSION['logueado']) != "SI"){
header('location: index.php');
exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM mensaje WHERE para='".$_SESSION['usuario']."' and ID='".$id."'";
$res = mysqli_query($cont, $sql) ;
$row = mysqli_fetch_assoc($res);
?>
Menu: <a href="listar.php">Ver mensajes</a> | <a href="crear.php">Crear mensajes</a> | <a href="cerrar.php">Cerrar sesion</a><br /><br />
<strong>De:</strong> <?php echo $row['de']?><br />
<strong>Fecha:</strong> <?php echo $row['fecha']?><br />
<strong>Asunto:</strong> <?php echo $row['asunto']?><br /><br />
<strong>Mensaje:</strong><br />
<?php echo $row['texto']?>

<?php 

if($row['leido'] != "si")
{
	mysqli_query($cont,"UPDATE mensaje SET leido='si' WHERE ID='".$id."'");
}
?>