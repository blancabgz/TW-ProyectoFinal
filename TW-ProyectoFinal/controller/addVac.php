<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/formulariosVAC.php";

$titulo="Añadir vacuna";
$titulo_form="Nueva vacuna";
$form = '../controller/addVac.php';
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
        formularioVAC03($_POST, $titulo_form, $form, $accion);
	}
    
    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatosVacuna($_POST);
	    $validar = validarDatosVacuna($datos);
        
        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioVAC02($datos, $titulo_form, $form, $validar);
        }
        //si está todo OK, se inserta el usuario
        else{
            $mensaje = insertarVacuna($datos, $rol);
            mensaje($titulo, $mensaje);
        }
	}

    //si viene de nuevas, se le muestra el formulario
	else{
		formularioVAC01($titulo_form, $form);
	}
}
HTMLformulario($rol);
HTMLfooter();

?>