<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $temas, $successMessage, $errorMessage
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
            $activeLinkForMenu = 'foro';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Foro de Discusión</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <?php if ($usuarioActual['Tipo'] == 'Docente'): ?>
                <div class="teacher-actions mb-4 p-3 border rounded">
                    <h2>Crear Nuevo Tema de Discusión</h2>
                    <form action="index.php?action=storeTema" method="post" autocomplete="off" class="formu">
                        <input type="hidden" name="clave_clase" value="<?php echo $claveClase; ?>">
                        <div class="form-group mb-2">
                            <label for="texto_tema">Tema/Pregunta:</label>
                            <textarea class="form-control formu-input" id="texto_tema" name="texto_tema" placeholder="Escribe el nuevo tema o pregunta para el foro" rows="3" required></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="fecha_cierre">Fecha de Cierre (para comentarios de estudiantes):</label>
                            <input class="form-control formu-input" type="date" id="fecha_cierre" name="fecha_cierre" required>
                        </div>
                        <button type="submit" class="btn btn-primary formu-button mt-2">Agregar Tema</button>
                    </form>
                </div>
                <hr>
            <?php endif; ?>

            <h2>Temas del Foro</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Tema</th>
                            <th>Creado por</th>
                            <th>Fecha Creación</th>
                            <th>Cierre (Estudiantes)</th>
                            <th>Comentarios</th>
                            <th>Acción</th>
                            <?php if ($usuarioActual['Tipo'] == 'Docente'): ?>
                                <th>Eliminar</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($temas)):
                            $i = 1;
                            foreach ($temas as $tema):
                                $foroCerradoParaEstudiante = ($usuarioActual['Tipo'] === 'Estudiante' && date("Y-m-d") > $tema['cierre']);
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($tema['tema'])); ?></td>
                                    <td><?php echo htmlspecialchars($tema['nombre_creador']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($tema['fecha']))); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($tema['cierre']))); ?></td>
                                    <td><?php echo htmlspecialchars($tema['num_comentarios']); ?></td>
                                    <td>
                                        <a href="index.php?action=viewTema&id_tema=<?php echo $tema['idtema']; ?>" class="btn btn-info btn-sm">
                                            Ver / Comentar
                                        </a>
                                        <?php if ($foroCerradoParaEstudiante): ?>
                                            <span class="badge badge-danger ml-2">Cerrado</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($usuarioActual['Tipo'] == 'Docente' && $tema['usuario'] === $usuarioActual['Email']): // Solo el docente creador puede eliminar ?>
                                        <td>
                                            <a href="index.php?action=destroyTema&id_tema=<?php echo $tema['idtema']; ?>&clave_clase=<?php echo $claveClase; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Estás seguro de eliminar este tema y todos sus comentarios?');">
                                               Eliminar
                                            </a>
                                        </td>
                                    <?php elseif($usuarioActual['Tipo'] == 'Docente'): ?>
                                        <td><!-- Espacio para mantener alineación o un ícono de no permitido --></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo ($usuarioActual['Tipo'] == 'Docente' ? '8' : '7'); ?>" class="text-center">No hay temas en este foro todavía.</td>
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
