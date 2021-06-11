<?php
require_once '../controller/check_login.php';
require_once '../model/basedatos.php';
require_once "../model/bdVacunas.php";
require_once '../view/vistasComunes.php';
require_once '../view/vistasVacunas.php';

$titulo="Listado de vacunas";

if($rol == 'A'){

    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //obtenemos el listado
    $lista = obtenerListadoVacunas();
    if($lista == 0){
        mensaje($titulo, "Error al conectarse a la base de datos.");
    }
    else if($lista == 1){
        mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.");
    }
    else{
        //mostramos el listado
        mostrarListaVacunas($lista, $titulo);
    }

    HTMLformulario($rol);
    HTMLfooter();
}
else{
    header("Location: ../view/inicio.php");
}
?>
