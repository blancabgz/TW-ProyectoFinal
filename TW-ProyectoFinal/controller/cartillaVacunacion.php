<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Cartilla de Vacunación";

if($rol == 'P'){
    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //se muestra la cabecera del calendario
    cabeceraCalendario($titulo);

    //se obtienen las vacunas y el calendario
    $totalVacunas = obtenerListadoVacunas();
    $calendario = obtenerCalendarioVacunas();
    $cartilla = obtenerCartilla($_SESSION['usuario']);

    //comprobamos que no ha habido error
    if($totalVacunas == 0 || $calendario == 0 || $cartilla == 0){
        mensaje($titulo, "Error al conectarse a la base de datos.");
    }
    else if($totalVacunas == 1 || $calendario == 1 || $cartilla == 1){
        mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.";
    }
    else if($cartilla == 2){
        mensaje($titulo, "No se ha encontrado el usuario con el identificador establecido.");
    }
    else{
        //se muestra el cuerpo del calendario
        cuerpoCartilla($calendario, $totalVacunas, $cartilla);
    }
    
    HTMLformulario($rol);
    HTMLfooter();
}else{
    header("Location: ../view/inicio.php");
}
?>