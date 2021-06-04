<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Cartilla de Vacunación";

if($rol == 'P' || $rol == 'S' || $rol == 'A'){
    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //se obtienen las vacunas y el calendario
    $calendario = obtenerCalendarioVacunas();
    $cartilla = obtenerCartilla($_SESSION['usuario']);

    //comprobamos que no ha habido error
    if($calendario == 0 || $cartilla == 0){
        mensaje($titulo, "Error al conectarse a la base de datos.");
    }
    else if($calendario == 1 || $cartilla == 1){
        mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.");
    }
    else if($cartilla == 2){
        mensaje($titulo, "No se ha encontrado el usuario con el identificador establecido.");
    }
    //si no ha habido error, mostramos la cartilla de vacunación
    else{
        //se muestra la cabecera del calendario
        cabeceraCalendario($titulo, $rol);
        
        //se muestra el cuerpo del calendario
        cuerpoCartilla($calendario, $cartilla);
    }
    
    HTMLformulario($rol);
    HTMLfooter();
}
else{
    header("Location: ../view/inicio.php");
}


function estaCartilla($id, $lista){

    $esta = false;

    foreach($lista as $l){
        if($l['calendario_id'] == $id){
            $esta = true;
        }
    }
    return $esta;
}

function cuerpoCartilla($calendario, $cartilla){
    
    $indice =  ['nombre', '-1', '0', '2', '4', '11', '12', '15', '36', '72',
    '144', '168', '216', '600', '780', '781'];

    $vacOld = 0;
    $fech = 0;

    //para cada fila del calendario obtenido
    foreach($calendario as $c){
    
        //si estamos añadiendo una vacuna diferente, cambiamos de fila
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
    
                    //si ini <= i <= fin
                    if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){
                        $acronimo = obtenerAcronimoVacuna($c['idvacuna']);

                        //si la vacuna está en la cartilla
                        if(estaCartilla($c['id'], $cartilla)){
                            celdaCartilla($acronimo, $c['id'], $c['idvacuna']);
                        }
                        //si no está, se pone de otro color
                        else{
                            //obtenemos el acronimo
                            $acronimo = obtenerAcronimoVacuna($c['idvacuna']);
                            celdaCalendario($acronimo, $c['id'], $c['idvacuna'], $c['sexo'], $c['tipo'], $c['comentarios'], 'y');
                        }
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
            }else{
                //si estamos rellenando el nombre, obtenemos el nombre de la vacuna
                if($i == 'nombre'){
                    $nombre = obtenerNombreVacuna($c['idvacuna']);
                    celdaNombre($nombre);
                }
                //si ini <= i <= fin
                else if($i >= $c['meses_ini'] && $i <= $c['meses_fin']){
                    $acronimo = obtenerAcronimoVacuna($c['idvacuna']);

                    //si la vacuna está en la cartilla
                    if(estaCartilla($c['id'], $cartilla)){
                        celdaCartilla($acronimo, $c['id'], $c['idvacuna']);
                    }
                    //si no está, se pone de otro color
                    else{
                        celdaCalendario($acronimo, $c['id'], $c['idvacuna'], $c['sexo'], $c['tipo'], $c['comentarios'], 'y');
                    }
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
    
    finFila();
}

?>