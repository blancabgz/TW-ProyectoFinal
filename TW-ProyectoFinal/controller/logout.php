<?php
require_once '../model/basedatos.php';

if (session_status()==PHP_SESSION_NONE) 
    session_start();


if (isset($_SESSION['usuario']))
    $mensaje = "Logout: ".$_SESSION['usuario'].".";
    log_sistema($mensaje);
    acabarSesion();

header("Location: ../view/inicio.php");

function acabarSesion(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    session_unset();    //borra las variables de sesión
    $galletas = session_get_cookie_params();    //obtenemos las cookies de sesión
    session_destroy();
}  
//header('Refresh: 0.1;');
?>