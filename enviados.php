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
$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$sql = ("SELECT* FROM mensaje WHERE de= '".$_SESSION['user']."' AND clave ='". $_SESSION['clave'] ."'ORDER BY `mensaje`.`fecha` DESC");
$res = mysqli_query($cont, $sql);
$row = mysqli_num_rows($res);
$asoc = mysqli_fetch_assoc($res);

include('includes/encabezado.php')

?>

<body>
  <?php
  include('includes/header.php');


  ?>
  <?php
  echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

  ?>
  <hr>

  <h1 style="margin-bottom: -50px;">Correo Interno</h1>
  <div class="menu">
    <h1><?php include('includes/menu.php') ?></h1>
  </div>
  <div class="tabla">
    <header>
      <input id="btn-menu" type="checkbox">
      <label for="btn-menu"><img src="img/588a64e0d06f6719692a2d10.png" class="imgg"></label>
      <nav class="menuh">
        <ul>
          <li><a href="listar.php">mensajes recibidos </a> </li>
          <li><a href="enviados.php">mensajes enviados</a> </li>
          <li><a href="crear.php">Crear mensajes</a></li>
        </ul>
      </nav>
    </header>
    <br>
    <table >
      <thead>
      <tr>
        <th ><strong>Asunto</strong></th>
        <th ><strong>Para</strong></th>
        <th ><strong>Fecha</strong></th>
      </tr>
      </thead>
      <?php
      $i = 0;
if ($row >0){

      do { ?>
        <tr onclick="window.location.href='leer copy.php?id=<?php echo $asoc['ID'] ?>'">
          <td><a class="ender" href="leer copy.php?id=<?php echo $asoc['ID'] ?>"><?php echo $asoc['asunto'] ?></a></td>
          <td><?php echo $asoc['para'] ?></td>
          <td><?php echo $asoc['fecha'] ?></td>
        </tr>
      <?php $i++;
      }while ($asoc = mysqli_fetch_assoc($res));
    }
    else{
      ?>
      <tr>
          <td colspan="4" >Sin correos enviados</td>
        </tr>
        <?php
    }
      ?>
      
</table>
  </div>
  
</body>

</html>

