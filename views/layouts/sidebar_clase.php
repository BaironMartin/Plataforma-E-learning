<?php
// Variables esperadas:
// $claveClaseActual (string) - La clave de la clase actual.
// $usuarioTipoActual (string) - 'Docente' o 'Estudiante'.
// $activeLink (string) - Para marcar el enlace activo (ej. 'plan', 'tareas', 'calificaciones').

if (empty($claveClaseActual)) {
    // No debería pasar si se llama correctamente, pero es una salvaguarda.
    echo "<p>Error: No se ha especificado la clase para el menú.</p>";
    return;
}

$menuItems = [
    ['action' => 'viewPlan', 'label' => 'Plan de Estudio', 'id' => 'plan'],
    // Para Tareas, la acción depende del tipo de usuario
    // Docentes podrían ir a una vista general de tareas de la clase (ej. 'listTareasDocente')
    // Estudiantes van a su lista de tareas para entregar ('listTareasEstudiante')
    // Por ahora, simplificaremos y asumiremos que Tareas lleva a un punto de entrada y el controlador decide.
    // O, mejor, definimos la acción aquí mismo:
    ['action' => ($usuarioTipoActual === 'Docente' ? 'viewPlan' : 'listTareasEstudiante'), 'label' => 'Tareas', 'id' => 'tareas'], // Docente ve tareas en Plan, Estudiante tiene su lista. O crear accion TareaDocente
    ['action' => 'viewCalificaciones', 'label' => 'Calificaciones', 'id' => 'calificaciones'],
    ['action' => 'viewForo', 'label' => 'Foro', 'id' => 'foro'], // Necesitará acción y vista
    ['action' => 'viewParticipantes', 'label' => 'Participantes', 'id' => 'participantes'], // Necesitará acción y vista
    ['action' => 'viewBiblioteca', 'label' => 'Biblioteca', 'id' => 'biblioteca'], // Necesitará acción y vista
    // ['action' => 'viewReuniones', 'label' => 'Reuniones', 'id' => 'reuniones'], // Necesitará acción y vista
];

// Ajustar el enlace de "Tareas" específicamente para docentes si es necesario.
// Por ejemplo, si los docentes gestionan tareas desde la vista del Plan de Estudio,
// el enlace de "Tareas" podría ser redundante o apuntar también al Plan.
// Si tienen una vista separada para ver todas las entregas de todas las actividades:
if ($usuarioTipoActual === 'Docente') {
    // Encontrar el item 'tareas' y cambiar su acción si es necesario
    foreach ($menuItems as $key => $item) {
        if ($item['id'] === 'tareas') {
            // Ejemplo: los docentes ven el estado de las tareas desde la página de calificaciones o una específica de tareas.
            // $menuItems[$key]['action'] = 'viewTareaStatusDocente'; // Nueva acción hipotética
            // O simplemente lo enlazamos a calificaciones por ahora, ya que ahí ven las entregas.
             $menuItems[$key]['action'] = 'viewCalificaciones'; // O una vista de resumen de tareas.
             // O podrías tener una acción que liste todas las actividades del plan para que el docente seleccione una y vea las entregas.
             // $menuItems[$key]['action'] = 'listActividadesParaCalificar';
        }
    }
}


?>
<div class="sidebar-sticky">
    <h4>Menú de Clase</h4>
    <ul class="nav flex-column">
        <?php foreach ($menuItems as $item): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($activeLink) && $activeLink === $item['id'] ? 'active font-weight-bold' : ''); ?>"
                   href="index.php?action=<?php echo htmlspecialchars($item['action']); ?>&clave=<?php echo htmlspecialchars($claveClaseActual); ?>">
                    <?php echo htmlspecialchars($item['label']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">Volver al Dashboard</a>
    <?php if ($usuarioTipoActual === 'Docente'): ?>
        <a href="index.php?action=manageClases" class="btn btn-outline-info btn-sm mt-2">Gestionar Mis Clases</a>
    <?php else: ?>
        <a href="index.php?action=listStudentClases" class="btn btn-outline-info btn-sm mt-2">Mis Clases / Unirme</a>
    <?php endif; ?>
</div>
