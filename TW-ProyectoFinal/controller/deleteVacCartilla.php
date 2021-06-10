<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/formularioVAC_CAR.php";

$titulo="Eliminar vacuna de la cartilla";
$titulo_form="Eliminar vacunación";
$form = '../controller/deleteVacCartilla.php';
$accion = 'b';

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
	if(isset($_POST['deleteVac'])){
        $datos = obtenerVacunacion($_POST['id']);
        $datos['nombre'] = $_POST['nombre'];
        if(is_array($datos)){
		    
            //se muestra el formulario para rellenar la información
            formularioVAC_CAR03($titulo_form, $form, $datos, '', $accion);
        }else{
            mensaje($titulo_form, $datos);
        }
    }
	else if($_POST['eliminarDatos']){
        $mensaje = eliminarVacunacion($_POST['id']);
        mensaje($titulo_form, $mensaje);
        
        $mens = "Borrar vacunación: ".$_SESSION['usuario'].". Mensaje: ".$mensaje.".";
        log_sistema($mens);
	}
}
HTMLformulario($rol);
HTMLfooter();

?>
