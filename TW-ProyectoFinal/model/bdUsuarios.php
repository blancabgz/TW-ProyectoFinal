<?php
require_once 'credenciales.php';
require_once 'basedatos.php';

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
?>