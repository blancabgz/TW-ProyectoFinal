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
            $datos['rol'] = 'P';
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

function buscarPacientes($post){
    $bd = conectarBD();
    $pacientes = "Error en al conectarse a la base de datos.";
    $indice = ['nombre', 'apellidos', 'fecha_ini', 'fecha_fin', 'estado', 'ordenar'];


    if($bd){
        //construimos la consulta
        $consulta = "SELECT * FROM usuarios WHERE rol='P' and";
        $esta = 0;

        for($i = 0; $i < 6 ; $i = $i+1){

            //si está 
            if(isset($post[$indice[$i]]) && $post[$indice[$i]] != ''){
                $hay = ' ';
                $esta = $esta + 1;
                //comprobamos si los campos siguientes tienen valor
                for($j = $i+1; $j < 6; $j= $j+1){
                    if($indice[$j] != 'ordenar' && isset($post[$indice[$j]]) && $post[$indice[$j]] != ''){
                        $hay = 'and';
                    }
                }
                //si es el nombre
                if($indice[$i] == 'nombre'){
                    $consulta .= " ".$indice[$i]." LIKE '%".$post[$indice[$i]]."%' ".$hay." ";
                }

                //si es el apellido
                else if($indice[$i] == 'apellidos'){
                    $consulta .= " ".$indice[$i]." LIKE '%".$post[$indice[$i]]."%' ".$hay." ";
                }
                //si es el intervalo de la fecha
                else if($indice[$i] == 'fecha_ini'){

                    //comprobamos si está definido los meses finales
                    if(isset($post['fecha_fin']) && $post['fecha_fin'] != ''){
                        $consulta .= " fecha BETWEEN ".$post['fecha_ini']." AND ".$post['fecha_fin']." ".$hay." ";
                    }
                    else{
                        $consulta .= " fecha > ".$post['fecha_ini']." ".$hay." ";
                    }
                }
                else if($indice[$i] == 'fecha_fin'){
                    if(!isset($post['fecha_ini'])){
                        $consulta .= " fecha < ".$post['fecha_fin']." ".$hay." ";
                    }
                }
                //si es el estado
                else if($indice[$i] == 'estado'){
                    $consulta .= " ".$indice[$i]." = '".$post[$indice[$i]]."' ".$hay." ";
                }
                else if($indice[$i] == 'ordenar'){
                    $consulta .= "ORDER BY ";
                    if($post[$indice[$i]] == 'NA') $consulta .= " nombre, apellidos;";
                    else if($post[$indice[$i]] == 'YN') $consulta .= " fecha;";
                    else if($post[$indice[$i]] == 'NY') $consulta .= " fecha DESC;";
                }
            }
            else if($indice[$i] == 'ordenar'){
                $consulta .= " ORDER BY nombre, apellidos;";
            }
        }
        if($esta == 0){
            $consulta = "SELECT * FROM usuarios WHERE rol='P';";
        }
        else if($esta == 1 && isset($post['ordenar'])){
            $consulta = "SELECT * FROM usuarios WHERE rol='P' ORDER BY ";
            if($post['ordenar'] == 'NA') $consulta .= " nombre, apellidos;";
            else if($post['ordenar'] == 'YN') $consulta .= " fecha;";
            else if($post['ordenar'] == 'NY') $consulta .= " fecha DESC;";
        }
        
        $consulta_res = mysqli_query($bd, $consulta);

        if($consulta_res){
            if(mysqli_num_rows($consulta_res) > 0){
                $pacientes = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
            }
            else{
                $pacientes = "No se han encontrado pacientes con ese criterio de búsqueda.";
            }
        }else{
            $pacientes = "Error al realizar la consulta.";
        }
    }
    
    return $pacientes;
}

function activarPaciente($dni){
    $bd = conectarBD();
    $activado = "Error en la conexión a la base de datos.";

    if($bd){
        //construimos la consulta
        $consulta = "UPDATE usuarios SET estado='A' WHERE dni='$dni';";
        $consulta_res = mysqli_query($bd, $consulta);

        if($consulta_res){
            $activado = "Paciente activado con éxito.";
        }else{
            $activado = "No se ha encontrado el paciente en la base de datos.";
        }
    }
    return $activado;
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

function log_sistema($descripcion){
    $bd = conectarBD();
    $fecha = date("Y-m-d G:i:s");
    
    if($bd){
        $consulta = "INSERT INTO log (fecha, descripcion) VALUES ('".$fecha."', '".$descripcion."')";
        $consulta_res = mysqli_query($bd, $consulta);
    }
    //return $consulta;
}
?>