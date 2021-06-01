<?php
require_once '../controller/check_login.php';
require_once '../controller/validacion.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';
require_once '../view/formularios.php';

$titulo = "Borrar usuario";
$form = "../controller/deleteUser.php";
$accion = 'b';

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
    if(isset($_POST['borrar'])){
        
        //si el dni está, obtenemos los datos y se muestran en el formulario
        if(isset($_POST['dni'])){
            $datos = obtenerDatosUsuario($_POST['dni']);

            //si es un array, es la consulta, se muestra por el formulario
            if(is_array($datos)){
                formularioUSU03($datos, $accion, $form, $titulo, $rol);
            }
            //si no, ha habido error
            else{
                mensaje($titulo, $datos);
            }
        }
        //si el dni no está, se muestra un mensaje de error.
        else{
            mensaje($titulo, "El DNI no es correcto.");
        }
    }
    
    //si ha confirmado borrar usuario, se borra el usuario y se muestra el mensaje por pantalla
    elseif(isset($_POST['borrarUsuario'])){
        $mensaje = borrarUsuario($_POST['dni']);
        mensaje($titulo, $mensaje);
    }
    
    //para cualquier otra, se muestra el mensaje
    else{
        mensaje($titulo, 'El DNI no es correcto.');
    }
    
    HTMLformulario($rol);
    HTMLfooter();
}
?>