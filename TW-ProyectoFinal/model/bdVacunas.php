<?php
require_once 'credenciales.php';
require_once 'basedatos.php';

/* GESTIÓN DE LAS VACUNAS */
function obtenerListadoVacunas(){
    $bd = conectarBD();
    $listado = 0;

    if($bd){
        $consulta = "SELECT id, nombre, acronimo FROM vacunas";
        $consulta_res = mysqli_query($bd, $consulta);
        
        if($consulta_res){
            if(mysqli_num_rows($consulta_res) > 0){
                $listado = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
            }
            else{
                $listado = 1;
            }
        }
        //mysqli_free_results($consulta_res);
        desconectarBD($bd);
    }
    //0: error bd   1: no hay filas
    return $listado;
}

function obtenerDatosVacuna($id){
    $bd = conectarBD();

    if($bd){
        $consulta = "SELECT * FROM vacunas WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $datos = "Error en la consulta.";
        }
        else{
            $datos = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
        }
    }
    else{
        $datos = "Error en la conexión con la base de datos.";
    }

    return $datos;
}

function insertarVacuna($datos){
    $bd = conectarBD();

    if($bd){
        $nombre = $datos['nombre'];
        $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';
        $indice = ['nombre', 'acronimo', 'descripcion'];
        
        $consulta_select = "SELECT nombre FROM vacunas WHERE nombre='$nombre';";
        $consulta_res = mysqli_query($bd, $consulta_select);

        //si ya hay una vacuna con ese nombre
        if(mysqli_num_rows($consulta_res) > 0){
            $mensaje = "No se ha realizado la inserción de la vacuna, ya existe dicha vacuna en el sistema.";
        }
        //si no hay ninguna vacuna la añadimos a la bd
        else{
            $consulta = "INSERT INTO vacunas (";

            //construimos las columnas a insertar
            foreach($indice as $k){
                if($k == 'descripcion'){
                    $consulta .= $k.") VALUES (";
                }else{
                    $consulta .= $k.",";
                }
            }
            //construimos la consulta con los datos del argumento
            foreach($indice as $k){
                if($datos[$k] != ""){
                    if($k == 'descripcion'){
                        $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."');";
                    }else{
                        $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."',";
                    }
                }
                else{
                    if($k == 'descripcion') $consulta .= "'');";
                    else $consulta .= " '',";
                }
            }

            $consulta_res = mysqli_query($bd, $consulta);

            //si ha habido error
            if(!$consulta_res){
                $mensaje = "Error de inserción, vuelva a intentarlo.";
            }
            else{
                $mensaje = "Vacuna añadida con éxito";
            }
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = "Error al conectarse a la base de datos.";
    }
    return $mensaje;   
}

function actualizarVacuna($datos, $id){
    $bd = conectarBD();

    if($bd){
        $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';
        $indice = ['nombre', 'acronimo', 'descripcion'];
        $nombre = $datos['nombre']; $acron = $datos['acronimo'];
        $consulta = "SELECT nombre, acronimo FROM vacunas WHERE id!='$id' AND (nombre='$nombre' OR acronimo='$acron');";
        $consulta_res = mysqli_query($bd, $consulta);

        //si ya hay una vacuna con ese nombre o acrónimo
        if(mysqli_num_rows($consulta_res) > 0){
            $mensaje = "No se ha realizado la inserción de la vacuna, ya existe una vacuna con dicho nombre o acrónimo en el sistema.";
        }
        //si no hay ninguna vacuna la añadimos a la bd
        else{
            $consulta = "UPDATE vacunas SET ";

            //construimos las columnas a insertar
            foreach($indice as $k){
                if($k == 'descripcion'){
                    $consulta .= " ".$k." = '".mysqli_real_escape_string($bd,$datos[$k])."' ";
                }else{
                    $consulta .= " ".$k." = '".mysqli_real_escape_string($bd,$datos[$k])."',";
                }   
            }
            $consulta .= "WHERE id='".$id."';";

            $consulta_res = mysqli_query($bd, $consulta);

            //si ha habido error
            if(!$consulta_res){
                $mensaje = "Error de actualización, vuelva a intentarlo.";
            }
            else{
                $mensaje = "Vacuna actualizada con éxito";
            }
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = 'Error al conectarse a la base de datos.';
    }
    return $mensaje;
}

function borrarVacuna($id){
    $bd = conectarBD();

    if($bd){
        $consulta = "DELETE FROM vacunas WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(!$consulta_res){
            $mensaje = 'No se ha podido eliminar la vacuna.';
        }
        else{
            $mensaje = "Vacuna eliminada con éxito.";
        }
    }else{
        $mensaje = 'Error al conectarse a la base de datos.';
    }

    return $mensaje;
}
?>