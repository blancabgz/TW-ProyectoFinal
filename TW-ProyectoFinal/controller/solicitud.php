<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once '../model/bdUsuarios.php';
require_once "../view/vistasComunes.php";
require_once "../view/formulariosUSU.php";

$titulo = "Solicitud";
$titulo_form = "Solicitar alta";
$form = '../controller/solicitud.php';
$accion = 's';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si se ha enviado los datos, se procesa la fotografía y se muestra el formulario
if(isset($_POST['enviarDatos'])){
    procesarFotografia();
    formularioUSU06($_POST, $accion, $form, $titulo_form, $rol);
}

//si va a validar los datos, se procesan y validan los datos
else if(isset($_POST['validarDatos'])){
    $datos = procesarDatos($_POST);
    $validar = validarDatos($datos, $rol);

    //si hay errores se muestran
    if(!empty($validar)){
        formularioUSU07($datos, $validar, $form, $titulo_form);
    }
    //si está todo OK, se inserta el usuario
    else{
        $mensaje = insertarUsuario($datos, $rol);
        mensaje($titulo, $mensaje);
    }
}

else{
	formularioUSU04($titulo_form, $form);
}

HTMLformulario($rol);
HTMLfooter();
?>
