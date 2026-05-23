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

// Consulta segura para obtener docentes
$stmt = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE tipo = ?");
$tipo_docente = "docente";
mysqli_stmt_bind_param($stmt, "s", $tipo_docente);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
$coun = mysqli_num_rows($query);

$sql = "SELECT * FROM admin_p WHERE email = ?";
$stmt2 = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt2, "s", $_SESSION['user_admin']);
mysqli_stmt_execute($stmt2);
$resultado = mysqli_stmt_get_result($stmt2);
$a = mysqli_fetch_assoc($resultado);

// Eliminar docente con verificación CSRF
if (isset($_REQUEST['e'])) {
    // Verificar token CSRF
    if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token CSRF inválido";
        header("location:docentes_admin.php");
        exit;
    }
    
    $email_delete = $_REQUEST['e'];
    $stmt3 = mysqli_prepare($cont, "DELETE FROM usuarios WHERE Email = ?");
    mysqli_stmt_bind_param($stmt3, "s", $email_delete);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);
    
    header("location:docentes_admin.php");
    exit;
}

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Docentes</h1>
    <br>
    <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix green-text">search</i>
            <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" placeholder="">
            <label for="icon_telephone">Buscar docente</label>
        </div>
    </div>
    <div class="row   ">
        <?php
        if ($coun > 0) {
        ?>
            <table class="table table-striped table-dark table_id">
                <thead>
                    <tr class="green">
                        <th class=''>Name</th>
                        <th class=''>Email</th>
                        <th class=''>Eliminar</th>
                        <th class=''>Materias</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($content = mysqli_fetch_assoc($query)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($content['Nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($content['Email']) . "</td>";
                        
                        // Formulario de eliminación con CSRF
                        echo "<th><form method='POST' action='docentes_admin.php' style='display:inline;' onsubmit='return confirm(\"¿Está seguro de eliminar este docente?\");'>";
                        echo campoTokenCSRF();
                        echo "<input type='hidden' name='e' value='" . htmlspecialchars($content['Email']) . "'>";
                        echo "<button type='submit' class='btn red white-text'>Eliminar</button>";
                        echo "</form></th>";
                        
                        echo "<th><a class='btn blue white-text' href='docente_clase.php?clave=" . urlencode($content['Email']) . "' >Ver Materias</a></th>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                </tbody>
            </table>

        <?php
        } else {
            echo "<p class='center'>No hay docentes registrados</p>";
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
