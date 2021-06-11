<?php
require_once 'credenciales.php';
require_once 'basedatos.php';

function obtenerCalendarioVacunas(){
    $bd = conectarBD();
    $calendario = 0;

    if($bd){
        $consulta = "SELECT * FROM calendario;";
        $consulta_res = mysqli_query($bd, $consulta);
        
        if($consulta_res){
            if(mysqli_num_rows($consulta_res) > 0){
                $calendario = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
            }
            else{
                $calendario = 1;
            }
        }
        //mysqli_free_results($consulta_res);
        desconectarBD($bd);
    }

    //0: error bd   1: no hay filas
    return $calendario;
}

function obtenerCalendarioIDVacuna($id){
    $bd = conectarBD();

    if($bd){
        $consulta = "SELECT * FROM calendario WHERE idvacuna='$id';";
        $consulta_res = mysqli_query($bd, $consulta);
        $calendario = 0;
        
        if($consulta_res){
            if(mysqli_num_rows($consulta_res) > 0){
                $calendario = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
            }
            else{
                $calendario = 1;
            }
        }
        //mysqli_free_results($consulta_res);
        desconectarBD($bd);
    }
    else{
        $calendario = 'Error al conectarse a la base de datos.';
    }
    //0: error bd   1: no hay filas
    return $calendario;
}

function obtenerCartilla($dni){
    $bd = conectarBD();
    $cartilla = 0;
    
    if($bd){
        //obtenemos el id del usuario
        $consulta = "SELECT id FROM usuarios WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);
        
        //si existe el usuario
        if($consulta_res){

            //obtenemos el dni
            $id = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
            $id = $id['id'];

            //obtenemos su cartilla de vacunacion
            $consulta = "SELECT * FROM vacunacion WHERE idusuario='$id';";
            $consulta_res = mysqli_query($bd, $consulta);

            //obtenemos el array
            if($consulta_res){
                if(mysqli_num_rows($consulta_res) > 0){
                    $cartilla = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
                }
                else{
                    $cartilla = 1;
                }
            }
        }
        //si no existe el usuario
        else{
            $cartilla = 2;
        }

        //mysqli_free_results($consulta_res);
        desconectarBD($bd);
    }

    //0: error bd   1: no hay filas 2: no hay id usuario
    return $cartilla;
}

//recibe el id de la vacuna
function obtenerNombreVacuna($id){
    $bd = conectarBD();
    $nombre = 3;

    if($bd){
        //construimos la consulta
        $consulta = "SELECT nombre FROM vacunas WHERE id=$id;";
        $consulta_res = mysqli_query($bd, $consulta);
        
        if(mysqli_num_rows($consulta_res) > 0){
            $nombre = mysqli_fetch_array($consulta_res);
            $nombre = $nombre['nombre'];
        }
        else{
            $nombre = 1;
        }
    }
    
    return $nombre;
}

//recibe el id de la vacuna
function obtenerAcronimoVacuna($id){
    $bd = conectarBD();
    $acronimo = 3;

    if($bd){
        //construimos la consulta
        $consulta = "SELECT acronimo FROM vacunas WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) > 0){
            $acronimo = mysqli_fetch_array($consulta_res);
            $acronimo = $acronimo['acronimo'];
        }else{
            $acronimo = 1;
        }
    }
    return $acronimo;
}

function obtenerDatosVacunacion($dni, $idcalendario){
    $bd = conectarBD();
    $cartilla = 3;

    if($bd){
        //obtenemos el id del usuario con $dni
        $consulta = "SELECT id FROM usuarios WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);

        //si existe
        if(mysqli_num_rows($consulta_res) > 0){
            $id = mysqli_fetch_array($consulta_res);
            $id = $id['id'];

            //obtenemos la cartilla de dicho usuario
            $consulta = "SELECT fecha, fabricante, comentarios FROM vacunacion WHERE calendario_id='$idcalendario' and idusuario='$id';";
            $consulta_res = mysqli_query($bd, $consulta);
            
            if(mysqli_num_rows($consulta_res) > 0){
                $cartilla = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
            }
            else{
                $cartilla = 2;
            }
        }
        else{
            $cartilla = 1;
        }
    }
    return $cartilla;
}


