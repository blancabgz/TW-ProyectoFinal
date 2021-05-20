<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Editar usuario";
$form = '../controller/edit.php';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

if($rol != 'A' && $rol != 'C'){
	header("Location: ../view/presentacion.php");
}
else if($rol == 'A'){
	$titulo_form="Modificar usuario";

	//si se ha enviado los datos
	if(isset($_POST['enviarDatos'])){
        formularioUSU03($_POST, 'e', $form, $titulo_form);
	}
    //si viene del listado
    else if(isset($_POST['editarUser'])){
        $dni = $_POST['dni'];
        $datos = obtenerDatosUsuario($dni);
        formularioUSU02($datos, 'e', $form, $titulo_form);
    }
    //si es va a validar los datos
	else if(isset($_POST['validarDatos'])){
	    $validar = validarDatos($_POST, 'c');
        //si hay errores
        if(!empty($validar)){
            formularioUSU02($_POST, $validar, $form, $titulo_form);
        }
        //si está todo OK
        else{
            $mensaje = actualizarUsuario($_POST, 'a');
            mensaje($titulo_form, $mensaje);
        }
	}
	else{
		header("Location: ../view/presentacion.php");
	}
}
else if($rol == 'C'){
    $titulo_form="Modificar mis datos";

	//si se ha enviado los datos
	if(isset($_POST['enviarDatos'])){
        formularioUSU06($_POST, 'e', $form, $titulo_form);
	}
    //si viene del listado
    else if(isset($_POST['editarUser'])){
        $dni = $_POST['dni'];
        $datos = obtenerDatosUsuario($dni);
        formularioUSU05($datos, 'e', $form, $titulo_form);
    }
    //si es va a validar los datos
	else if(isset($_POST['validarDatos'])){
	    $validar = validarDatos($_POST, 'c');

        //si hay errores
        if(!empty($validar)){
            formularioUSU05($_POST, $validar, $form, $titulo_form);
        }
        //si está todo OK
        else{
            $mensaje = actualizarUsuario($_POST, 'c');
            mensaje($titulo_form, $mensaje);
        }
	}
	else{
		header("Location: ../view/presentacion.php");
	}
}
HTMLformulario($rol);
HTMLfooter();
?>