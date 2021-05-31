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



function obtenerNombreVacuna($lista, $id){
	foreach($lista as $v){
		if($v['id'] == $id){
			$nombre = $v['nombre'];
		}
	}
	return $nombre;
}

function obtenerAcronimo($lista, $id){
	foreach($lista as $v){
		if($v['id'] == $id){
			$acronimo = $v['acronimo'];
		}
	}
	return $acronimo;
}

function cabeceraCalendario($titulo){
	$cabecera = ['Vacuna', 'Pre natal', '0 meses', '2 meses', '4 meses',
		'11 meses', '12 meses', '15 meses', '3 años', '6 años', '12 años',
		'14 años', '18 años', '50 años', '65 años', '> 65 años'];

	echo <<< HTML
	<main class='row'>
	  <section id='contenido' class='borde_verde col-md-9'>
		  <h1> $titulo </h1>
		  <div class='container table-responsive py-5'>
				<table class='table table-bordered table-hover'>
					<tr>
	HTML;
	 
	foreach($cabecera as $cab){
		echo "<th scope='col'>".$cab."</th>";
	}
	echo "</tr>";
}

function cuerpoCalendario($calendario, $vacunas){
	$indice =  ['nombre', '-1', '0', '2', '4', '11', '12', '15', '36', '72',
	'144', '168', '216', '600', '780', '781'];

	$vacOld = 0;
	$fech = 0;
	
	/* VERSIÓN 2 */
	//para cada fila del calendario obtenido
	foreach($calendario as $c){
	
		$vacActual = $c['idvacuna'];
		if($vacActual != $vacOld){
			echo "</tr>";
		}
	
		//recorremos el índice
		foreach($indice as $i){
	
			//si la vacuna es la misma, rellenamos en la misma línea
			if($vacActual == $vacOld){
				//si el mes que estamos mirando es posterior al que ya se ha puesto
				if($i > $fech && $i != 'nombre'){
	
					//si ini <= i <= fin
					if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){
						$acronimo = obtenerAcronimo($vacunas, $c['idvacuna']);
						echo "<th scope='col'>".$acronimo." </th>";
					}
					//si i < ini
					else if($i < $c['meses_ini']){
						echo "<th scope='col'> </th>";
					}
					else if($i > $c['meses_fin']){
						$fech = $c['meses_fin'];
						break;
					}
				}
			}else{
				//si estamos rellenando el nombre, obtenemos el nombre de la vacuna
				if($i == 'nombre'){
					$nombre = obtenerNombreVacuna($vacunas, $c['idvacuna']);
					echo "<th scope='col'>".$nombre."</th>";
				}
				//si ini <= i <= fin
				else if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){
					$acronimo = obtenerAcronimo($vacunas, $c['idvacuna']);
					echo "<th scope='col'>".$acronimo." </th>";
				}
				//si i < ini
				else if($i < $c['meses_ini']){
					echo "<th scope='col'> </th>";
				}
				else if($i > $c['meses_fin']){
					$fech = $c['meses_fin'];
					break;
				}
			}
		}	
		$vacOld = $vacActual;
	}
	echo "</table> </div> </section>";
}
?>