function borrarVacunaCalendario($id){
    $bd = conectarBD();

    if($bd){
        $consulta = "DELETE FROM calendario WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(!$consulta_res){
            $mensaje = 'No se ha podido eliminar la vacuna del calendario.';
        }
        else{
            $mensaje = "Vacuna eliminada con éxito.";
        }
    }else{
        $mensaje = 'Error al conectarse a la base de datos.';
    }

    return $mensaje;
}

function insertarVacunaCalendario($datos){
    $bd = conectarBD();

    if($bd){
        $id = $datos['idvacuna'];
        $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';
        $indice = ['idvacuna', 'sexo', 'meses_ini', 'meses_fin', 'tipo', 'comentarios'];
        
        $consulta_select = "SELECT nombre FROM vacunas WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta_select);

        //comprobamos si existe la vacuna
        if(mysqli_num_rows($consulta_res) < 0){
            $mensaje = "No se ha realizado la inserción de la vacuna, puesto que no existe dicha vacuna.";
        }
        //si existe la vacuna
        else{
            $consulta = "INSERT INTO calendario (";

            //construimos las columnas a insertar
            foreach($indice as $k){
                if($k == 'comentarios'){
                    if(isset($datos[$k]) && !empty($datos[$k])){
                        $consulta .= $k.") VALUES (";
                    }
                }
                else if($k == 'tipo'){
                    $consulta .= $k.") VALUES (";
                }
                else{
                    $consulta .= $k.",";
                }
            }
            //construimos la consulta con los datos del argumento
            foreach($indice as $k){
                if($k == 'comentarios'){
                    if(isset($datos[$k]) && !empty($datos[$k])){
                        $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."');";
                    }
                }
                else if($k != 'tipo'){
                    $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."', ";
                }
                else{
                    
                    $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."');";
                }
            }

            $consulta_res = mysqli_query($bd, $consulta);
            
            //si ha habido error
            if(!$consulta_res){
                $mensaje = "Error de inserción, vuelva a intentarlo.";
            }
            else{
                $mensaje = "Vacuna añadida al calendario con éxito";
            }
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = "Error al conectarse a la base de datos.";
    }
    return $mensaje;   
}

function obtenerCalendarioID($id){
    $bd = conectarBD();
    $calendario = 2;

    if($bd){
        //construimos la consulta
        $consulta = "SELECT * FROM calendario WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) > 0){
            $calendario = mysqli_fetch_array($consulta_res);
        }else{
            $calendario = 1;
        }
    }
    return $calendario;
}



//obtener el dni a partir del id
function obtenerDNI_ID($id){
    $bd = conectarBD();
    $dni = "Error en la conexión a la base de datos.";

    if($bd){
        //construimos la consulta
        $consulta = "SELECT dni FROM usuarios WHERE id='$id';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $dni = "No existe un usuario con ese identificador.";
        }else{
            $dni = mysqli_fetch_array($consulta_res);
        }
    }
    return $dni;
}

function obtenerID_DNI($dni){
    $bd = conectarBD();
    $id = "Error en la conexión a la base de datos.";

    if($bd){
        //construimos la consulta
        $consulta = "SELECT id FROM usuarios WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $id = "No existe un usuario con ese identificador.";
        }else{
            $id = mysqli_fetch_array($consulta_res);
        }
    }
    return $id;
}

