<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/formulariosUSU.php";

$titulo = "Activar paciente";
$form = '../controller/activarPaciente.php';
$accion = 'c';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'S'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
	//si se ha enviado los datos, se procesa la imagen y se muestra el formulario
	if(isset($_POST['activarPaciente'])){
        $paciente = obtenerDatosUsuario($_POST['dnipaciente']);
        formularioUSU03($paciente, $accion, $form, $titulo, $rol);
	}
    else if(isset($_POST['activado'])){
        $activado = activarPaciente($_POST['dni']);
        mensaje($titulo, $mensaje);
    }
    else if(isset($_POST['informar'])){
        $mensaje = 'El usuario ha sido informado de un error.';
        mensaje($titulo, $mensaje);
    }
    else if(isset($_POST['borrar'])){
        $mensaje = borrarUsuario($_POST['dni']);
        mensaje($titulo, $mensaje);
        
        $mensaje = "Activar paciente: ".$_SESSION['usuario'].".";
        log_sistema($mensaje);
    }
}
HTMLformulario($rol);
HTMLfooter();

?>