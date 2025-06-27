<?php
// $pageTitle, $clases, $successMessage, $errorMessage son pasados por ClaseController::manageClases()
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

    <!-- Formulario para crear nueva clase -->
    <div class="form-container" style="margin-bottom: 30px; padding: 20px; border: 1px solid #ccc;">
        <h2>Crear Nueva Clase</h2>
        <form action="index.php?action=storeClase" method="post" autocomplete="off" class="formu">
            <div style="margin-bottom: 10px;">
                <label for="clase_nombre">Nombre de Clase:</label><br>
                <input class="formu-input" type="text" id="clase_nombre" name="clase_nombre" placeholder="Ej: Matemáticas Avanzadas" required>
            </div>
            <div style="margin-bottom: 10px;">
                <label for="clase_imagen">Enlace de la Imagen de Portada:</label><br>
                <input class="formu-input" type="text" id="clase_imagen" name="clase_imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
            </div>
            <div style="margin-bottom: 10px;">
                <label for="clase_grado">Grado:</label><br>
                <select class="formu-input" name='clase_grado' id="clase_grado" required>
                    <option value="">Seleccione un grado</option>
                    <option value="Prescolar">Prescolar</option>
                    <option value="primero">Primero</option>
                    <option value="segundo">Segundo</option>
                    <option value="tercero">Tercero</option>
                    <option value="cuarto">Cuarto</option>
                    <option value="quinto">Quinto</option>
                    <option value="sexto">Sexto</option>
                    <option value="septimo">Séptimo</option>
                    <option value="octavo">Octavo</option>
                    <option value="noveno">Noveno</option>
                    <option value="decimo">Décimo</option>
                    <option value="undecimo">Undécimo</option>
                    <!-- <option value="noaplica">No aplica</option> --> <!-- Docentes siempre aplican un grado a la clase -->
                </select>
            </div>
            <input class="formu-button" type="submit" value="Crear Clase">
        </form>
    </div>

    <hr>
    <h2>Mis Clases Creadas</h2>
    <div class="page-content">
        <?php if (!empty($clases)): ?>
            <div class="clases-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($clases as $clase): ?>
                    <div class='product-container' style="border: 1px solid #eee; padding: 15px; text-align: center;">
                        <h3><?php echo htmlspecialchars(substr($clase['nombre'], 0, 25)); ?><?php echo strlen($clase['nombre']) > 25 ? '...' : ''; ?></h3>
                        <img src='<?php echo htmlspecialchars($clase['imagen']); ?>' alt="Imagen de la clase" style="width: 100%; height: 150px; object-fit: cover; margin-bottom: 10px;" />
                        <p><strong>Clave:</strong> <?php echo htmlspecialchars($clase['clave']); ?></p>
                        <p><strong>Grado:</strong> <?php echo htmlspecialchars($clase['grado']); ?></p>
                        <p><strong>Fecha Creación:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($clase['fecha']))); ?></p>

                        <div class="actions" style="margin-top: 15px;">
                            <!-- El enlace a "Ver Plan" necesitará su propia acción y controlador -->
                            <a class='editar' href='index.php?action=viewPlan&clave=<?php echo htmlspecialchars($clase['clave']); ?>' style="margin-right: 5px; padding: 8px 12px; background-color: #5cb85c; color: white; text-decoration: none;">Ver Plan</a>
                            <a class='cerrar' href='index.php?action=deleteClase&id=<?php echo $clase['idclase']; ?>' onclick='return confirmarEliminacion();' style="padding: 8px 12px; background-color: #d9534f; color: white; text-decoration: none;">Eliminar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No has creado ninguna clase todavía.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmarEliminacion() {
        return confirm("¿Estás seguro de que deseas eliminar esta clase? Esta acción no se puede deshacer y podría afectar a los planes de estudio y tareas asociadas.");
    }
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
