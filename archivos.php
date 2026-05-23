<?php
include('includes/conectar.php');
include('includes/secionesUser.php');
require_once('includes/upload_security.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error_handler.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['subir']) && !empty($_REQUEST['subir'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $clave = $_SESSION['clave'];
    $usuario = $_SESSION['user'];
    
    // Validar archivo subido
    $uploadArchivo = validarArchivo($_FILES['archivo'], ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'], 10485760);
    
    if (!$uploadArchivo['success']) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $namefile = $uploadArchivo['nombre_seguro'];
    $tipo = $uploadArchivo['mime'];
    $tamanio = $_FILES['archivo']['size'];

    // Verificar si ya existe con prepared statement
    $stmt_check = $cont->prepare("SELECT idarchivos FROM archivos WHERE nombre = ?");
    $stmt_check->bind_param("s", $namefile);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();
    
    if (mysqli_num_rows($resultado) == 1) {
        echo '<script type="text/javascript">
        alert("El Documento ya Existe, Por favor Cambie el Nombre del Documento y Vuelva a Cargarlo ");
        window.location.href="archivos.php";
        </script>';
    } else {
        $stmt_insert = $cont->prepare("INSERT INTO archivos VALUE (NULL, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("ssiss", $namefile, $tipo, $tamanio, $clave, $usuario);
        $stmt_insert->execute();
        
        // Obtener ID insertado
        $idar = mysqli_insert_id($cont);
        
        move_uploaded_file($_FILES['archivo']['tmp_name'], "archivos/archivosClases/" . $idar . $namefile);
        header("location:archivos.php");
        
        $stmt_insert->close();
    }
    $stmt_check->close();
}

// Consulta de archivos
$stmt_archivos = $cont->prepare("SELECT * FROM archivos WHERE clave = ?");
$stmt_archivos->bind_param("s", $_SESSION['clave']);
$stmt_archivos->execute();
$qarchivos = $stmt_archivos->get_result();
$numArchivos = mysqli_num_rows($qarchivos);
$archivosbase = mysqli_fetch_assoc($qarchivos);
$stmt_archivos->close();

// Consulta de clase
$stmt_clase = $cont->prepare("SELECT * FROM clase WHERE clave = ?");
$stmt_clase->bind_param("s", $_SESSION['clave']);
$stmt_clase->execute();
$resultado1 = $stmt_clase->get_result();
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);
$stmt_clase->close();

if (isset($_REQUEST['e'])) {
    // Validar que sea docente antes de eliminar
    $stmt_tipo = $cont->prepare("SELECT Tipo FROM usuarios WHERE Email = ?");
    $stmt_tipo->bind_param("s", $_SESSION['user']);
    $stmt_tipo->execute();
    $tipoUsuario = mysqli_fetch_assoc($stmt_tipo->get_result());
    $stmt_tipo->close();
    
    if ($tipoUsuario['Tipo'] == 'Docente') {
        $idEliminar = intval($_REQUEST['e']);
        
        $stmt_file = $cont->prepare("SELECT idarchivos, nombre FROM archivos WHERE idarchivos = ?");
        $stmt_file->bind_param("i", $idEliminar);
        $stmt_file->execute();
        $as = mysqli_fetch_assoc($stmt_file->get_result());
        $stmt_file->close();
        
        if ($as) {
            $name = 'archivos/archivosClases/' . $as['idarchivos'] . $as['nombre'];
            if (file_exists($name)) {
                unlink($name);
            }
            
            $stmt_delete = $cont->prepare("DELETE FROM archivos WHERE idarchivos = ?");
            $stmt_delete->bind_param("i", $idEliminar);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
        header("location:archivos.php");
    }
}

// Obtener tipo de usuario
$stmt_usuario = $cont->prepare("SELECT Tipo FROM usuarios WHERE Email = ?");
$stmt_usuario->bind_param("s", $_SESSION['user']);
$stmt_usuario->execute();
$atipo = mysqli_fetch_assoc($stmt_usuario->get_result());
$stmt_usuario->close();

include('includes/encabezado.php')
?>
