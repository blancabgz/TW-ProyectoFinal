<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Datos de la vacuna";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si se llega de datosVacuna
if(isset($_POST['datosVacuna'])){

    $vacunas = obtenerListadoVacunas();
    $calendario = obtenerCalendarioVacunas();

    //se muestra los datos de la vacuna en el calendario
    $nombre = obtenerNombreVacuna($vacunas, $_POST['idVac']);
    $acronimo = obtenerAcronimo($vacunas, $_POST['idVac']);
    /*$sexo = obtenerSexoVacuna($calendario, $_POST['id']);
    $tipo = obtenerTipoVacuna($calendario, $_POST['id']);
    $comentarios = obtenerComentarios($calendario, $_POST['id']);*/

    $sexo = $_POST['sexo'];
    $tipo = $_POST['tipo'];
    $comentarios = $_POST['comment'];
    
    //procesamos los datos
    if($sexo == 'T'){
        $sexo = "Para todas las personas";
    }
    else if($sexo == 'M'){
        $sexo = "Para mujeres";
    }
    else if($sexo == 'H'){
        $sexo = "Para hombres";
    }

    if($tipo == 'S'){
        $tipo = "Administración Sistemática";
    }
    else if($tipo == 'N'){
        $tipo = "Administración en personas susceptibles o no vacunadas con anterioridad";
    }
    else if($tipo == 'R'){
        $tipo = "Administración en recién nacidos";
    }


    $mensaje = "
    <br>Nombre: ".$nombre."
    <br>Acrónimo: ".$acronimo."
    <br>Sexo: ".$sexo."
    <br>Tipo: ".$tipo."
    <br>Comentarios: ".$comentarios."
    ";

    mensaje($titulo, $mensaje);
}

function obtenerSexoVacuna($calendario, $id){
	foreach($calendario as $c){
		if($c['id'] == $id){
			$sexo = $c['sexo'];
		}
	}
	return $sexo;
}

function obtenerTipoVacuna($calendario, $id){
	foreach($calendario as $c){
		if($c['id'] == $id){
			$tipo = $c['tipo'];
		}
	}
	return $tipo;
}

function obtenerComentarios($calendario, $id){
	foreach($calendario as $c){
		if($c['id'] == $id){
			$comentarios = $c['comentarios'];
		}
	}
	return $comentarios;
}

HTMLformulario($rol);
HTMLfooter();
?>