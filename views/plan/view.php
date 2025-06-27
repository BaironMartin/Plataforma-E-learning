<?php
// Variables disponibles:
// $pageTitle (string) - Título de la página
// $claseActual (array) - Datos de la clase actual (nombre, clave, etc.)
// $usuarioActual (array) - Datos del usuario logueado (Email, Nombre, Tipo)
// $actividades (array) - Lista de actividades del plan
// $actividadParaModificar (array|null) - Datos de la actividad a modificar (si aplica para docente)
// $successMessage (string|null) - Mensaje de éxito
// $errorMessage (string|null) - Mensaje de error
// $menu_content (string|null) - Contenido HTML del menú lateral (si se carga desde el controlador)

include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid"> <!-- Usar container-fluid para más espacio si es necesario -->
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            // Incluir el menú lateral dinámico
            // Pasarle las variables necesarias: $claveClaseActual, $usuarioTipoActual, $activeLink
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo'];
            $activeLinkForMenu = 'plan'; // Identificador para esta página
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Plan de Estudio</h1>
            </div>

            <!-- Mensajes de feedback -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <?php if ($usuarioActual['Tipo'] == 'Docente'): ?>
                <div class="teacher-actions mb-4 p-3 border rounded">
                    <h2><?php echo $actividadParaModificar ? 'Modificar Actividad' : 'Agregar Nueva Actividad'; ?></h2>
                    <form action="index.php?action=<?php echo $actividadParaModificar ? 'updateActividad' : 'storeActividad'; ?>" method="post" autocomplete="off" class="formu">

                        <input type="hidden" name="clave_clase" value="<?php echo htmlspecialchars($claseActual['clave']); ?>">
                        <?php if ($actividadParaModificar): ?>
                            <input type="hidden" name="id_plan_modificar" value="<?php echo htmlspecialchars($actividadParaModificar['idplan']); ?>">
                        <?php endif; ?>

                        <div class="form-group mb-2">
                            <label for="titulo">Título:</label>
                            <input class="form-control formu-input" type="text" id="titulo" name="titulo" placeholder="Título de la actividad"
                                   value="<?php echo htmlspecialchars($actividadParaModificar['titulo'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="ckeditor_texto">Descripción/Texto:</label>
                            <textarea id="ckeditor_texto" class="form-control formu-input ckeditor" name="texto" placeholder="Descripción detallada" rows="5" required><?php echo htmlspecialchars($actividadParaModificar['texto'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-2">
                                <label for="fecha_entrega">Fecha de Entrega:</label>
                                <input class="form-control formu-input" type="date" id="fecha_entrega" name="fecha_entrega"
                                       value="<?php echo htmlspecialchars($actividadParaModificar['fechaentrega'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label for="periodo">Periodo:</label>
                                <select class="form-control formu-input" name='periodo' id="periodo" required>
                                    <option value="">Seleccione Periodo</option>
                                    <?php
                                    $periodos = ['I' => 'Primer Periodo', 'II' => 'Segundo Periodo', 'III' => 'Tercer Periodo', 'IV' => 'Cuarto Periodo'];
                                    $periodoSeleccionado = $actividadParaModificar['periodo'] ?? '';
                                    foreach ($periodos as $val => $desc) {
                                        echo "<option value='{$val}' " . ($periodoSeleccionado === $val ? 'selected' : '') . ">{$desc}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary formu-button mt-2">
                            <?php echo $actividadParaModificar ? 'Guardar Cambios' : 'Agregar Actividad'; ?>
                        </button>
                        <?php if ($actividadParaModificar): ?>
                            <a href="index.php?action=viewPlan&clave=<?php echo htmlspecialchars($claseActual['clave']); ?>" class="btn btn-secondary mt-2">Cancelar Edición</a>
                        <?php endif; ?>
                    </form>
                </div>
                <hr>
            <?php endif; // Fin de acciones de Docente ?>


            <h2>Lista de Actividades del Plan</h2>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Periodo</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Entrega</th>
                            <?php if ($usuarioActual['Tipo'] == 'Docente'): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($actividades)): ?>
                            <?php foreach ($actividades as $actividad): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($actividad['titulo']); ?></td>
                                    <td><?php echo substr(strip_tags($actividad['texto']), 0, 100); ?>...</td> <!-- strip_tags para CKEditor -->
                                    <td><?php echo htmlspecialchars($actividad['periodo']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($actividad['fecha']))); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($actividad['fechaentrega']))); ?></td>
                                    <?php if ($usuarioActual['Tipo'] == 'Docente'): ?>
                                        <td>
                                            <a href="index.php?action=viewPlan&clave=<?php echo htmlspecialchars($claseActual['clave']); ?>&ma_idplan=<?php echo $actividad['idplan']; ?>" class="btn btn-sm btn-info">Modificar</a>
                                            <a href="index.php?action=destroyActividad&clave_clase=<?php echo htmlspecialchars($claseActual['clave']); ?>&id_plan=<?php echo $actividad['idplan']; ?>"
                                               class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta actividad y todas sus tareas y exámenes asociados?');">Eliminar</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo ($usuarioActual['Tipo'] == 'Docente' ? '6' : '5'); ?>" class="text-center">No hay actividades en este plan todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- CKEditor necesita ser inicializado. Asumiendo que el script de CKEditor ya está cargado globalmente (ej. en footer.php del layout) -->
<!-- Si no, se debe cargar aquí. -->
<!-- <script src="js/ckeditor/ckeditor.js"></script> -->
<script>
    // Aplicar CKEditor a los textareas con la clase 'ckeditor'
    // Esto es un ejemplo básico, la configuración puede variar.
    document.addEventListener("DOMContentLoaded", function() {
        var textareas = document.querySelectorAll('textarea.ckeditor');
        textareas.forEach(function(textarea) {
            if (CKEDITOR.instances[textarea.id]) {
                CKEDITOR.instances[textarea.id].destroy(true);
            }
            CKEDITOR.replace(textarea.id);
        });
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
