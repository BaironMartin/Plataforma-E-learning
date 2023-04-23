<?php

function login_Index($u,$p){
    global $cont;
    $p= hash('sha512',$p);
    $sql = "SELECT * FROM usuarios WHERE Email='$u' AND Clave='$p'";
    $resultado = mysqli_query($cont, $sql);
    if (mysqli_num_rows($resultado) == 1) {
        $_SESSION['user'] = $u;
        $_SESSION['time'] = time();
        header("Location: inicio.php");
    } else {
        header("Location: errors/errorlogin1.php");
    }
    mysqli_free_result($resultado);
    mysqli_close($cont);
}

function registrer_Index($u,$p,$n,$cc,$f,$t,$g){

    global $cont;
    $p= hash('sha512',$p);
    $id = $u;
    $sql = "SELECT* FROM usuarios WHERE Email='$id'";
    $resultado = mysqli_query($cont, $sql);
    $sql2 = "SELECT* FROM usuarios WHERE cc='$cc'";
    $resultado2 = mysqli_query($cont, $sql2);
    if (mysqli_num_rows($resultado2) == 0) {
        if (mysqli_num_rows($resultado2) == 0) {

        mysqli_query($cont, "INSERT INTO usuarios VALUE ('$u','$p','$n','$cc','$f','$t','$g')");
        move_uploaded_file($_FILES['photo']['tmp_name'], "archivos/" . $u . $f);
        header("Location: index.php");
        }
        else{
            header("Location: errors/errorlogin2.php");
        }
    } else {
        header("Location: errors/errorlogin3.php");
        
    }

    mysqli_free_result($resultado);
    mysqli_close($cont);
}

?>