<?php
// Vars: $pageTitle, $claseActual, $usuarioActual, $referencias,
//       $referenciaParaModificar (array|null), $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
$claveClase = htmlspecialchars($claseActual['clave']);
$esDocentePropietario = ($usuarioActual['Tipo'] === 'Docente' && $usuarioActual['Email'] === $claseActual['usuario']);
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo'];
            $activeLinkForMenu = 'biblioteca';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Biblioteca de Recursos</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <?php if ($esDocentePropietario): // Solo el docente propietario de la clase puede agregar/editar ?>
                <div class="teacher-actions mb-4 p-3 border rounded">
                    <h2><?php echo $referenciaParaModificar ? 'Modificar Recurso' : 'Agregar Nuevo Recurso'; ?></h2>
                    <form action="index.php?action=<?php echo $referenciaParaModificar ? 'updateReferencia' : 'storeReferencia'; ?>" method="post" autocomplete="off" class="formu">

                        <input type="hidden" name="clave_clase" value="<?php echo $claveClase; ?>">
                        <?php if ($referenciaParaModificar): ?>
                            <input type="hidden" name="id_referencia_modificar" value="<?php echo htmlspecialchars($referenciaParaModificar['id']); ?>">
                            <input type="hidden" name="clave_clase_redirect" value="<?php echo $claveClase; // Para la redirección en update ?>">
                        <?php endif; ?>

                        <div class="form-group mb-2">
                            <label for="titulo_referencia">Título del Recurso:</label>
                            <input class="form-control formu-input" type="text" id="titulo_referencia" name="titulo_referencia"
                                   value="<?php echo htmlspecialchars($referenciaParaModificar['titulo'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="ckeditor_referencia">Contenido/Referencia (puede ser texto, enlaces, HTML embebido):</label>
                            <textarea id="ckeditor_referencia" class="form-control formu-input ckeditor" name="texto_referencia" rows="6" required><?php echo htmlspecialchars($referenciaParaModificar['referencia'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary formu-button mt-2">
                            <?php echo $referenciaParaModificar ? 'Guardar Cambios' : 'Agregar Recurso'; ?>
                        </button>
                        <?php if ($referenciaParaModificar): ?>
                            <a href="index.php?action=viewBiblioteca&clave=<?php echo $claveClase; ?>" class="btn btn-secondary mt-2">Cancelar Edición</a>
                        <?php endif; ?>
                    </form>
                </div>
                <hr>
            <?php endif; ?>

            <h2>Recursos Disponibles</h2>
            <div class="lista-referencias">
                <?php if (!empty($referencias)): ?>
                    <?php foreach ($referencias as $ref): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($ref['titulo']); ?></h5>
                                <small class="text-muted">
                                    Agregado por: <?php echo htmlspecialchars($ref['nombre_creador']); ?>
                                    el <?php echo htmlspecialchars(date("d/m/Y", strtotime($ref['fecha']))); ?>
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="referencia-contenido">
                                    <?php echo nl2br(htmlspecialchars($ref['referencia'])); ?>
                                </div>
                            </div>
                            <?php
                            // El docente que creó la referencia (o el dueño de la clase) puede editar/eliminar.
                            // Aquí, el control de $esDocentePropietario ya verifica si es dueño de la clase.
                            // Si solo el creador de la referencia puede editar/eliminar (incluso si otro docente está viendo):
                            $puedeModificarEstaRef = ($usuarioActual['Tipo'] === 'Docente' && $usuarioActual['Email'] === $ref['usuario']);
                            if ($puedeModificarEstaRef):
                            ?>
                                <div class="card-footer text-right bg-light">
                                    <a href="index.php?action=viewBiblioteca&clave=<?php echo $claveClase; ?>&ma_idreferencia=<?php echo $ref['id']; ?>" class="btn btn-sm btn-info">Modificar</a>
                                    <a href="index.php?action=destroyReferencia&id_referencia=<?php echo $ref['id']; ?>&clave_clase_redirect=<?php echo $claveClase; ?>"
                                       class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este recurso?');">Eliminar</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay recursos o referencias en la biblioteca de esta clase todavía.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var textareas = document.querySelectorAll('textarea.ckeditor');
        textareas.forEach(function(textarea) {
            if (CKEDITOR.instances[textarea.id]) {
                 // CKEDITOR.instances[textarea.id].destroy(true); // Descomentar si se necesita recrear
            }
            if (!CKEDITOR.instances[textarea.id]) { // Solo si no existe ya
                 CKEDITOR.replace(textarea.id);
            }
        });
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
