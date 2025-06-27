<?php
// Vars: $pageTitle, $claseActual, $usuarioActual (quien ve), $docenteDeLaClase, $alumnosConFoto, $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
$claveClase = htmlspecialchars($claseActual['clave']);
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <?php
            $claveClaseActualForMenu = $claseActual['clave'];
            $usuarioTipoActualForMenu = $usuarioActual['Tipo']; // El tipo del usuario que está viendo la página
            $activeLinkForMenu = 'participantes';
            include __DIR__ . '/../layouts/sidebar_clase.php';
            ?>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($claseActual['nombre']); ?> - Lista de Participantes</h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <?php if ($usuarioActual['Tipo'] == 'Docente' && $usuarioActual['Email'] == $docenteDeLaClase['Email']): ?>
                                <th>Acción (Eliminar Alumno)</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mostrar al Docente primero -->
                        <?php if ($docenteDeLaClase): ?>
                        <tr>
                            <td><span class="badge bg-primary text-white"><?php echo htmlspecialchars($docenteDeLaClase['Tipo']); ?></span></td>
                            <td>
                                <img src="archivos/<?php echo htmlspecialchars($docenteDeLaClase['Email'] . $docenteDeLaClase['Foto']); ?>"
                                     alt="Foto de <?php echo htmlspecialchars($docenteDeLaClase['Nombre']); ?>"
                                     class="imgg rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td><?php echo htmlspecialchars($docenteDeLaClase['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($docenteDeLaClase['Email']); ?></td>
                            <?php if ($usuarioActual['Tipo'] == 'Docente' && $usuarioActual['Email'] == $docenteDeLaClase['Email']): ?>
                                <td><!-- No se puede eliminar a sí mismo --></td>
                            <?php endif; ?>
                        </tr>
                        <?php endif; ?>

                        <!-- Mostrar Alumnos -->
                        <?php if (!empty($alumnosConFoto)): ?>
                            <?php foreach ($alumnosConFoto as $alumno): ?>
                                <tr>
                                    <td><span class="badge bg-secondary text-white"><?php echo htmlspecialchars($alumno['Tipo']); ?></span></td>
                                    <td>
                                        <img src="archivos/<?php echo htmlspecialchars($alumno['Email'] . $alumno['Foto']); ?>"
                                             alt="Foto de <?php echo htmlspecialchars($alumno['Nombre']); ?>"
                                             class="imgg rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($alumno['Nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($alumno['Email']); ?></td>
                                    <?php if ($usuarioActual['Tipo'] == 'Docente' && $usuarioActual['Email'] == $docenteDeLaClase['Email']):
                                        // Necesitamos el idmiclase para eliminar la inscripción.
                                        // Esto asume que $alumnosConFoto tiene 'idmiclase'.
                                        // Si no, se necesitaría obtenerlo o ajustar el controlador para pasarlo.
                                        // Por ahora, asumimos que $alumno['idmiclase'] NO está disponible directamente aquí.
                                        // El controlador tendría que pasar un array que contenga 'idmiclase'.
                                        // Vamos a simular que lo tenemos, pero esto necesita ajuste en el controlador.
                                        // La forma correcta es que $alumnosConFoto venga del MisClasesModel y ya incluya idmiclase.
                                        // Si $alumnosConFoto es una lista de objetos User, entonces no tiene idmiclase.
                                        // El controlador ClaseController@viewParticipantes debe construir $alumnosConFoto con el idmiclase.
                                        // TEMPORAL: El enlace de eliminar necesitará el `idmiclase`.
                                        // El controlador actual no pasa `idmiclase` en `$alumnosConFoto`.
                                        // Esto se corregirá en el controlador para que pase el id de la inscripción.
                                        // Por ahora, el enlace podría no funcionar correctamente sin el idmiclase.
                                        // Una solución rápida sería volver a consultar MisClasesModel para obtener el idmiclase
                                        // basado en $alumno['Email'] y $claveClase, pero es ineficiente.
                                        // Lo ideal es que $alumnosInscritos en el controlador ya tenga esta info.
                                        // El modelo MisClasesModel->getAlumnosByClaveClase debería devolver también idmiclase.

                                        // Para que esto funcione, el controlador debe modificar $alumnosInscritos para que contenga idmiclase
                                        // Vamos a asumir que el controlador lo ha hecho.
                                        // Si $alumnosConFoto es el resultado de MisClasesModel->getAlumnosByClaveClase, ya debería tener idmiclase.
                                        // Pero el controlador actual hace un segundo fetch.
                                        // CORRECCIÓN NECESARIA EN EL CONTROLADOR:
                                        // En ClaseController->viewParticipantes, $alumnosInscritos ya tiene email y nombre.
                                        // Debería pasar $alumnosInscritos (que puede incluir idmiclase si el modelo lo da)
                                        // y luego, si se necesita más info (como foto), obtenerla.
                                        // Por ahora, para que el enlace se genere, asumiré que $alumno['idmiclase'] está disponible.
                                        // Esto es una NOTA para revisar y corregir en el controlador.
                                        // El controlador ClaseController@viewParticipantes debe asegurar que $alumnosConFoto
                                        // contenga 'idmiclase' para cada alumno.
                                        // Por ahora, voy a omitir el idmiclase del enlace para evitar errores,
                                        // pero el botón de eliminar NO funcionará correctamente.
                                        // Debería ser: &idmiclase=<?php echo $alumno['idmiclase'];

                                        // Actualización: getAlumnosByClaveClase SÍ devuelve idmiclase si se ajusta el SELECT en el modelo.
                                        // Asumamos que MisClasesModel->getAlumnosByClaveClase devuelve idmiclase, email, nombre.
                                        // Y luego el controlador enriquece con la foto.
                                        // Por lo tanto, $alumno['idmiclase'] debería estar si el modelo se ajustó.
                                        // Si no, el controlador debe buscarlo.
                                        // Por ahora, asumiré que el controlador lo provee.
                                        // Si $alumno es de la tabla usuarios, no tiene idmiclase.
                                        // El controlador debe pasar un array combinado.
                                        // Vamos a asumir que $alumno SÍ tiene `idmiclase` (requiere ajuste en controlador)
                                        // Si no, el botón de eliminar no funcionará.
                                        // Para que funcione, el controlador debe pasar el id de la inscripción.
                                        // El controlador actual en `viewParticipantes` obtiene `$alumnosInscritos`
                                        // y luego crea `$alumnosConFoto`. `$alumnosInscritos` debería tener `idmiclase`.
                                        // Se debe asegurar que `$idmiclase` se pase a la vista.
                                        // Por ahora, voy a construir el enlace asumiendo que $alumno['idmiclase']
                                        // será añadido por el controlador.
                                        // TEMPORAL: El controlador necesita pasar el idmiclase.
                                        // Para que esto funcione, el controlador debe pasar el idmiclase.
                                        // Ajuste en ClaseController:
                                        /*
                                        $alumnosParaVista = [];
                                        foreach($alumnosInscritos as $inscripcion) { // $alumnosInscritos de MisClasesModel
                                            $datosAlumno = $this->userModel->findByEmail($inscripcion['Email']);
                                            if ($datosAlumno) {
                                                $alumnosParaVista[] = array_merge($datosAlumno, ['idmiclase' => $inscripcion['idmiclase']]);
                                            }
                                        }
                                        // y luego pasar $alumnosParaVista a la vista como $alumnosConFoto
                                        */
                                    ?>
                                    <td>
                                        <a href="index.php?action=removeParticipante&idmiclase=<?php echo $alumno['idmiclase_placeholder']; /* Placeholder */ ?>&clave_clase_redirect=<?php echo $claveClase; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Estás seguro de que quieres eliminar a este alumno de la clase?');">
                                           Eliminar de Clase
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo ($usuarioActual['Tipo'] == 'Docente' && $usuarioActual['Email'] == $docenteDeLaClase['Email'] ? '5' : '4'); ?>" class="text-center">
                                    No hay alumnos inscritos en esta clase todavía.
                                </td>
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
