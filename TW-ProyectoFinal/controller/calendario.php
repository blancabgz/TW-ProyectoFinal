<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Calendario de vacunación Andalucia 2020";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//se muestra la cabecera del calendario
cabeceraCalendario($titulo);

//se obtienen las vacunas y el calendario
$vacunas = obtenerListadoVacunas();
$calendario = obtenerCalendarioVacunas();

//se muestra el cuerpo del calendario
cuerpoCalendario($calendario, $vacunas);


HTMLformulario($rol);
HTMLfooter();
?>