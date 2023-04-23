<?php
include('includes/conectar.php');
include('includes/secionesUser.php');



if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);


mysqli_free_result($resultado);
mysqli_close($cont);
include('includes/encabezado.php')
?>


<body>
    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php') ?></h2>
        <div class="container2">
            <a class=" editar" href="Editar.php" heh>Editar</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
<br>
    <article class="card">
        <header class="card__header">
            <?php
            if ($a['baner'] == "") {
            ?>
                <img src="https://png.pngtree.com/thumb_back/fw800/background/20210115/pngtree-abstract-yellow-zoom-line-empty-banner-background-image_519823.jpg" alt="pattern card" class="card__header-image" />
            <?php
            } else {
            ?>
                <img src="<?php print $a['baner']; ?>" alt="pattern card" class="card__header-image" />
            <?php
            }

            echo "<img src ='archivos/" . $_SESSION['user'] . "" . $a['Foto'] . "'alt='profile image' class='card__header-profile'> ";
            ?>

        </header>
        <section class="card__body">
            <?php

            echo "<p class='card__text'>Nombre: " . $a['Nombre'] . "</p>";
            echo "<p class='card__text'>Numero de Documento: " . $a['cc'] . "</p>";
            echo "<p class='card__text'>Email: " . $a['Email'] . "</p>";
            ?>

        </section>
        <div class="card__footer">
                <?php
                if ($a['Tipo'] == 'Docente') {
                    echo "<a class='crear'  href='crearClase.php'> Gestionar Clases</a>";
                }

                if ($a['Tipo'] != 'Docente') {
                    echo "<a class='crear'  href='unirse.php'> Gestionar Clases</a>";
                }
                ?>

        </div>
    </article>

    <?php
    include('includes/footer.php');
    ?>


</body>


</html>