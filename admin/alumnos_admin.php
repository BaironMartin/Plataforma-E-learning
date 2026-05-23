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

// Actualizar grado con prepared statement y CSRF
if (isset($_REQUEST['gradoup']) && !empty($_REQUEST['gradoup']) && isset($_REQUEST['ide']) && !empty($_REQUEST['ide'])) {
    // Verificar token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token CSRF inválido";
        header("location:alumnos_admin.php");
        exit;
    }
    
    $gradoup = $_REQUEST['gradoup'];
    $email = $_REQUEST['ide'];
    
    $stmt = mysqli_prepare($cont, "UPDATE usuarios SET grado = ? WHERE Email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $gradoup, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("location:alumnos_admin.php");
    exit;
}

// Consulta segura para obtener estudiantes
$stmt = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE tipo = ? ORDER BY grado DESC");
$tipo_estudiante = "Estudiante";
mysqli_stmt_bind_param($stmt, "s", $tipo_estudiante);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
$coun = mysqli_num_rows($query);

$sql = "SELECT * FROM admin_p WHERE email = ?";
$stmt2 = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt2, "s", $_SESSION['user_admin']);
mysqli_stmt_execute($stmt2);
$resultado = mysqli_stmt_get_result($stmt2);
$a = mysqli_fetch_assoc($resultado);

// Eliminar usuario con verificación CSRF
if (isset($_REQUEST['e'])) {
    // Verificar token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token CSRF inválido";
        header("location:alumnos_admin.php");
        exit;
    }
    
    $email_delete = $_REQUEST['e'];
    $stmt3 = mysqli_prepare($cont, "DELETE FROM usuarios WHERE Email = ?");
    mysqli_stmt_bind_param($stmt3, "s", $email_delete);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);
    
    header("location:alumnos_admin.php");
    exit;
}

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Alumnos</h1>

    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix green-text">search</i>
            <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" placeholder="">
            <label for="icon_telephone">Buscar Alumno</label>
        </div>
    </div>


    <br>
    <div class="row   ">
        <?php
        if ($coun > 0) {
        ?>
            <table class="table table-striped table-dark table_id ">
                <thead>
                    <tr class="green">
                        <th>Codigo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Grado</th>
                        <th>Modificar Grado</th>
                        <th>Eliminar</th>
                        <th>Materias</th>
                    </tr >
                </thead>
                <tbody>
                    <?php
                    while ($content = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($content['cc']) . "</td>";
                        echo "<td>" . htmlspecialchars($content['Nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($content['Email']) . "</td>";
                        echo "<td>" . htmlspecialchars($content['grado']) . "</td>";
                        $em = urlencode($content['Email']);
                        
                        // Generar token CSRF para el formulario de actualización
                        $csrf_token = generarTokenCSRF();
                        
                        echo "<td class='text-center text-warning'>";
                        echo "<form method='POST' action='alumnos_admin.php' style='display:inline;'>";
                        echo campoTokenCSRF();
                        echo "<input type='hidden' name='ide' value='" . htmlspecialchars($content['Email']) . "'>";
                        echo "<select name='gradoup' onchange='this.form.submit()'>";
                        echo "<option value='' disabled selected>Modificar Grado</option>";
                        echo "<option value='primero'>primero</option>";
                        echo "<option value='prescolar'>Prescolar</option>";
                        echo "<option value='segundo'>segundo</option>";
                        echo "<option value='tercero'>tercero</option>";
                        echo "<option value='cuarto'>cuarto</option>";
                        echo "<option value='quinto'>quinto</option>";
                        echo "<option value='sexto'>sexto</option>";
                        echo "<option value='septimo'>septimo</option>";
                        echo "<option value='octavo'>octavo</option>";
                        echo "<option value='noveno'>noveno</option>";
                        echo "<option value='decimo'>decimo</option>";
                        echo "<option value='undecimo'>undecimo</option>";
                        echo "</select>";
                        echo "</form>";
                        echo "</td>";
                        
                        // Formulario de eliminación con CSRF
                        echo "<th><form method='POST' action='alumnos_admin.php' style='display:inline;' onsubmit='return confirm(\"¿Está seguro de eliminar este alumno?\");'>";
                        echo campoTokenCSRF();
                        echo "<input type='hidden' name='e' value='" . htmlspecialchars($content['Email']) . "'>";
                        echo "<button type='submit' class='btn red white-text'>Eliminar</button>";
                        echo "</form></th>";
                        
                        echo "<th><a href='alumno_clase.php?clave=" . urlencode($content['Email']) . "' class='btn blue white-text'>Ver Materias</a></th>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php
        } else {
            echo "<p class='center'>No hay alumnos registrados</p>";
        }
        ?>

    </div>
</div>

<script src="js/buscador.js"></script>

<?php
mysqli_free_result($resultado);
mysqli_free_result($query);
mysqli_close($cont);
include('includes/footer.php');
?>
