<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/formulariosVAC_CAL.php";

$titulo="Añadir vacuna al calendario";
$titulo_form="Nueva vacuna";
$form = '../controller/addVacunaCalendario.php';
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

    $vacunas = obtenerListadoVacunas();
	
	//si se ha enviado los datos, se procesa la imagen y se muestra el formulario
	if(isset($_POST['enviarDatos'])){
        formularioVAC_CAL03($_POST, $titulo_form, $form, $vacunas);
	}
    
    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatosVacunaCalendario($_POST, $vacunas);
	    $validar = validarDatosVacunaCalendario($datos, $vacunas);
        
        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioVAC_CAL02($datos, $titulo_form, $form, $validar, $vacunas);
        }
        //si está todo OK, se inserta el usuario
        else{
            $mensaje = insertarVacunaCalendario($datos);
            mensaje($titulo, $mensaje);
        }
	}

    //si viene de nuevas, se le muestra el formulario
	else{
		formularioVAC_CAL01($titulo_form, $form, $vacunas);
	}
}
HTMLformulario($rol);
HTMLfooter();

?>