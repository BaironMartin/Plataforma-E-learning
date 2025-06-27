<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $actividadPlan,
//       $tareaEntregada (array|false), $necesitaCrearEntradaTarea (bool|null)
//       $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';

$idPlan = htmlspecialchars($actividadPlan['idplan']);
$claveClase = htmlspecialchars($claseActual['clave']);
$fechaLimiteEntrega = $actividadPlan['fechaentrega'];
$fechaActual = date("Y-m-d");
$puedeEntregar = ($fechaActual <= $fechaLimiteEntrega);

// Determinar si ya hay una entrega y si esta tiene contenido (archivo o texto)
$hayEntregaConContenido = false;
if ($tareaEntregada && (!empty($tareaEntregada['archivo']) || !empty($tareaEntregada['texto']))) {
    $hayEntregaConContenido = true;
}
$puedeEliminarEntrega = $hayEntregaConContenido && (!$tareaEntregada['calificado'] || $tareaEntregada['evaluacion'] == '');


?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo'];
            $activeLinkForMenu = 'tareas'; // Sigue siendo la sección de tareas
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Tarea</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>
            <?php if (isset($_SESSION['info_message'])): ?><div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['info_message']); unset($_SESSION['info_message']);?></div><?php endif; ?>


            <div class="card mb-4">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($actividadPlan['titulo']); ?></h3>
                </div>
                <div class="card-body">
                    <p><strong>Descripción de la Actividad:</strong></p>
                    <div><?php echo nl2br(htmlspecialchars($actividadPlan['texto'])); ?></div>
                    <p><strong>Fecha Límite de Entrega:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($fechaLimiteEntrega))); ?></p>
                    <?php if (!$puedeEntregar && !$hayEntregaConContenido): ?>
                        <p class="text-danger font-weight-bold">La fecha límite para entregar esta tarea ha pasado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($necesitaCrearEntradaTarea) && $necesitaCrearEntradaTarea === true): ?>
                <div class="alert alert-warning">
                    <p>No tienes una entrada registrada para esta tarea. Esto puede suceder si te uniste a la clase después de que la tarea fue asignada.</p>
                    <form action="index.php?action=ensureStudentTareaEntry" method="POST" style="display:inline;">
                        <input type="hidden" name="id_plan" value="<?php echo $idPlan; ?>">
                        <button type="submit" class="btn btn-info">Registrarme en esta Tarea</button>
                    </form>
                </div>
            <?php elseif ($tareaEntregada): // Si ya existe la entrada en la tabla 'tareas' ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Tu Entrega</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($hayEntregaConContenido): ?>
                            <p><strong>Observaciones Enviadas:</strong></p>
                            <p><?php echo !empty($tareaEntregada['texto']) ? nl2br(htmlspecialchars($tareaEntregada['texto'])) : '<em>No se enviaron observaciones.</em>'; ?></p>

                            <?php if (!empty($tareaEntregada['archivo'])): ?>
                                <p><strong>Archivo Enviado:</strong>
                                    <a href="archivos/archivosTareas/<?php echo htmlspecialchars($tareaEntregada['archivo']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($tareaEntregada['archivo']); ?>
                                    </a>
                                </p>
                            <?php else: ?>
                                <p><em>No se envió ningún archivo.</em></p>
                            <?php endif; ?>

                            <p><strong>Fecha de Entrega/Última Modificación:</strong> <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($tareaEntregada['fecha']))); ?></p>

                            <?php if ($tareaEntregada['calificado'] && $tareaEntregada['evaluacion'] !== ''): ?>
                                <p class="font-weight-bold"><strong>Calificación:</strong> <?php echo htmlspecialchars($tareaEntregada['evaluacion']); ?></p>
                            <?php else: ?>
                                <p><em>Esta tarea aún no ha sido calificada.</em></p>
                            <?php endif; ?>

                            <?php if ($puedeEliminarEntrega && $puedeEntregar): ?>
                                <hr>
                                <p class="text-warning">Si necesitas reemplazar tu entrega, primero elimina la actual y luego podrás subir una nueva versión.</p>
                                <a href="index.php?action=deleteStudentEntrega&id_tarea=<?php echo $tareaEntregada['idtarea']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar tu entrega actual? Podrás subir una nueva si la fecha límite no ha pasado.');">
                                   Eliminar Entrega Actual
                                </a>
                            <?php elseif ($puedeEliminarEntrega && !$puedeEntregar): ?>
                                 <p class="text-info">La fecha límite ha pasado. Ya no puedes modificar tu entrega.</p>
                            <?php endif; ?>

                        <?php endif; // Fin de if $hayEntregaConContenido ?>

                        <?php if (!$hayEntregaConContenido && $puedeEntregar): ?>
                            <p>Aún no has realizado una entrega para esta tarea.</p>
                            <form action="index.php?action=submitEntrega" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_plan" value="<?php echo $idPlan; ?>">
                                <div class="form-group">
                                    <label for="texto_observacion">Observaciones (opcional):</label>
                                    <textarea name="texto_observacion" id="texto_observacion" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="archivo_entrega">Adjuntar Archivo:</label>
                                    <input type="file" name="archivo_entrega" id="archivo_entrega" class="form-control-file">
                                    <small class="form-text text-muted">Si ya subiste un archivo y solo quieres cambiar observaciones, no necesitas adjuntar de nuevo.</small>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Entregar Tarea</button>
                            </form>
                        <?php elseif (!$hayEntregaConContenido && !$puedeEntregar): ?>
                             <p class="text-danger font-weight-bold">No realizaste una entrega para esta tarea y la fecha límite ha pasado.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; // Fin de if $tareaEntregada ?>
        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
