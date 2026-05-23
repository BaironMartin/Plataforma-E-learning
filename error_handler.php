<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Plataforma E-LEARNING</title>
    <link rel="icon" href="img/logo.ico">
    <link rel="stylesheet" href="css/estilos.css?v=<?php echo rand(); ?>">
    <style>
        /* Estilos específicos para la página de error */
        body {
            overflow: hidden;
        }
        .container-star {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -1;
        }
        .star-1, .star-2 {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        .container-bird {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: white;
            text-align: center;
        }
        .title {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 8rem;
            font-weight: bold;
        }
        .moon {
            width: 100px;
            height: 100px;
            background: #f5f5f5;
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(255,255,255,0.3);
        }
        .subtitle {
            font-size: 1.5rem;
            margin: 20px 0;
        }
        button {
            padding: 15px 40px;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.3s;
        }
        button:hover {
            transform: translateY(-3px);
        }
    </style>
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
