<?php
require_once '../model/basedatos.php';

if(session_status() == PHP_SESSION_NONE)
    session_start();
$rol = 'V';

if(isset($_SESSION['usuario'])){   
    $rol = obtenerTipoUsuario($_SESSION['usuario']);
    $_SESSION['deaquivengo']=$_SERVER['REQUEST_URI'];
}
?>