function insertarVacunaCartilla($datos, $dni){

    $bd = conectarBD();
    $mensaje = 'Error en la conexión a la base de datos.';

    if($bd){
        //vamos a comprobar que el usuario existe
        $idusuario = obtenerID_DNI($dni);

        //si no ha habido error
        if(is_array($idusuario)){

            //comprobamos que no existe en la cartilla del usuario la vacuna a insertar 
            $calendario = $datos['calendario_id'];
            $idusuario = $idusuario['id'];
            $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';
            
            $consulta_select = "SELECT id FROM vacunacion WHERE calendario_id='$calendario' and idusuario='$idusuario';";
            $mensaje = $consulta_select;
            $consulta_res = mysqli_query($bd, $consulta_select);

            //si ya hay una vacuna con ese nombre
            if(mysqli_num_rows($consulta_res) > 0){
                $mensaje = "No se ha realizado la inserción de la vacuna en la cartilla, ya existe dicha vacuna en la cartilla.";
            }
            //si no está la vacuna en la cartilla, la añadimos
            else{

                $indice = ['fabricante', 'comentarios', 'fecha'];
                $consulta = "INSERT INTO vacunacion (idusuario, calendario_id, ";

                //construimos las columnas a insertar
                foreach($indice as $k){
                    if($k == 'fecha'){
                        $consulta .= $k.") VALUES (";
                    }else{
                        $consulta .= $k.", ";
                    }
                }

                $consulta .= "'".$idusuario."', ";
                $consulta .= "'".$calendario."', ";
                
                //construimos la consulta con los datos del argumento
                foreach($indice as $k){
                    if($datos[$k] != ""){
                        if($k == 'fecha'){
                            $consulta .="'".$datos[$k]."');";
                        }
                        else{
                            $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."', ";
                        }
                    }
                    else{
                        if($k == 'fecha') $consulta .= "'null');";
                        else $consulta .= " '',";
                    }
                }
                $consulta_res = mysqli_query($bd, $consulta);

                //si ha habido error
                if(!$consulta_res){
                    $mensaje = "Error de inserción, vuelva a intentarlo.";
                }
                else{
                    $mensaje = "Vacuna añadida con éxito.";
                }
            }
        }
        else{
            $mensaje = $id;
        }
    }
    mysqli_close($bd);

    return $mensaje;
}

function obtenerVacunacion($idvacunacion){
    $bd = conectarBD();
    $vacunacion = "Error en la conexión a la base de datos.";

    if($bd){
        //construimos la consulta
        $consulta = "SELECT * FROM vacunacion WHERE id='$idvacunacion';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $vacunacion = "No existe la vacunación.";
        }else{
            $vacunacion = mysqli_fetch_array($consulta_res);
        }
    }
    return $vacunacion;
}

function actualizarVacunacion($datos, $id){
    $bd = conectarBD();
    $mensaje = 'Error al conectarse a la base de datos.';

    if($bd){
        $indice = ['fabricante', 'comentarios', 'fecha'];
        $consulta = "UPDATE vacunacion SET ";

        //construimos las columnas a insertar
        foreach($indice as $k){
            if($k == 'fecha'){
                $consulta .= " ".$k." = '".$datos[$k]."' ";
            }
            else{
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
            $mensaje = "Vacunación actualizada con éxito.";
        }
        mysqli_close($bd);
    }

    return $mensaje;
}

function eliminarVacunacion($id){
    $bd = conectarBD();
    $mensaje = 'Error al conectarse a la base de datos.';

    if($bd){
        $consulta = "SELECT id FROM vacunacion WHERE id='$id' ";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $mensaje = 'Error al eliminar, no existe la vacunación en la base de datos.';
        }
        else{
            $consulta = "DELETE FROM vacunacion WHERE id='$id';";
            $consulta_res = mysqli_query($bd, $consulta);

            //si ha habido error
            if(!$consulta_res){
                $mensaje = "Error al eliminar, vuelva a intentarlo.";
            }
            else{
                $mensaje = "Vacunación eliminada con éxito.";
            }
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = 'Error al conectarse a la base de datos.';
    }

    return $mensaje;
}
?>