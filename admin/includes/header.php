<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma E-LEARNING login and register</title>
    <link rel="icon" href="../img/logo.ico">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/materialize.min.css">
    <link rel="stylesheet" href="css/main.css?v=<?php echo (rand()); ?>">

    <script src="js/materialize.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/materialize.js"></script>

</head>

<body>


    <nav class='green'>
        <div class="container">
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo  ">Institucion educativa Horizontes Academicos</a>
                <p class="btn right  orange"><span><?php echo $a['cargo'];?></span></p>
            </div>
        </div>
    </nav>