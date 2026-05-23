<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location:error_handler.php");
}else{
    if((time() - $_SESSION['time']) > 3900){
        session_destroy();
        header("location:index.php");
    }
}
?>