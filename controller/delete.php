<?php
require_once '../controller/check_login.php';
require_once '../controller/validacion.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';
require_once '../view/formularios.php';

$titulo="Borrar usuario";
$form="../controller/delete.php";

if($rol != 'A'){
    header("Location: ../view/inicio.php");
}
else{
    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //si viene de listado
    if(isset($_POST['borrar'])){
        $accion = 'b';
        if(isset($_POST['dni'])){
            $dni = $_POST['dni'];
            $datos = obtenerDatosUsuario($dni);
            formularioUSU03($datos, $accion, $form, $titulo);
        }else{
            mensaje("El DNI no es correcto.");
        }
    }elseif(isset($_POST['borrarUsuario'])){
        $mensaje = borrarUsuario($_POST['dni']);
        mensaje($titulo, $mensaje);
    }else{
        mensaje($titulo, 'El DNI no es correcto.');
    }
    HTMLfooter();
}
?>