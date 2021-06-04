<?php
require_once '../controller/check_login.php';
require_once '../controller/validacion.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';
require_once '../view/formularios.php';

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

    //si viene de listado
    if(isset($_POST['deleteVac'])){
        
        //si el dni está, obtenemos los datos y se muestran en el formulario
        if(isset($_POST['idVac'])){
            $_SESSION['idVac'] = $_POST['idVac'];
            $datos = obtenerDatosVacuna($_POST['idVac']);

            if(is_array($datos)){
                formularioVAC_CAL03($datos, $titulo, $form, $accion);
            }
            else{
                mensaje($titulo, $datos);
            }
        
        //si el dni no está, se muestra un mensaje de error.
        else{
            mensaje($titulo, "La vacuna no es correcta.");
        }
    }
    
    //si ha confirmado borrar usuario, se borra el usuario y se muestra el mensaje por pantalla
    else if(isset($_POST['borrarVac'])){
        $mensaje = borrarVacunaCalendario($_SESSION['idVac']);
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