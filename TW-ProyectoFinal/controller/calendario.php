<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Calendario de vacunación Andalucia 2020";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//se obtienen las vacunas y el calendario
$vacunas = obtenerListadoVacunas();
$calendario = obtenerCalendarioVacunas();

//comprobamos que no ha habido error
if($vacunas == 0 || $calendario == 0){
    mensaje($titulo, "Error al conectarse a la base de datos.");
}
else if($vacunas == 1 || $calendario == 1){
    mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.");
}
else{
    //se muestra la cabecera del calendario
    cabeceraCalendario($titulo);
    
    //se muestra el cuerpo del calendario
    cuerpoCalendario($calendario, $vacunas);
}

HTMLformulario($rol);
HTMLfooter();
?>