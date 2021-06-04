<?php
require_once 'credenciales.php';

/* CONEXIÓN Y DESCONEXIÓN DE LA BASE DE DATOS*/
// Conexión a la BBDD
function conectarBD(){
    $bd = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);
    $dato = 1;
    
    if(!$bd)
        $dato = 0;

    mysqli_set_charset($bd,"utf8");

    //dato: 0 -> error; dato: 1 -> se ha conectado
    return $bd;
}

// Desconexión de la BBDD
function desconectarBD($bd) {
    mysqli_close($bd);
}

/* LOGIN */
//Comprobación de los datos
function comprobarDatos($dni, $clave){
    $bd = conectarBD();

    //si se ha conectado a la bd, realizamos la consulta
    if($bd){

        $consulta = "SELECT dni, clave, estado FROM usuarios where dni='$dni'";
        $consulta_res = mysqli_query($bd, $consulta);
        $res = 0;

        if(!empty($consulta_res) && mysqli_num_rows($consulta_res) > 0){
            $valores = mysqli_fetch_array($consulta_res);
        
            if($valores['estado'] != 'I'){
                if(password_verify($clave, $valores['clave'])){
                    $res = 1;
                }
            }
            /*if($clave == '123456'){
                $res = true;
            }*/
        }

        mysqli_free_result($consulta_res);
        mysqli_close($bd);
    }
    else{
        $res = 2;
    }

    //0: error de identif. ; 1: identificado ; 2: error bd
    return $res;
}

/* GESTIÓN DEL USUARIO */
function obtenerTipoUsuario($dni){
    $usuario = strip_tags($dni);
    $usuario = addslashes($dni);

    $bd = conectarBD();
    $consulta = "SELECT rol FROM usuarios where dni='$usuario'";
    $consulta_res = mysqli_query($bd, $consulta);
    $rol = 'V';

    if(mysqli_num_rows($consulta_res) > 0){
        $rol = mysqli_fetch_array($consulta_res);
        $rol = $rol['rol'];
    }
    
    mysqli_free_result($consulta_res);
    mysqli_close($bd);
    return $rol;
}

