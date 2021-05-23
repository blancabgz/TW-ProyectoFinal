<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Añadir usuario";
$titulo_form="Nuevo usuario";
$form = '../controller/add.php';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
else{
	
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
        formularioUSU03($_POST, 'a', $form, $titulo_form, $rol);
	}

    //si es va a validar los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, 'a');
        
        //si hay errores
        if(!empty($validar)){
            formularioUSU02($datos, $validar, $form, $titulo_form, $rol);
        }
        //si está todo OK
        else{
            $mensaje = insertarUsuario($datos, $rol);
            mensaje($titulo, $mensaje);
        }
	}
	else{
		formularioUSU01($titulo_form, $rol, $form);
	}
}
HTMLformulario($rol);
HTMLfooter();
?>