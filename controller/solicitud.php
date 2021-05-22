<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Solicitud";
$titulo_form="Solicitar alta";
$form = '../controller/solicitud.php';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si se ha enviado los datos
if(isset($_POST['enviarDatos'])){
    formularioUSU03($_POST, 'a', $form, $titulo_form, $rol);
}
//si es va a validar los datos
else if(isset($_POST['validarDatos'])){
    $validar = validarDatos($_POST, $rol);
       
        //si hay errores
        if(!empty($validar)){
            formularioUSU02($_POST, $validar, $form, $titulo_form, $rol);
        }
        //si está todo OK
        else{
            $mensaje = insertarUsuario($_POST, $rol);
            mensaje($titulo, $mensaje);
        }
}
else{
	formularioUSU01($titulo_form, $rol, 'solicitud.php');
}

HTMLformulario($rol);
HTMLfooter();
?>