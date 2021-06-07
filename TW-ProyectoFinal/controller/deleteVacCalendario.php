<?php
require_once '../controller/check_login.php';
require_once '../controller/validacion.php';
require_once '../model/basedatos.php';
require_once '../view/vistasComunes.php';
require_once '../view/formulariosVAC_CAL.php';

$titulo = "Borrar usuario";
$form = "../controller/deleteVacCalendario.php";
$accion = 'bV';

//si no es la persona administradora, redirige al inicio
if($rol != 'A'){
    header("Location: ../view/inicio.php");
}
//si es el administrador
else{
    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //si viene del calendario
    if(isset($_POST['deleteVac'])){
        
        //si el dni está, obtenemos los datos y se muestran en el formulario
        if(isset($_POST['id'])){
            $_SESSION['id'] = $_POST['id'];
            $datos = obtenerCalendarioID($_POST['id']);
            $datos['idvacuna'] = $_POST['idvacuna'];
            $vacunas = obtenerListadoVacunas();

            if(is_array($datos)){
                formularioVAC_CAL03($datos, $titulo, $form, $vacunas, 'b');
            }
            else{
                mensaje($titulo, $datos);
            }
        
        //si el dni no está, se muestra un mensaje de error.
        }else{
            mensaje($titulo, "La vacuna no es correcta.");
        }
    }
    
    //si ha confirmado borrar usuario, se borra el usuario y se muestra el mensaje por pantalla
    else if(isset($_POST['borrarVac'])){
        $mensaje = borrarVacunaCalendario($_SESSION['id']);
        mensaje($titulo, $mensaje);
    }
    
    //para cualquier otra, se muestra el mensaje
    else{
        mensaje($titulo, 'La vacuna no es correcta.');
    }
    
    HTMLformulario($rol);
    HTMLfooter();
}
?>