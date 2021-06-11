<?php
require_once "../controller/check_login.php";
require_once "../model/basedatos.php";
require_once "../model/log.php";
require_once "../view/vistasComunes.php";
require_once "../view/vistasSistema.php";

$titulo="Consultar log";
$titulo_form="Log del sistema";
$form = '../controller/logsistema.php';
$accion = 'l';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
    $log = obtenerLogSistema();

    if(!is_array($log)){
        mensaje($titulo, $mensaje);
    }
    else{
        mostrarLogSistema($titulo_form, $log);
    }
}
HTMLformulario($rol);
HTMLfooter();

?>