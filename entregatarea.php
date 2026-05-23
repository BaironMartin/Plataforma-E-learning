<?php
date_default_timezone_set('America/Bogota');
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

// Obtener tipo de usuario con prepared statement
$stmt_tipo = $cont->prepare("SELECT * FROM usuarios WHERE Email = ?");
$stmt_tipo->bind_param("s", $_SESSION['user']);
$stmt_tipo->execute();
$atipo = mysqli_fetch_assoc($stmt_tipo->get_result());
$stmt_tipo->close();

if (isset($_REQUEST['tarea'])) {
    $_SESSION['tarea'] = intval($_REQUEST['tarea']);
}

if (isset($_REQUEST['idtarea']) && !empty($_REQUEST['idtarea']) && (isset($_REQUEST['cal']) && !empty($_REQUEST['cal']))) {
    // Validar token CSRF y que sea docente
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $idTarea = intval($_REQUEST['idtarea']);
    $calificacion = floatval($_REQUEST['cal']);
    
    $stmt_update = $cont->prepare("UPDATE tareas SET evaluacion = ? WHERE idtarea = ?");
    $stmt_update->bind_param("di", $calificacion, $idTarea);
    $stmt_update->execute();
    $stmt_update->close();
    
    header("location:entregatarea.php");
    exit();
}

// Consulta de tarea agregada
$stmt_agregar = $cont->prepare("SELECT * FROM tareas WHERE usuario = ? AND idplan = ?");
$stmt_agregar->bind_param("si", $_SESSION['user'], $_SESSION['tarea']);
$stmt_agregar->execute();
$agregartarea = $stmt_agregar->get_result();
$ntareaag = mysqli_num_rows($agregartarea);
$atareaag = mysqli_fetch_assoc($agregartarea);
$stmt_agregar->close();

if (isset($_REQUEST['tareaone'])) {
    $stmt_tarea = $cont->prepare("SELECT * FROM plan WHERE idplan = ? AND clave = ?");
    $stmt_tarea->bind_param("is", $_SESSION['tarea'], $_SESSION['clave']);
    $stmt_tarea->execute();
    $tarea12 = $stmt_tarea->get_result();
    $ntarea12 = mysqli_num_rows($tarea12);
    $atarea12 = mysqli_fetch_assoc($tarea12);
    $stmt_tarea->close();

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $idt = $_SESSION['tarea'];
    $p = $atarea12['periodo'];

    $stmt_insert = $cont->prepare("INSERT INTO tareas VALUE (NULL, '', ?, ?, NULL, '', ?, 0, ?)");
    $stmt_insert->bind_param("sssi", $u, $c, $p, $idt);
    $stmt_insert->execute();
    $stmt_insert->close();
    
    header("location:tareas.php");
    exit();
}

// Consulta de plan
$stmt_plan = $cont->prepare("SELECT * FROM plan WHERE idplan = ?");
$stmt_plan->bind_param("i", $_SESSION['tarea']);
$stmt_plan->execute();
$tarea1 = $stmt_plan->get_result();
$ntarea1 = mysqli_num_rows($tarea1);
$atarea1 = mysqli_fetch_assoc($tarea1);
$stmt_plan->close();

// Consulta de clase
$stmt_clase = $cont->prepare("SELECT * FROM clase WHERE clave = ?");
$stmt_clase->bind_param("s", $_SESSION['clave']);
$stmt_clase->execute();
$resultado1 = $stmt_clase->get_result();
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);
$stmt_clase->close();

if (isset($_REQUEST['texto']) && !empty($_REQUEST['texto'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $clave = $_SESSION['clave'];
    $usuario = $_SESSION['user'];
    $t = trim($_REQUEST['texto']);

    // Consultar tarea actual
    $stmt_tarea = $cont->prepare("SELECT * FROM tareas WHERE idplan = ? AND usuario = ?");
    $stmt_tarea->bind_param("is", $_SESSION['tarea'], $usuario);
    $stmt_tarea->execute();
    $tarr = $stmt_tarea->get_result();
    $ntarr = mysqli_num_rows($tarr);
    $atarr = mysqli_fetch_assoc($tarr);
    $stmt_tarea->close();

    $id = $atarr['idtarea'];

    // Validar archivo si se sube
    if ($_FILES['archivo']['name'] != "") {
        $uploadArchivo = validarArchivo($_FILES['archivo'], ['pdf', 'doc', 'docx', 'txt', 'jpg', 'png'], 10485760);
        
        if (!$uploadArchivo['success']) {
            header("Location: error_handler.php?t=login");
            exit();
        }
        
        $namefile = $id . $uploadArchivo['nombre_seguro'];
        
        // Verificar si ya existe
        $stmt_check = $cont->prepare("SELECT idtarea FROM tareas WHERE archivo = ?");
        $stmt_check->bind_param("s", $namefile);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();
        
        if (mysqli_num_rows($resultado) == 1) {
            echo '<script type="text/javascript">
            alert("El Documento ya Existe, Por favor Cambie el Nombre del Documento y Vuelva a Cargarlo ");
            window.location.href="entregatarea.php";
            </script>';
            $stmt_check->close();
        } else {
            $stmt_update_texto = $cont->prepare("UPDATE tareas SET texto = ? WHERE idtarea = ?");
            $stmt_update_texto->bind_param("si", $t, $id);
            $stmt_update_texto->execute();
            $stmt_update_texto->close();
            
            $stmt_update_archivo = $cont->prepare("UPDATE tareas SET archivo = ? WHERE idtarea = ?");
            $stmt_update_archivo->bind_param("si", $namefile, $id);
            $stmt_update_archivo->execute();
            $stmt_update_archivo->close();
            
            move_uploaded_file($_FILES['archivo']['tmp_name'], "archivos/archivosTareas/" . $id . $namefile);
            header("location:entregatarea.php");
            exit();
        }
    }
}

// Consulta de tareas entregadas
$stmt_tareas = $cont->prepare("SELECT * FROM tareas WHERE idplan = ? AND clave = ? AND usuario = ?");
$stmt_tareas->bind_param("iss", $_SESSION['tarea'], $_SESSION['clave'], $_SESSION['user']);
$stmt_tareas->execute();
$tarea = $stmt_tareas->get_result();
$ntarea = mysqli_num_rows($tarea);
$atarea = mysqli_fetch_assoc($tarea);
$stmt_tareas->close();

if (isset($_REQUEST['e'])) {
    // Validar que sea docente
    if ($atipo['Tipo'] == 'Docente') {
        $idEliminar = intval($_REQUEST['e']);
        
        $stmt_file = $cont->prepare("SELECT idtarea, archivo FROM tareas WHERE idtarea = ?");
        $stmt_file->bind_param("i", $idEliminar);
        $stmt_file->execute();
        $as = mysqli_fetch_assoc($stmt_file->get_result());
        $stmt_file->close();
        
        if ($as && $as['archivo'] != '') {
            $name = 'archivos/archivosTareas/' . $as['idtarea'] . $as['archivo'];
            if (file_exists($name)) {
                unlink($name);
            }
            
            $stmt_delete = $cont->prepare("DELETE FROM tareas WHERE idtarea = ?");
            $stmt_delete->bind_param("i", $idEliminar);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
        header("location:entregatarea.php");
        exit();
    }
}

include('includes/encabezado.php')
?>
