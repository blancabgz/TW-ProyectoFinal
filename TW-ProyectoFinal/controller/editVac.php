<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once '../model/bdVacunas.php';
require_once "../model/log.php";
require_once "../view/vistasComunes.php";
require_once "../view/formulariosVAC.php";

$titulo="Editar vacuna";
$form = '../controller/editVac.php';
$accion = 'e';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si es administrador, puede modificar cualquier campo de la vacuna
if($rol == 'A'){

    //si se ha enviado los datos, se procesan los datos y se muestra el formulario
	if(isset($_POST['enviarDatos'])){
        $datos = procesarDatosVacuna($_POST);
        formularioVAC03($datos, $titulo, $form, $accion);
	}

    //si viene del listado se obtiene los datos y se muestran en un formulario
    else if(isset($_POST['editVac'])){
        $_SESSION['idVac'] = $_POST['idVac'];
        $datos = obtenerDatosVacuna($_SESSION['idVac']);
        
        if(is_array($datos)){
            formularioVAC02($datos, $titulo, $form, '');
        }
        else{
            mensaje($titulo, $datos);
        }
    }

    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatosVacuna($_POST);
	    $validar = validarDatosVacuna($datos);

        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioVAC02($datos, $titulo, $form, $validar);
        }
        //si está todo OK se actualiza el usuario
        else{
            $mensaje = actualizarVacuna($datos, $_SESSION['idVac']);
            mensaje($titulo, $mensaje);

            $mens = "Borrar vacuna: ".$_SESSION['usuario'].". Mensaje: ".$mensaje.".";
            log_sistema($mens);
        }
	}
	else{
		header("Location: ../view/inicio.php");
	}
}

//si no es administrador, se le redirige al inicio
else{
    header("Location: ../view/inicio.php");
	
}
?>