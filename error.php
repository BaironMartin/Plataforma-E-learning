<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING login and register</title>
    <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
    <link rel="icon" href="img/logo.ico">
</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="container container-star">
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-1"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
        <div class="star-2"></div>
    </div>
    <div class="container container-bird">
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="bird bird-anim">
            <div class="bird-container">
                <div class="wing wing-left">
                    <div class="wing-left-top"></div>
                </div>
                <div class="wing wing-right">
                    <div class="wing-right-top"></div>
                </div>
            </div>
        </div>
        <div class="container-title">
            <div class="title">
                <div class="number">4</div>
                <div class="moon">
                    <div class="face">
                        <div class="mouth"></div>
                        <div class="eyes">
                            <div class="eye-left"></div>
                            <div class="eye-right"></div>
                        </div>
                    </div>
                </div>
                <div class="number">4</div>
            </div>
            <div class="subtitle">Vaya. Parece que tomaste un giro equivocado..</div>

            <?php
            if (!isset($_SESSION['user'])) {
                echo "<a  href='index.php'><button>Volver</button></a>";
            } else {
                if (!isset($_SESSION['clave'])) {
                    echo "<a  href='inicio.php'><button>Volver</button></a>";
                }
            }
            ?>

        </div>
    </div>
    <!-- partial -->

</body>

</html>