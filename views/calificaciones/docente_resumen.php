<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $alumnosConPromedios, $successMessage, $errorMessage
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
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Resumen de Calificaciones</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre del Alumno</th>
                            <th>Promedio General en Clase</th>
                            <th>Reporte Individual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumnosConPromedios)): ?>
                            <?php foreach ($alumnosConPromedios as $alumno): ?>
                                <tr>
                                    <td>
                                        <img src="archivos/<?php echo htmlspecialchars($alumno['Email'] . $alumno['Foto']); ?>"
                                             alt="Foto de <?php echo htmlspecialchars($alumno['Nombre']); ?>"
                                             class="imgg" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($alumno['Nombre']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($alumno['promedio']); ?></strong></td>
                                    <td>
                                        <!-- Enlace al reporte individual del alumno. Necesitará su propia acción y controlador de reportes -->
                                        <a href="index.php?action=generarReporteIndividual&clave=<?php echo $claveClase; ?>&userEmail=<?php echo htmlspecialchars($alumno['Email']); ?>"
                                           class="btn btn-info btn-sm" target="_blank">
                                           Ver Reporte
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay alumnos inscritos en esta clase o no tienen calificaciones aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($alumnosConPromedios)): ?>
            <hr>
            <!-- Formulario para generar reporte general de la clase. Necesitará su propia acción. -->
            <form action="index.php?action=generarReporteClase" method="post" target="_blank">
                <input type="hidden" name="clave_clase" value="<?php echo $claveClase; ?>">
                <button type="submit" class="btn btn-success">Generar Reporte PDF de la Clase</button>
            </form>
            <?php endif; ?>

        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