function insertarUsuario($datos, $user){
    $bd = conectarBD();
    
    if($bd){
        //si hay fotografía se inserta
        if(isset($datos['fotografia']) && $datos['fotografia'] != ''){
            $indice = ['fotografia', 'dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
        }
        //si no, no se añade
        else{
            $indice = ['dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
        }

        //si el usuario es visitante, el estado se marca a I (inactivo)
        if($user == 'V'){
            $datos['estado'] = 'I';
        }

        $dni = $datos['dni'];
        $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';

        //comprobamos que el DNI no es nulo y que no existe ya en la bd
        if($dni != null){
            $consulta_select = "SELECT dni FROM usuarios WHERE dni='$dni';";
            $consulta_res = mysqli_query($bd, $consulta_select);

            //si ya hay un usuario
            if(mysqli_num_rows($consulta_res) > 0){
                $mensaje = "No se ha realizado la inserción del usuario, ya existe uno con ese DNI.";
            }
            //si no hay ningún usuario con ese dni lo añadimos a la bd
            else{
                $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
                $consulta = "INSERT INTO usuarios (";

                //construimos las columnas a insertar
                foreach($indice as $k){
                    if($k == 'clave'){
                        $consulta .= $k.") VALUES (";
                    }else{
                        $consulta .= $k.",";
                    }

                }
                //construimos la consulta con los datos del argumento
                foreach($indice as $k){
                    if($datos[$k] != ""){
                        if($k == 'clave'){
                            $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."');";
                        }else{
                            $consulta .="'".mysqli_real_escape_string($bd,$datos[$k])."',";
                        }
                    }
                    else{
                        if($k == 'fecha'){
                            $consulta .= " null,";
                        }else{
                            $consulta .= " '',";
                        }  
                    }
                }

                $consulta_res = mysqli_query($bd, $consulta);

                //si ha habido error
                if(!$consulta_res){
                    $mensaje = "Error de inserción, vuelva a intentarlo.";
                }
                else{
                    $mensaje = "Usuario añadido con éxito";

                    if($user == 'V'){
                        $mensaje = "D/Dª ".$datos['nombre']." ".$datos['apellidos']." su solicitud ha quedado registrada.
                        Próximamente recibirá un email confirmando su inserción en el sistema si los datos
                        que ha proporcionado son correctos. En caso de que no podamos verificar sus datos, se enviará
                        un email a la dirección proporcionada informándole de ese hecho.";
                    }
                }
            }
        }else{
            $mensaje = "El DNI no puede ser nulo.";
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = "Error al conectarse a la base de datos.";
    }
    
    //0: éxito;   1: dni existe     2: error inserción      3: dni null
    return $mensaje;    
}

function actualizarUsuario($datos, $user, $dniOld){
    $bd = conectarBD();

    if($bd){
        if(isset($datos['fotografia']) && $datos['fotografia'] != ''){
            $indice = ['fotografia', 'dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
        }
        else{
            $indice = ['dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
        }

        if($user == 'P' || $user == 'S'){
            $indice = ['email', 'telefono', 'clave'];
        }

        $dni = $datos['dni'];
        $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';

        //comprobamos que el DNI no es nulo y que existe ya en la bd
        if($dni != null){
            $consulta_select = "SELECT nombre FROM usuarios WHERE dni='$dni';";        
            $consulta_res = mysqli_query($bd, $consulta_select);

            //si ya hay un usuario
            if(mysqli_num_rows($consulta_res) < 0){
                $mensaje = "No se ha realizado la actualización del usuario, ya existe uno con dicho DNI.";
            }
            //si no hay ningún usuario con ese dni lo añadimos a la bd
            else{
                $consulta = "UPDATE usuarios SET ";
                if(strlen($datos['clave']) < 60)
                    $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);

                //construimos la consulta con los datos del argumento
                foreach($indice as $k){
                    //si tiene información
                    if($datos[$k] != ""){
                        //si es clave es el final, con lo que no se pone la coma (,)
                        if($k == 'clave'){
                            $consulta .= " ".$k." = '".$datos[$k]."' ";
                        }
                        else{
                            $consulta .= " ".$k." = '".mysqli_real_escape_string($bd,$datos[$k])."',";
                        
                        }
                    }
                    //si está vacío se escribe '', si está vacío y es una fecha, se pone a null
                    else{
                        if($k == 'fecha'){
                            $consulta .= " ".$k." = null,";
                        }else{
                            $consulta .= " ".$k." = '',";
                        }  
                    }
                }

                $consulta .= "WHERE dni='".$dniOld."';";

                $consulta_res = mysqli_query($bd, $consulta);
                //si ha habido error
                if(!$consulta_res){
                    $mensaje = "Error de actualización, vuelva a intentarlo.";
                }
                else{
                    $mensaje = "Usuario actualizado con éxito";
                }
            }
        }else{
            $mensaje = "El DNI no puede ser nulo.";
        }
        mysqli_close($bd);
    }
    else{
        $mensaje = "Error al conectarse a la base de datos.";
    }
    
    return $mensaje;    
}


function obtenerDatosUsuario($dni){
    $bd = conectarBD();

    if($bd){
        $consulta = "SELECT * FROM usuarios WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $datos = "No hay datos para dicho usuario.";
        }
        else{
            $datos = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
        }

        mysqli_close($bd);
    }else{
        $datos = "Error al conectarse a la base de datos.";
    }

    return $datos;
}

function borrarUsuario($dni){
    $bd = conectarBD();

    if($bd){
        $consulta = "DELETE FROM usuarios WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);

        if(!$consulta_res){
            $mensaje = 'No se ha podido eliminar el usuario con DNI'.$dni.'.';
        }
        else{
            $mensaje = "Usuario eliminado con éxito.";
        }
    }
    else{
        $mensaje = "Error al conectarse a la base de datos.";
    }

    return $mensaje;
}

function obtenerListado(){
    $bd = conectarBD();
    $listado = 0;

    if($bd){
        $consulta = "SELECT dni, fotografia, nombre, apellidos, email, estado, rol FROM usuarios";
        $consulta_res = mysqli_query($bd, $consulta);
        $listado = 0;
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
?>