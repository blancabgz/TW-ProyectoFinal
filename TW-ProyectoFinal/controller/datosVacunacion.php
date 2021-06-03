<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Datos de la vacunación";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si se llega de datosVacuna
if(isset($_POST['datosCartilla'])){

    $vacunas = obtenerListadoVacunas();
    $cartilla = obtenerCartilla($_SESSION['usuario']);

    //se muestra los datos de la vacuna en el calendario
    $nombre = obtenerNombreVacuna($vacunas, $_POST['idVac']);
    $acronimo = obtenerAcronimo($vacunas, $_POST['idVac']);
    $fecha = obtenerFecha($cartilla, $_POST['id']);
    $fabricante = obtenerFabricante($cartilla, $_POST['id']);
    $comentarios = obtenerComentarios($cartilla, $_POST['id']);

    $mensaje = "
    <br>Nombre: ".$nombre."
    <br>Acrónimo: ".$acronimo."
    <br>Fecha: ".$fecha."
    <br>Fabricante: ".$fabricante."
    <br>Comentarios: ".$comentarios."
    ";

    mensaje($titulo, $mensaje);
}

function obtenerFecha($cartilla, $id){
	foreach($cartilla as $c){
        if($c['calendario_id'] == $id){
			$fecha = $c['fecha'];
		}
	}

	return $fecha;
}

function obtenerFabricante($cartilla, $id){
	foreach($cartilla as $c){
		if($c['calendario_id'] == $id){
			$fabricante = $c['fabricante'];
		}
	}
	return $fabricante;
}

function obtenerComentarios($cartilla, $id){
	foreach($cartilla as $c){
		if($c['calendario_id'] == $id){
			$comentarios = $c['comentarios'];
		}
	}
	return $comentarios;
}

HTMLformulario($rol);
HTMLfooter();
?>