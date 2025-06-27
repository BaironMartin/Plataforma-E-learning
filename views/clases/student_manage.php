<?php
// $pageTitle, $clasesInscritas, $successMessage, $errorMessage son pasados por ClaseController::listStudentClases()
include __DIR__ . '/../layouts/header.php';
?>

<div class="container"> <!-- Un contenedor general para la página -->
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

    <!-- Mensajes de feedback -->
    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <!-- Formulario para unirse a una clase -->
    <div class="form-container" style="margin-bottom: 30px; padding: 20px; border: 1px solid #ccc;">
        <h2>Unirme a una Nueva Clase</h2>
        <form action="index.php?action=joinClase" method="post" autocomplete="off" class="formu">
            <div style="margin-bottom: 10px;">
                <label for="clave_clase">Clave de la Clase:</label><br>
                <input class="formu-input" type="text" id="clave_clase" name="clave_clase" placeholder="Ingresa la clave única de la clase" required>
            </div>
            <input class="formu-button" type="submit" value="Unirme a Clase">
        </form>
    </div>

    <hr>
    <h2>Mis Clases Inscritas</h2>
    <div class="page-content">
        <?php if (!empty($clasesInscritas)): ?>
            <div class="clases-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($clasesInscritas as $clase): ?>
                    <div class='product-container' style="border: 1px solid #eee; padding: 15px; text-align: center;">
                        <h3><?php echo htmlspecialchars(substr($clase['nombre'], 0, 25)); ?><?php echo strlen($clase['nombre']) > 25 ? '...' : ''; ?></h3>
                        <img src='<?php echo htmlspecialchars($clase['imagen']); ?>' alt="Imagen de la clase" style="width: 100%; height: 150px; object-fit: cover; margin-bottom: 10px;" />
                        <p><strong>Clave:</strong> <?php echo htmlspecialchars($clase['clave']); ?></p>

                        <div class="actions" style="margin-top: 15px;">
                            <a class='editar' href='index.php?action=viewPlan&clave=<?php echo htmlspecialchars($clase['clave']); ?>' style="margin-right: 5px; padding: 8px 12px; background-color: #5cb85c; color: white; text-decoration: none;">Ver Plan</a>
                            <a class='cerrar' href='index.php?action=leaveClase&idmiclase=<?php echo $clase['idmiclase']; ?>' onclick='return confirmarSalida();' style="padding: 8px 12px; background-color: #d9534f; color: white; text-decoration: none;">Salir de Clase</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No estás inscrito en ninguna clase todavía.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmarSalida() {
        return confirm("¿Estás seguro de que deseas salir de esta clase?");
    }
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
