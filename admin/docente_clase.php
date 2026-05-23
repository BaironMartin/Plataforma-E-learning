<?php

include('../includes/conectar.php');
include('../includes/csrf.php');

session_start();
if (!isset($_SESSION['user_admin'])) {
    header("location:index.php");
} else {
    if ((time() - $_SESSION['time']) > 1800) {
        session_destroy();
        header("location:index.php");
    }
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}
if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['user'] = $_REQUEST['clave'];
}

// Prepared statement para consultar clases
$stmt1 = $cont->prepare("SELECT * FROM clase WHERE usuario = ?");
$stmt1->bind_param("s", $_SESSION['user']);
$stmt1->execute();
$resultado1 = $stmt1->get_result();
$n1 = $resultado1->num_rows;
$a1 = $resultado1->fetch_assoc();

function generaPass(){
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 10;
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

if (isset($_REQUEST['clase']) && isset($_REQUEST['imagen']) && isset($_REQUEST['grado'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido");
    }
    
    $name = trim($_POST['clase']);
    $imagen = trim($_POST['imagen']);
    $gra = $_POST['grado'];
    $clave = generaPass();
    $u = $_SESSION['user'];

    // Validaciones básicas
    if (empty($name) || empty($imagen) || empty($gra)) {
        die("Todos los campos son requeridos");
    }
    
    // Prepared statement para INSERT
    $stmt = $cont->prepare("INSERT INTO clase VALUES(NULL, ?, ?, ?, NULL, ?, ?)");
    $stmt->bind_param("ssssss", $name, $clave, $u, $imagen, $gra);
    $stmt->execute();
    $stmt->close();
    
    header("location:docente_clase.php");
    exit();
}


// Prepared statement para consultar usuario
$stmt_query = $cont->prepare("SELECT * FROM `usuarios` WHERE Email = ?");
$stmt_query->bind_param("s", $_SESSION['user']);
$stmt_query->execute();
$query = $stmt_query->get_result();
$coun = $query->num_rows;
$content = $query->fetch_assoc();





if (isset($_REQUEST['e']) && is_numeric($_REQUEST['e'])) {
    // Validar token CSRF para eliminación
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        die("Token CSRF inválido");
    }
    
    $id_clase = intval($_REQUEST['e']);
    
    // Prepared statement para DELETE
    $stmt_delete = $cont->prepare("DELETE FROM clase WHERE idclase = ? AND usuario = ?");
    $stmt_delete->bind_param("is", $id_clase, $_SESSION['user']);
    $stmt_delete->execute();
    $stmt_delete->close();
    
    header("location:docente_clase.php");
    exit();
}

// Prepared statement para admin
$stmt_admin = $cont->prepare("SELECT * FROM admin_p WHERE email = ?");
$stmt_admin->bind_param("s", $_SESSION['user_admin']);
$stmt_admin->execute();
$resultado = $stmt_admin->get_result();
$a = $resultado->fetch_assoc();

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Clases del Docente</h1>
    <hr>
    <h5 class="green-text center">Crear clase al docente <?php echo htmlspecialchars($content['Nombre']); ?> </h5>

    <form class="col s12" action="docente_clase.php" method="post" autocomplete="off">
        <?php echo campoTokenCSRF(); ?>

        <div class="row">
            <div class="input-field col s12">
                <input name="clase" id="first_name2" type="text" class="validate" required>
                <label class="active" for="first_name2">Nombre de la Clase</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input name="imagen" id="first_name2" type="url" class="validate" required>
                <label class="active" for="first_name2">Enlace de la Imagen</label>
            </div>
        </div>

        <select class="formu-input" name='grado' id="grado" required>
        <option value="" disabled selected>Choose your option</option>
            <option value="Prescolar">Prescolar</option>
            <option value="primero">primero</option>
            <option value="segundo">segundo</option>
            <option value="tercero">tercero</option>
            <option value="cuarto">cuarto</option>
            <option value="quinto">quinto</option>
            <option value="sexto">sexto</option>
            <option value="septimo">septimo</option>
            <option value="octavo">octavo</option>
            <option value="noveno">noveno</option>
            <option value="decimo">decimo</option>
            <option value="undecimo">undecimo</option>
            <option value="noaplica">No aplica</option>
        </select>



        <div class=" center-align">
            <button class="btn waves-effect waves-light green" type="submit" name="action">Crear Clase
                <i class="material-icons right">send</i>
            </button>
        </div>
    </form>


    <hr><br><br><br><br>

    <div class="row">
        <?php
        if ($n1 > 0) {
            do {
                $name = $a1['nombre'];
        ?>


                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-image">
                            <?php
                            echo "<img class='z-depth-5' src='" . htmlspecialchars($a1['imagen']) . "'>";

                            echo "<br><br><br><br><br><span class='card-title black-text'><strong>" . htmlspecialchars(substr($name, 0, 20)) . "...</strong> </span>";
                            ?>
                        </div>
                        <div class="card-content">
                            <p><strong class="red-text">Clave: </strong><?php echo htmlspecialchars($a1['clave']); ?> </p>
                            <p><strong class="green-text">Grado: </strong><?php echo htmlspecialchars($a1['grado']); ?> </p>
                        </div>
                        <div class="card-action">
                            <?php 
                            $csrf_token = generarTokenCSRF();
                            echo "<a href='docente_clase.php?e=" . intval($a1['idclase']) . "&csrf_token=" . $csrf_token . "' onclick=\"return confirm('¿Está seguro de eliminar esta clase?')\">Eliminar</a>";
                            ?>
                        </div>
                    </div>
                </div>

        <?php
            } while ($a1 = mysqli_fetch_assoc($resultado1));
        } else {
            echo "No hay clases";
        }

        ?>
    </div>
</div>






<script src="js/buscador.js"></script>

<?php
$stmt1->close();
$stmt_query->close();
$stmt_admin->close();
mysqli_close($cont);
include('includes/footer.php');
?>