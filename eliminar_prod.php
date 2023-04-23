<?php
	EliminarProducto($_GET['e']);

	function EliminarProducto($e)
	{
		include('includes/conectar.php');
		
        $sql = ("SELECT* FROM clase WHERE idclase='" . $_REQUEST['e'] . "'");
        $resultadoE = mysqli_query($cont, $sql);
        $nE = mysqli_num_rows($resultadoE);
        $aE = mysqli_fetch_assoc($resultadoE);
        $clave = $aE['clave'];
    
        $sql = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $clave . "'");
        $resultado = mysqli_query($cont, $sql);
        $n = mysqli_num_rows($resultado);
        $alumnos = mysqli_fetch_assoc($resultado);
    
        $sql = ("SELECT* FROM plan WHERE clave='" . $clave . "'");
        $resultado1 = mysqli_query($cont, $sql);
        $n = mysqli_num_rows($resultado1);
        $a = mysqli_fetch_assoc($resultado1);
    
        $sqconcepto = ("SELECT* FROM concepto WHERE clase='" . $clave . "'");
        $resultadoconcepto = mysqli_query($cont, $sqconcepto);
        $nconcepto = mysqli_num_rows($resultadoconcepto);
        $aconcepto = mysqli_fetch_assoc($resultadoconcepto);
    
        $sqtema = ("SELECT* FROM temas WHERE clave='" . $clave . "'");
        $resultadotema = mysqli_query($cont, $sqtema);
        $ntema = mysqli_num_rows($resultadotema);
        $atema = mysqli_fetch_assoc($resultadotema);
    
        $qcomentario = mysqli_query($cont, "SELECT * FROM comentario WHERE idtema ='" . ($atema['idtema']) . "' ");
        $ncomentario = mysqli_num_rows($qcomentario);
        $acomentario = mysqli_fetch_assoc($qcomentario);
    
        $qarchivos = mysqli_query($cont, "SELECT *FROM archivos WHERE clave='" . $clave . "'");
        $numArchivos = mysqli_num_rows($qarchivos);
        $archivosbase = mysqli_fetch_assoc($qarchivos);

        $qtar = mysqli_query($cont, "SELECT *FROM tareas WHERE clave='" . $clave . "'");
        $numtar = mysqli_num_rows($qtar);
        $archtar = mysqli_fetch_assoc($qtar);
    

    

        //Eliminar usuarios
        do {
            $id = intval($alumnos['idmiclase']);
            $sql4 = ("DELETE FROM misclases WHERE idmiclase= $id");
            mysqli_query($cont, $sql4);
        } while ($alumnos = mysqli_fetch_assoc($resultado));
    
        //Eliminar plan
        do {
            $idplan = intval($a['idplan']);
            $sql2 = ("DELETE FROM plan WHERE idplan=" . $idplan);
            mysqli_query($cont, $sql2);
        } while ($a = mysqli_fetch_assoc($resultado1));
    
        //elimiar contenido
        do {
            $idcont = intval($aconcepto['id']);
            mysqli_query($cont, "DELETE FROM concepto WHERE  id=" . $idcont);
        } while ($aconcepto = mysqli_fetch_assoc($resultadoconcepto));
    
        //eliminar comentarios    
        do {
            $idcomen = intval($acomentario['idcomentario']);
            mysqli_query($cont, "DELETE FROM comentario WHERE  idcomentario=" . $idcomen);
        } while ($acomentario = mysqli_fetch_assoc($qcomentario));
    
        //eliminar foro
        do {
            $idforo = intval($atema['idtema']);
            mysqli_query($cont, "DELETE FROM temas WHERE  idtema=" . $idforo);
        } while ($atema = mysqli_fetch_assoc($resultadotema));
    
        //eliminar Guia de actividades
        do {
            $idguia = intval($archivosbase['idarchivos']);
            var_dump($idguia);
    
            $as = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM archivos WHERE idarchivos='" . $idguia . "'"));
            $name = 'archivos/archivosClases/' . $as['idarchivos'] . $as['nombre'];
            unlink($name);
            mysqli_query($cont, "DELETE FROM archivos WHERE idarchivos=" . $idguia);
        } while ($archivosbase = mysqli_fetch_assoc($qarchivos));

        //eliminar tarea
        do {
            $idtar = intval($archtar['idtarea']);
            $sql5 = ("DELETE FROM tareas WHERE idtarea= $idtar");
            mysqli_query($cont, $sql5);
        } while ($archtar = mysqli_fetch_assoc($qtar));
    
        $sql2 = ("DELETE FROM clase WHERE idclase=" . $_REQUEST['e']);
        mysqli_query($cont, $sql2);
        
        echo'<script type="text/javascript">
	alert("Clase Eliminado!!");';

       header("location:crearClase.php");

	}
