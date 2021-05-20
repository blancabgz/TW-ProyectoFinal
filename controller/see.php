<?php
require_once '../controller/check_login.php';
require_once '../controller/validacion.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';
require_once '../view/formularios.php';

$titulo="Ver usuario";
$form = "../controller/see.php";

if($rol != 'A' && $rol !='C'){
    header("Location: ../view/presentacion.php");
}
else{
    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    if(isset($_POST['ver'])){
        $accion = 'v';
    }else{
        header("Location: ../view/list.php");
    }

    if(isset($_POST['dni'])){
        $dni = $_POST['dni'];
        $datos = obtenerDatosUsuario($dni);
        formularioUSU03($datos, $accion, $form, $titulo);
    }else{
        mensaje($titulo, 'El DNI no es correcto');
    }
    HTMLfooter();
}
?>