<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/formulariosUSU.php";

$titulo="Añadir usuario";
$titulo_form="Nuevo usuario";
$form = '../controller/addUser.php';
$accion = 'a';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
	//si se ha enviado los datos, se procesa la imagen y se muestra el formulario
	if(isset($_POST['enviarDatos'])){
        procesarFotografia();
        formularioUSU03($_POST, $accion, $form, $titulo_form, $rol);
	}
    
    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, $accion);
        
        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioUSU02($datos, $validar, $form, $titulo_form, $rol, $accion);
        }
        //si está todo OK, se inserta el usuario
        else{
            $mensaje = insertarUsuario($datos, $rol);
            mensaje($titulo, $mensaje);
        }
	}

    //si viene de nuevas, se le muestra el formulario
	else{
		formularioUSU01($titulo_form, $form);
	}
}
HTMLformulario($rol);
HTMLfooter();

?>