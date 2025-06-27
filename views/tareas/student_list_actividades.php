<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $actividadesPlan, $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo']; // Asumiendo que $usuarioActual está disponible aquí
            $activeLinkForMenu = 'tareas';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Lista de Tareas</h1>
            </div>

            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título de la Actividad</th>
                            <th>Descripción Breve</th>
                            <th>Fecha de Entrega Límite</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($actividadesPlan)): ?>
                            <?php foreach ($actividadesPlan as $actividad): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($actividad['titulo']); ?></td>
                                    <td><?php echo substr(strip_tags($actividad['texto']), 0, 100); ?>...</td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($actividad['fechaentrega']))); ?></td>
                                    <td>
                                        <a href="index.php?action=viewTarea&id_plan=<?php echo $actividad['idplan']; ?>" class="btn btn-primary btn-sm">
                                            Ver / Entregar Tarea
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay actividades o tareas asignadas para esta clase todavía.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
