<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $temaActual, $comentarios, $foroAbierto (bool para estudiantes)
//       $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
$claveClase = htmlspecialchars($claseActual['clave']);
$idTema = htmlspecialchars($temaActual['idtema']);
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
                <h1 class="h2">Foro: <?php echo htmlspecialchars($claseActual['nombre']); ?></h1>
                <a href="index.php?action=listTemas&clave=<?php echo $claveClase; ?>" class="btn btn-sm btn-outline-secondary">Volver a Lista de Temas</a>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <div class="tema-principal mb-4 p-3 border rounded bg-white shadow-sm">
                <h3>Tema: <?php echo nl2br(htmlspecialchars($temaActual['tema'])); ?></h3>
                <p class="text-muted">
                    Creado por: <?php echo htmlspecialchars($temaActual['nombre_creador']); ?>
                    el <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($temaActual['fecha']))); ?>.
                    <br>
                    Cierre para comentarios de estudiantes: <?php echo htmlspecialchars(date("d/m/Y", strtotime($temaActual['cierre']))); ?>
                    <?php if (!$foroAbierto && $usuarioActual['Tipo'] === 'Estudiante'): ?>
                        <span class="badge badge-danger ml-2">Este tema está cerrado para nuevos comentarios.</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="comentarios-seccion">
                <h4>Comentarios</h4>
                <?php if ($foroAbierto || $usuarioActual['Tipo'] === 'Docente'): ?>
                    <div class="nuevo-comentario-form mb-4 p-3 border rounded">
                        <h5>Agregar Nuevo Comentario</h5>
                        <form action="index.php?action=storeComentario" method="post" autocomplete="off">
                            <input type="hidden" name="id_tema" value="<?php echo $idTema; ?>">
                            <input type="hidden" name="clave_clase" value="<?php echo $claveClase; ?>">
                            <div class="form-group">
                                <textarea id="ckeditor_comentario" class="form-control formu-input ckeditor" name="texto_comentario" placeholder="Escribe tu comentario aquí..." rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary formu-button mt-2">Enviar Comentario</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($comentarios)): ?>
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <img src="archivos/<?php echo htmlspecialchars($comentario['usuario'] . $comentario['foto_usuario']); ?>"
                                         alt="Foto de <?php echo htmlspecialchars($comentario['nombre_usuario']); ?>"
                                         class="imgg rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?php echo htmlspecialchars($comentario['nombre_usuario']); ?></strong>
                                                <small class="text-muted">
                                                    (<?php echo htmlspecialchars($comentario['tipo_usuario']); ?>)
                                                    - <?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($comentario['fecha']))); ?>
                                                </small>
                                            </div>
                                            <?php if ($usuarioActual['Tipo'] == 'Docente' && $claseActual['usuario'] === $usuarioActual['Email']): // Docente de la clase puede borrar ?>
                                                <a href="index.php?action=destroyComentario&id_comentario=<?php echo $comentario['idcomentario']; ?>&id_tema_redirect=<?php echo $idTema; ?>"
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('¿Estás seguro de eliminar este comentario?');">
                                                   <small>Eliminar</small>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comentario-texto mt-2">
                                            <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay comentarios en este tema todavía. ¡Sé el primero en comentar!</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var textareas = document.querySelectorAll('textarea.ckeditor');
        textareas.forEach(function(textarea) {
            // Prevenir reinicialización si ya existe una instancia (puede causar errores)
            if (CKEDITOR.instances[textarea.id]) {
                // Podrías querer destruirla y recrearla si el contenido cambia dinámicamente sin recarga de página
                // CKEDITOR.instances[textarea.id].destroy(true);
            }
            // Solo inicializar si no existe una instancia para ese ID
            if (!CKEDITOR.instances[textarea.id]) {
                 CKEDITOR.replace(textarea.id);
            }
        });
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
