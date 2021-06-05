<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Añadir vacuna a la cartilla";
$titulo_form="Nueva vacuna";
$form = '../controller/addVacCartilla.php';
$accion = 'a';

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
	if(isset($_POST['addVacCartilla'])){
        formularioVAC03($_POST, $titulo_form, $form, $accion);
    }
}
HTMLformulario($rol);
HTMLfooter();

?>