<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasComunes.php";
require_once "../view/vistasUsuarios.php";
require_once "../view/formulariosUSU.php";

$titulo="Buscar pacientes";
$titulo_list="Listado de pacientes";
$form = '../controller/busquedaPacientes.php';
$accion = 'b';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si es el sanitario, puede buscar pacientes
if($rol == 'S'){

    //si se ha dado a buscar pacientes
    if(isset($_POST['buscarPacientes'])){

        //buscamos los pacientes
        $pacientes = buscarPacientes($_POST);
        $_SESSION['pacientes'] = $pacientes;
        $_SESSION['vengode'] = 'pacientes';

        if(is_array($pacientes)){
            mostrarListaPacientes($pacientes, $titulo_list);
        }
        else{
            mensaje($titulo, $pacientes);
        }
    }
    //si llega de la cartilla de vacunación
    else if(isset($_POST['cartillaVac'])){
        mostrarListaPacientes($_SESSION['pacientes'], $titulo);
    }
    else{
        formularioBUSQP($titulo, $form);
    }
//si no es sanitario, se le redirige al inicio
}
else{
    header("Location: ../view/inicio.php");
}
HTMLformulario($rol);
HTMLfooter();
?>
