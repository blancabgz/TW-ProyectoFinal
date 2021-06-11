<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../model/bdVacunas.php";
require_once "../model/bdCalCart.php";
require_once "../model/log.php";
require_once "../view/vistasComunes.php";
require_once "../view/formularioVAC_CAR.php";

$titulo = "Modificar vacuna de la cartilla";
$titulo_form = "Editar vacunación";
$form = '../controller/editVacunaCartilla.php';
$accion = 'e';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'S' && $rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	$vacunas = obtenerListadoVacunas();

	//si se ha pulsado enviar desde Datos Vacuna
	if(isset($_POST['editVac'])){
        $datos = obtenerVacunacion($_POST['id']);
        $datos['nombre'] = $_POST['nombre'];
        if(is_array($datos)){
		    
            //se muestra el formulario para rellenar la información
            formularioVAC_CAR02($titulo_form, $form, $datos, '', $accion);
        }else{
            mensaje($titulo_form, $datos);
        }
    }
	else if($_POST['validarDatos']){
		
		$datos = procesarDatosVacunaCartilla($_POST, $vacunas);
		$validar = validarDatosVacunaCartilla($_POST, $vacunas);
        
        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioVAC_CAR02($titulo_form, $form, $datos, $validar, $accion);
        }
        //si está todo OK, se inserta el usuario
        else{
            $mensaje = actualizarVacunacion($datos, $datos['id']);
            mensaje($titulo_form, $mensaje);

            $mens = "Modificar vacunación: ".$_SESSION['usuario'].". Mensaje: ".$mensaje.".";
            log_sistema($mens);
        }
	}
}
HTMLformulario($rol);
HTMLfooter();

?>
