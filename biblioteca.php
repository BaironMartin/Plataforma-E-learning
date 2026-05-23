<?php
include('includes/conectar.php');
include('includes/secionesUser.php');
include('includes/csrf.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}


if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['clave'] = $_REQUEST['clave'];
}

// Prepared statement para consultar clase
$stmt_clase = $cont->prepare("SELECT * FROM clase WHERE clave = ?");
$stmt_clase->bind_param("s", $_SESSION['clave']);
$stmt_clase->execute();
$resultado1 = $stmt_clase->get_result();
$n1 = $resultado1->num_rows;
$a1 = $resultado1->fetch_assoc();


if (isset($_POST['titulo']) && !isset($_POST['modificar'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido");
    }
    
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = trim($_POST['titulo']);
    $tx = trim($_POST['texto']);
    
    if (empty($t) || empty($tx)) {
        die("Todos los campos son requeridos");
    }
    
    // Prepared statement para INSERT
    $stmt = $cont->prepare("INSERT INTO referencias VALUES(NULL, ?, ?, ?, ?, NULL)");
    $stmt->bind_param("ssss", $t, $tx, $u, $c);
    $stmt->execute();
    $stmt->close();
    
    header("location:biblioteca.php");
    exit();
}

if (isset($_POST['modificar'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        die("Token CSRF inválido");
    }

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = trim($_POST['titulo']);
    $tx = trim($_POST['texto']);
    $id_mod = intval($_POST['modificar']);
    
    // Prepared statement para UPDATE
    $stmt_update = $cont->prepare("UPDATE referencias SET titulo = ?, referencia = ? WHERE id = ? AND usuario = ? AND clave = ?");
    $stmt_update->bind_param("ssiss", $t, $tx, $id_mod, $u, $c);
    $stmt_update->execute();
    $stmt_update->close();
    
    header("location:biblioteca.php");
    exit();
}

if (isset($_GET['e']) && is_numeric($_GET['e'])) {
    // Validar token CSRF para eliminación
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        die("Token CSRF inválido");
    }
    
    $id_eliminar = intval($_GET['e']);
    
    // Prepared statement para DELETE
    $stmt_delete = $cont->prepare("DELETE FROM referencias WHERE id = ? AND clave = ?");
    $stmt_delete->bind_param("is", $id_eliminar, $_SESSION['clave']);
    $stmt_delete->execute();
    $stmt_delete->close();
    
    header("location:biblioteca.php");
    exit();
}

// Prepared statement para consultar referencias
$stmt_ref = $cont->prepare("SELECT * FROM referencias WHERE clave = ? ORDER BY fecha DESC");
$stmt_ref->bind_param("s", $_SESSION['clave']);
$stmt_ref->execute();
$resultado = $stmt_ref->get_result();
$n = $resultado->num_rows;
$a = $resultado->fetch_assoc();


if (isset($_REQUEST['ma']) && is_numeric($_REQUEST['ma'])) {
    // Prepared statement para obtener referencia específica
    $stmt_ma = $cont->prepare("SELECT * FROM referencias WHERE id = ?");
    $stmt_ma->bind_param("i", $_REQUEST['ma']);
    $stmt_ma->execute();
    $mn = $stmt_ma->get_result();
    $mplan = $mn->fetch_assoc();
   
}

// Prepared statement para usuario
$stmt_user = $cont->prepare("SELECT * FROM usuarios WHERE Email = ?");
$stmt_user->bind_param("s", $_SESSION['user']);
$stmt_user->execute();
$resultad = $stmt_user->get_result();
$as = $resultad->fetch_assoc();


$stmt_tipo = $cont->prepare("SELECT * FROM usuarios WHERE Email = ?");
$stmt_tipo->bind_param("s", $_SESSION['user']);
$stmt_tipo->execute();
$atipo = $stmt_tipo->get_result()->fetch_assoc();

include('includes/encabezado.php');
?>

<body>

<?php
    include('includes/header.php');
    ?>

    
    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");

    if ($as['Tipo'] == 'Docente') {

    ?>
    <h1>Biblioteca</h1>
        <form action="biblioteca.php" method="post" autocomplete="off" class="formu">
            <?php echo campoTokenCSRF(); ?>
            <input class="formu-input" type="text" name="titulo" placeholder="Titulo" <?php if (isset($_REQUEST['ma'])) {echo "value='" . htmlspecialchars($mplan['titulo']) . "'";} ?> required> <br><br>
            <textarea id="ckeditor" class="formu-input ckeditor" type="text" name="texto" placeholder="Texto" cols='30' rows="10" required><?php if (isset($_REQUEST['ma'])) {echo htmlspecialchars($mplan['referencia']);} ?></textarea><br>
            <?php if (isset($_REQUEST['ma'])) {
                echo "<input type='hidden' name ='modificar' value='" . intval($_REQUEST['ma']) . "'> ";
            } ?>
            <input class="formu-button" type="submit" <?php if (isset($_REQUEST['ma'])) {echo "value='Guardar'";} else {echo "value='Agregar'";} ?>>
        </form>
    <?php

    }

    ?>


    <hr>
    <h1 style="margin-bottom: -50px">Lista de Referencias</h1>
    

    <div class="menu">
<h1><?php include('includes/menu.php')?></h1>
    </div>
    <div class="tabla">
        <?php
        if ($n> 0) {
            echo "<div class='contenedor'>";
            do {
                echo "<div class='contenedor_interno'>";
                echo "<artricle class='articulo'>";
                
                if ($atipo['Tipo'] == 'Docente') {
                    $csrf_token = generarTokenCSRF();
                    echo "<br><a class='cerrar' href='biblioteca.php?e=" . intval($a['id']) . "&csrf_token=" . $csrf_token . "' onclick=\"return confirm('¿Está seguro de eliminar?')\">Eliminar</a>";
                    echo "<a class='editar' href='biblioteca.php?ma=" . intval($a['id']) . "'>Modificar</a><br>";
                }
                echo "<br><p><strong>" . htmlspecialchars($a['titulo']) . "</strong></p>";
                echo "<p>Fecha: " . htmlspecialchars($a['fecha'])."</p> ";
                ?>
                <div class="refer">
                <?php
                echo "<br><p>" . htmlspecialchars($a['referencia']) . "</p><br>";
                ?>
                </div>
                <?php
               echo "</artricle><br>";
               echo "</div>";
            } while ($a = $resultado->fetch_assoc());
        } else {
            echo "<div class='contenedor_interno'>";
            echo "<artricle>";
            echo "<h3>Sin referencias bibliograficas</h3>";
            echo "</artricle>";
            echo "</div>";
        }
        echo "</div>";

        ?>

    </div>
    
    
</body>

</html>

<?php

$stmt_clase->close();
if (isset($stmt)) $stmt->close();
if (isset($stmt_update)) $stmt_update->close();
if (isset($stmt_delete)) $stmt_delete->close();
$stmt_ref->close();
if (isset($stmt_ma)) $stmt_ma->close();
$stmt_user->close();
$stmt_tipo->close();
mysqli_close($cont);

?>