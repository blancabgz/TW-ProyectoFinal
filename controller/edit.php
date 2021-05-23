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

if($rol != 'A' && $rol != 'P' && $rol != 'S'){
	header("Location: ../view/inicio.php");
}
else if($rol == 'A'){
	$titulo_form="Modificar usuario";

	//si se ha enviado los datos
	if(isset($_POST['enviarDatos'])){
        //si se ha insertado imagen
        if(isset($_FILES['fotografia']['tmp_name']) && !empty($_FILES['fotografia']['tmp_name'])){
        
            //$_POST['fotografia] toma el nombre de la imagen
            $_POST['fotografia'] = $_FILES["fotografia"]["tmp_name"];
        }
        //si se viene de un formulario con la imagen ya puesta
        else if(isset($_POST['foto'])){
            $_POST['fotografia'] = $_POST['foto'];
        }
        //si no se ha insertado imagen
        else{
            //vemos si $_POST['fotografia] tiene valor
            if(!isset($_POST['fotografia'])){
                $_POST['fotografia'] = '';
            }
        }
        formularioUSU03($_POST, 'e', $form, $titulo_form, $rol);
	}
    //si viene del listado
    else if(isset($_POST['editarUser'])){
        $dni = $_POST['dni'];
        $datos = obtenerDatosUsuario($dni);
        formularioUSU02($datos, 'e', $form, $titulo_form, $rol);
    }
    //si es va a validar los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, 'c');

        //si hay errores
        if(!empty($validar)){
            formularioUSU02($datos, $validar, $form, $titulo_form, $rol);
        }
        //si está todo OK
        else{
            $mensaje = actualizarUsuario($datos, $rol);
            mensaje($titulo_form, $mensaje);
        }
	}
	else{
		header("Location: ../view/inicio.php");
	}
}
else if($rol == 'P' || $rol == 'S'){
    $titulo_form="Modificar mis datos";

	//si se ha enviado los datos
	if(isset($_POST['enviarDatos'])){//si se ha insertado imagen
        if(isset($_FILES['fotografia']['tmp_name']) && !empty($_FILES['fotografia']['tmp_name'])){
        
            //$_POST['fotografia] toma el nombre de la imagen
            $_POST['fotografia'] = $_FILES["fotografia"]["tmp_name"];
        }
        //si se viene de un formulario con la imagen ya puesta
        else if(isset($_POST['foto'])){
            $_POST['fotografia'] = $_POST['foto'];
        }
        //si no se ha insertado imagen
        else{
            //vemos si $_POST['fotografia] tiene valor
            if(!isset($_POST['fotografia'])){
                $_POST['fotografia'] = '';
            }
        }
        formularioUSU06($_POST, 'e', $form, $titulo_form);
	}
    //si es va a validar los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, $rol);

        //si hay errores
        if(!empty($validar)){
            formularioUSU05($_POST, $validar, $titulo_form);
        }
        //si está todo OK
        else{
            $mensaje = actualizarUsuario($_POST, $rol);
            mensaje($titulo_form, $mensaje);
        }
	}
    //si ha pulsado en Datos Personales
	else{
		$dni = $_SESSION['usuario'];
        $datos = obtenerDatosUsuario($dni);
        formularioUSU05($datos, 'e', $titulo_form);
	}
}
HTMLformulario($rol);
HTMLfooter();
?>