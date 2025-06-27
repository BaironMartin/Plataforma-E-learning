<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $calificacionesEstudiante, $promedioGeneralEstudiante, $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
$claveClase = htmlspecialchars($claseActual['clave']);
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo'];
            $activeLinkForMenu = 'calificaciones';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Mis Calificaciones</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <h4>Detalle de Calificaciones</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre de la Tarea/Actividad</th>
                            <th>Periodo</th>
                            <th>Calificación</th>
                            <th>Entregado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($calificacionesEstudiante)):
                            $i = 1;
                            foreach ($calificacionesEstudiante as $calif): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($calif['titulo_plan']); ?></td>
                                    <td><?php echo htmlspecialchars($calif['periodo_plan'] ?? $calif['periodo'] /* fallback por si el join no trae periodo_plan */); ?></td>
                                    <td>
                                        <?php
                                        if ($calif['calificado'] && $calif['evaluacion'] !== '') {
                                            echo "<strong>" . htmlspecialchars($calif['evaluacion']) . "</strong>";
                                        } elseif (!empty($calif['archivo']) || !empty($calif['texto'])) {
                                            echo "<em>Entregado, pendiente calificación</em>";
                                        } else {
                                            echo "<em>No entregado / Sin calificar</em>";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo !empty($calif['fecha']) ? htmlspecialchars(date("d/m/Y", strtotime($calif['fecha']))) : 'No'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No tienes calificaciones registradas para esta clase todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($calificacionesEstudiante)): ?>
                <div class="mt-4 p-3 mb-3 bg-light rounded">
                    <h4>Promedio General en la Clase:
                        <strong>
                        <?php
                            if ($promedioGeneralEstudiante !== 'N/A') {
                                echo number_format($promedioGeneralEstudiante, 2);
                            } else {
                                echo "N/A (Sin tareas calificadas)";
                            }
                        ?>
                        </strong>
                    </h4>
                </div>
                <hr>
                <!-- Formulario para generar reporte del estudiante. Necesitará su propia acción. -->
                <form action="index.php?action=generarReporteEstudiante" method="post" target="_blank">
                    <input type="hidden" name="clave_clase" value="<?php echo $claveClase; ?>">
                    <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($usuarioActual['Email']); ?>">
                    <button type="submit" class="btn btn-success">Generar Mi Reporte PDF</button>
                </form>
            <?php endif; ?>

        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
