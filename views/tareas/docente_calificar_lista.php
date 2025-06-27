<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $actividadPlan, $entregas (array de tareas entregadas)
//       $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
$idPlan = htmlspecialchars($actividadPlan['idplan']);
$claveClase = htmlspecialchars($claseActual['clave']);
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo'];
            // Para el docente, la calificación es parte de la gestión de "Tareas" o "Calificaciones"
            // Ajustar $activeLinkForMenu según cómo se organice el flujo del docente.
            // Si esta vista se accede desde un listado de actividades del plan, 'plan' o 'tareas' podría ser activo.
            // Si se accede desde una sección general de "Calificaciones", entonces 'calificaciones'.
            // Por ahora, lo dejaré como 'tareas' ya que se califica una tarea específica.
            $activeLinkForMenu = 'tareas';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Calificar Tarea</h1>
            </div>

             <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">
                    <h3>Actividad: <?php echo htmlspecialchars($actividadPlan['titulo']); ?></h3>
                </div>
                <div class="card-body">
                    <p><strong>Descripción Original:</strong></p>
                    <div><?php echo $actividadPlan['texto']; ?></div>
                    <p><strong>Fecha Límite de Entrega:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($actividadPlan['fechaentrega']))); ?></p>
                </div>
            </div>

            <h4>Entregas de Estudiantes</h4>
            <?php if (!empty($entregas)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Fecha Entrega</th>
                                <th>Observaciones</th>
                                <th>Archivo Adjunto</th>
                                <th>Calificación Actual</th>
                                <th>Acción (Calificar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entregas as $entrega): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrega['nombre_estudiante']); ?></td>
                                    <td><?php echo $entrega['fecha'] ? htmlspecialchars(date("d/m/Y H:i", strtotime($entrega['fecha']))) : 'N/A'; ?></td>
                                    <td><?php echo !empty($entrega['texto']) ? nl2br(htmlspecialchars($entrega['texto'])) : '<em>Ninguna</em>'; ?></td>
                                    <td>
                                        <?php if (!empty($entrega['archivo'])): ?>
                                            <a href="archivos/archivosTareas/<?php echo htmlspecialchars($entrega['archivo']); ?>" target="_blank">
                                                <?php echo htmlspecialchars($entrega['archivo']); ?>
                                            </a>
                                        <?php else: ?>
                                            <em>No adjuntó archivo</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo ($entrega['calificado'] && $entrega['evaluacion'] !== '') ? htmlspecialchars($entrega['evaluacion']) : '<em>Sin calificar</em>'; ?>
                                    </td>
                                    <td>
                                        <form action="index.php?action=processCalificacion" method="POST" class="form-inline">
                                            <input type="hidden" name="id_tarea_calificar" value="<?php echo $entrega['idtarea']; ?>">
                                            <input type="hidden" name="id_plan_redirect" value="<?php echo $idPlan; // Para redirigir de vuelta ?>">
                                            <input type="number" name="calificacion" class="form-control form-control-sm mr-2" style="width: 80px;"
                                                   min="0" max="100" step="0.1" <!-- Ajusta según tu escala de calificación -->
                                                   value="<?php echo htmlspecialchars($entrega['evaluacion'] ?? ''); ?>" required>
                                            <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No hay entregas de estudiantes para esta actividad todavía, o ningún estudiante ha realizado una entrega con contenido.</p>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
