<?php
require_once "../controller/check_login.php";
require_once "../view/vistasComunes.php";
require_once "../view/vistasCal-Cart.php";
$titulo="Calendario de vacunación Andalucia 2020";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//se obtienen las vacunas y el calendario
$calendario = obtenerCalendarioVacunas();

//comprobamos que no ha habido error
if($calendario == 0){
    mensaje($titulo, "Error al conectarse a la base de datos.");
}
else if($calendario == 1){
    mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.");
}
else{
	//si el usuario es el administrador, puede añadir una vacuna al calendario
	if($rol == 'A'){
		$form = '../controller/addVacunaCalendario.php';
		$name = 'vacunaCalendario';
		$value = 'Añadir vacuna al calendario';
		botonAddVacunaCalendario($titulo, $form, $name, $value, '');
	}

    //se muestra la cabecera del calendario
    cabeceraCalendario($titulo, $rol);
    
    //se muestra el cuerpo del calendario
    cuerpoCalendario($calendario, $rol);
}

HTMLformulario($rol);
HTMLfooter();



//cuerpo del calendario
function cuerpoCalendario($calendario, $rol){
	$indice =  ['nombre', '-1', '0', '2', '4', '11', '12', '15',
	'36', '72', '144', '168', '216', '600', '780', '781'];

	$vacOld = 0;
	$fech = 0;
	
	//para cada fila del calendario obtenido
	foreach($calendario as $c){
	
		$vacActual = $c['idvacuna'];
		if($vacActual != $vacOld){
			finFila();
		}
	
		//recorremos el índice
		foreach($indice as $i){
	
			//si la vacuna es la misma, rellenamos en la misma línea
			if($vacActual == $vacOld){

				//si el mes que estamos mirando es posterior al que ya se ha puesto
				if($i > $fech && $i != 'nombre'){
	
					//si ini <= i <= fin, se muesta la celda con el acrónimo
					if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){

                        //obtenemos el acronimo
						$acronimo = obtenerAcronimoVacuna($c['idvacuna']);
                        celdaCalendario($acronimo, $c['id'], $c['idvacuna'], $c['sexo'], $c['tipo'], $c['comentarios'], 'n', $rol);
					}
					//si i < ini, se deja la celda vacía
					else if($i < $c['meses_ini']){
                        celdaVacia();
					}
					else if($i > $c['meses_fin']){
						$fech = $c['meses_fin'];
						break;
					}
				}
			}
			else{
				//si estamos rellenando el nombre, obtenemos el nombre de la vacuna
				if($i == 'nombre'){
					$nombre = obtenerNombreVacuna($c['idvacuna']);
					celdaNombre($nombre);
				}
				//si ini <= i <= fin
				else if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){
                    //obtenemos el acronimo
                    $acronimo = obtenerAcronimoVacuna($c['idvacuna']);
                    celdaCalendario($acronimo, $c['id'], $c['idvacuna'], $c['sexo'], $c['tipo'], $c['comentarios'], 'n', $rol);           
				}
				//si i < ini
				else if($i < $c['meses_ini']){
					celdaVacia();
				}
				else if($i > $c['meses_fin']){
					$fech = $c['meses_fin'];
					break;
				}
			}
		}	
		$vacOld = $vacActual;
	}
    finTabla();
}
?>