<?php


$servidor="localhost";
$usuario="root";
$contraseña="";
$base ="plataforma" ;
	$cont = mysqli_connect($servidor,$usuario,$contraseña,$base);
	if(!$cont){
	echo "conexión fallida :" . mysqli_connect_error();	
     }

?>