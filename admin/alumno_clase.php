<?php

include('../includes/conectar.php');
include('../includes/csrf.php');

session_start();

// Configuración de sesión segura
if (!isset($_SESSION['user_admin'])) {
    header("location:index.php");
    exit;
} else {
    if ((time() - $_SESSION['time']) > 1800) {
        session_destroy();
        header("location:index.php");
        exit;
    }
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
    exit;
}

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['user'] = $_REQUEST['clave'];
}

// Consulta segura para clases del alumno
$sql = "SELECT clase.nombre, clase.imagen, misclases.idmiclase, clase.clave, clase.grado 
        FROM clase, misclases 
        WHERE clase.clave = misclases.clave AND misclases.usuario = ?";
$stmt1 = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt1, "s", $_SESSION['user']);
mysqli_stmt_execute($stmt1);
$resultado1 = mysqli_stmt_get_result($stmt1);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

// Consulta segura para datos del usuario
$query = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE Email = ?");
mysqli_stmt_bind_param($query, "s", $_SESSION['user']);
mysqli_stmt_execute($query);
$result_query = mysqli_stmt_get_result($query);
$content = mysqli_fetch_assoc($result_query);
$grado = $content['grado'] ?? '';

// Consulta segura para clases por grado
$sql = "SELECT * FROM clase WHERE grado = ?";
$stmt_grado = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt_grado, "s", $grado);
mysqli_stmt_execute($stmt_grado);
$resultadoc = mysqli_stmt_get_result($stmt_grado);
$cpountc = mysqli_num_rows($resultadoc);
$ac = mysqli_fetch_assoc($resultadoc);

// Unir a clase con CSRF
if (isset($_REQUEST['clases1'])) {
    // Verificar token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token CSRF inválido";
        header("location:alumno_clase.php");
        exit;
    }
    
    $clave_clase = $_REQUEST['clases1'];
    
    $sql2 = "SELECT * FROM clase WHERE clave = ?";
    $stmt2 = mysqli_prepare($cont, $sql2);
    mysqli_stmt_bind_param($stmt2, "s", $clave_clase);
    mysqli_stmt_execute($stmt2);
   $resulado1 = mysqli_stmt_get_result($stmt2);
    $n = mysqli_num_rows($resulado1);
    
    if ($n > 0) {
        $sql3 = "SELECT * FROM misclases WHERE clave = ? AND usuario = ?";
        $stmt3 = mysqli_prepare($cont, $sql3);
        mysqli_stmt_bind_param($stmt3, "ss", $clave_clase, $_SESSION['user']);
        mysqli_stmt_execute($stmt3);
        $resultado2 = mysqli_stmt_get_result($stmt3);
        $nc = mysqli_num_rows($resultado2);

        if ($nc == 0) {
            $u = $_SESSION["user"];
            $c = $_REQUEST['clases1'];
            $sql_insert = "INSERT INTO misclases VALUES(NULL, ?, ?)";
            $stmt_insert = mysqli_prepare($cont, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "ss", $u, $c);
            mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);
            
            header("location:alumno_clase.php");
            exit;
        } else {
            echo '<script type="text/javascript">alert("Ya te Uniste a esta clase");window.location.href="alumnos_admin.php";</script>';
        }
        mysqli_free_result($resultado2);
    } else {
        echo '<script type="text/javascript">alert("La clase no Existe");window.location.href="alumnos_admin.php";</script>';
    }
    mysqli_free_result($resulado1);
}

// Eliminar clase con CSRF
if (isset($_REQUEST['e'])) {
    // Verificar token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token CSRF inválido";
        header("location:alumno_clase.php");
        exit;
    }
    
    $id_delete = $_REQUEST['e'];
    $sql2 = "DELETE FROM misclases WHERE idmiclase = ?";
    $stmt_delete = mysqli_prepare($cont, $sql2);
    mysqli_stmt_bind_param($stmt_delete, "i", $id_delete);
    mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);
    
    header("location:alumno_clase.php");
    exit;
}

// Consulta segura para admin_p
$sql = "SELECT * FROM admin_p WHERE email = ?";
$stmt_admin = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt_admin, "s", $_SESSION['user_admin']);
mysqli_stmt_execute($stmt_admin);
$resultado = mysqli_stmt_get_result($stmt_admin);
$a = mysqli_fetch_assoc($resultado);

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Clases del Alumno</h1>
    <hr>
    <h5 class="green-text center">registrar al alumno <?php echo htmlspecialchars($content['Nombre']); ?> a clase</h5>

    <form class="col s12" action="alumno_clase.php" method="post">
        <?php echo campoTokenCSRF(); ?>
        <div class="input-field col s12">
            <select name="clases1">
                <option value="" disabled selected>Choose your option</option>
                <?php
                if ($resultadoc) {
                    mysqli_data_seek($resultadoc, 0);
                    while ($ac = mysqli_fetch_assoc($resultadoc)) {
                        echo "<option value='" . htmlspecialchars($ac['clave']) . "'>" . htmlspecialchars($ac['nombre']) . "</option>";
                    }
                }
                ?>
            </select>
            <label>Clases</label>

            <div class="center-align">
                <button class="btn waves-effect waves-light green" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </div>
    </form>

    <hr>

    <div class="row">
        <?php
        if ($n1 > 0 && $resultado1) {
            mysqli_data_seek($resultado1, 0);
            while ($a1 = mysqli_fetch_assoc($resultado1)) {
                $name = $a1['nombre'];
        ?>
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-image">
                            <?php
                            echo "<img class='z-depth-5' src='" . htmlspecialchars($a1['imagen']) . "' alt='Imagen clase'>";
                            echo "<br><br><br><br><br><span class='card-title black-text'><strong>" . htmlspecialchars(substr($name, 0, 20)) . "...</strong> </span>";
                            ?>
                        </div>
                        <div class="card-content">
                            <p><strong class="red-text">clave: </strong><?php echo htmlspecialchars($a1['clave']); ?> </p>
                            <p><strong class="green-text">Grado: </strong><?php echo htmlspecialchars($a1['grado']); ?> </p>
                        </div>
                        <div class="card-action">
                            <form method="POST" action="alumno_clase.php" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar esta clase?');">
                                <?php echo campoTokenCSRF(); ?>
                                <input type="hidden" name="e" value="<?php echo htmlspecialchars($a1['idmiclase']); ?>">
                                <button type="submit" class="btn red white-text">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='center'>No hay clases</p>";
        }
        ?>
    </div>
</div>

<script src="js/buscador.js"></script>

<?php
mysqli_free_result($resultado1);
mysqli_free_result($resultadoc);
mysqli_free_result($result_query);
mysqli_close($cont);
include('includes/footer.php');
?>
