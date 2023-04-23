<ul id="slide-out" class="sidenav ">
    <li>
        <div class="user-view">
            <div class="background">
                <?php echo "<img src='archivos/baner/" . $a['nombre'] . "" . $a['baner'] . "' width=100% >" ?>
            </div>
            <?php
            echo " <a href='#user'><img class='circle' src='archivos/perfil/" . $a['nombre'] . "" . $a['foto'] . "'></a>";
            echo "<a href='#name'><span class='white-text name'>" . $a['nombre'] . " " . $a['apellidos'] . "</a>";
            echo "<a href='#email'><span class='white-text email'>" . $a['email'] . "</span></a>";
            ?>
        </div>
    </li>
    <li><a href="inicio_admin.php"><i class="material-icons">add &nbsp;</i>Registrar Directivos</a></li>
    <li><a href="registrarUsuario_admin.php"><i class="material-icons">add &nbsp;</i>Registrar Usuario</a></li>
    <li><a href="docentes_admin.php">Docentes</a></li>
    <li><a href="alumnos_admin.php">Alumnos</a></li>
    <li><a href="clases_admin.php">Materias</a></li>
    <li>
        <div class="divider"></div>
    </li>
    <li><a class="waves-effect btn red" href="inicio_admin.php?cerrar=1">Cerrar Secion</a></li>
</ul>
<a href="#" data-target="slide-out" class="sidenav-trigger green-text"><i class="material-icons">menu</i></a>