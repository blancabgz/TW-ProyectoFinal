<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../model/bdVacunas.php";
require_once "../model/bdCalCart.php";
require_once "../model/log.php";
require_once "../view/vistasComunes.php";
require_once "../view/formularioVAC_CAR.php";

$titulo="Añadir vacuna a la cartilla";
$titulo_form="Añadir vacunación";
$form = '../controller/addVacunaCartilla.php';
$accion = 'a';

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
	if(isset($_POST['vacunaCartilla'])){

		//se muestra el formulario para rellenar la información
        formularioVAC_CAR01($titulo_form, $form, $_POST['nombre'], $_POST['calendario_id']);
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
            $mensaje = insertarVacunaCartilla($datos, $_SESSION['dnipaciente']);
            mensaje($titulo, $mensaje);

            $mens = "Añadir vacuna a la cartilla: ".$_SESSION['usuario'].". Mensaje: ".$mensaje.".";
            log_sistema($mens);
        }
	}
}
HTMLformulario($rol);
HTMLfooter();

?>
