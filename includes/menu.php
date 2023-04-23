<?php
$sqlTipo = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultadoTipo = mysqli_query($cont, $sqlTipo);
$aTipo = mysqli_fetch_assoc($resultadoTipo);

$sql = "SELECT * FROM mensaje WHERE para='" . $_SESSION['user'] ."' AND clave ='". $_SESSION['clave'] . "' and leido IS NULL";
$res99 = mysqli_query($cont, $sql);
$totpp = mysqli_num_rows($res99);

echo "<a class=' volver' href='plan.php'>Actividades</a>";
echo "<a class=' volver' href='contenidos.php'>Contenidos</a>";
echo "<a class=' volver' href='participantes.php'>Participantes</a>";
echo "<a class=' volver' href='foro.php'>Foro de Discusión</a>";
echo "<a class=' volver' href='archivos.php'>Guias de Actividades</a>";
echo "<a class=' volver' href='biblioteca.php'>Biblioteca</a>";
echo "<a class=' volver' href='tareas.php'>Evaluacion</a>";
if (($aTipo['Tipo'] == 'Docente')) {
echo "<a class=' volver' href='examen.php'>Examen</a>";
}
echo "<a class=' volver' href='calificaciones.php'>Calificación</a>";
if($totpp >0){
echo "<a class=' volver' href='email.php'>Email &nbsp;<span class='alec' >".$totpp."</span></a>";
}
else{
    echo "<a class=' volver' href='email.php'>Email</a>"; 
}
echo "<a class=' volver' href='reuniones.php'>Reuniones</a>";
