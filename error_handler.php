<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Plataforma E-LEARNING</title>
    <link rel="icon" href="img/logo.ico">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo rand(); ?>">
</head>
<body>
    <div class="container container-star">
        <?php for($i = 0; $i < 40; $i++): ?>
            <div class="star-1"></div>
        <?php endfor; ?>
        <?php for($i = 0; $i < 40; $i++): ?>
            <div class="star-2"></div>
        <?php endfor; ?>
    </div>
    
    <div class="container container-bird">
        <?php for($i = 0; $i < 6; $i++): ?>
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
        <?php endfor; ?>
        
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
            
            <div class="subtitle">
                <?php 
                $tipo = isset($_GET['t']) ? $_GET['t'] : 'general';
                switch($tipo) {
                    case 'captcha':
                        echo "Verifique el Captcha Nuevamente";
                        break;
                    case 'login':
                        echo "Usuario y/o contraseña incorrectos";
                        break;
                    case 'registro':
                        echo "Correo ya registrado";
                        break;
                    case 'existe':
                        echo "Ya tienes una cuenta";
                        break;
                    case 'seguridad':
                        echo "Pregunta de seguridad incorrecta";
                        break;
                    case 'admin':
                        echo "Error administrativo";
                        break;
                    default:
                        echo "Vaya. Parece que tomaste un giro equivocado.";
                }
                ?>
            </div>
            
            <p>En caso de que el error persista, comuníquese con el administrador:<br>soporte@prueba.co</p>
            
            <?php if(!isset($_SESSION['user'])): ?>
                <a href="index.php"><button>Volver</button></a>
            <?php elseif(!isset($_SESSION['clave'])): ?>
                <a href="inicio.php"><button>Volver</button></a>
            <?php else: ?>
                <a href="inicio.php"><button>Volver</button></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
