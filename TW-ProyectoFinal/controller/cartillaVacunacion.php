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

    //se muestra el cuerpo del calendario
    cuerpoCartilla($calendario, $totalVacunas, $cartilla);//, $vacunasPropias);


    HTMLformulario($rol);
    HTMLfooter();
}else{
    header("Location: ../view/inicio.php");
}


function cuerpoCartilla($calendario, $vacunas, $cartilla){
	$indice =  ['nombre', '-1', '0', '2', '4', '11', '12', '15', '36', '72',
	'144', '168', '216', '600', '780', '781'];

	$vacOld = 0;
	$fech = 0;

	//para cada fila del calendario obtenido
	foreach($calendario as $c){
	
        //si estamos añadiendo una vacuna diferente, cambiamos de fila
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

                        //si la vacuna está en la cartilla
                        if(estaCartilla($c['id'], $cartilla)){

                            //se pone de un color (marroncito, por ejemplo)
                            echo "<th scope='col' style='background-color: #BDB76B'>".$acronimo." </th>";    
                        }
                        //si no está, se pone de otro color
                        else echo "<th scope='col' style='background-color: #FFA07A'>".$acronimo." </th>";
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

                    //si la vacuna está en la cartilla
                    if(estaCartilla($c['id'], $cartilla)){

                        //se pone de un color (marroncito, por ejemplo)
                        echo "<th scope='col' style='background-color: #BDB76B'>".$acronimo." </th>";    
                    }
                    //si no está, se pone de otro color
                    else echo "<th scope='col' style='background-color: #FFA07A'>".$acronimo." </th>";
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

//función para ver si una vacuna está en la cartilla de un paciente
function estaCartilla($id, $lista){

    $esta = false;

    foreach($lista as $l){
        if($l['calendario_id'] == $id){
            $esta = true;
        }
    }
    return $esta;
}
?>