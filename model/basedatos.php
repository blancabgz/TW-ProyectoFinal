<?php
require_once 'credenciales.php';

// Conexión a la BBDD
function conectarBD(){
    $bd = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);
    if(!$bd)
        return "Error al conectarse a la base de datos(".mysqli_connect_errno()."): ".mysqli_connect_error();

    mysqli_set_charset($bd,"utf8");

    return $bd;
}

// Desconexión de la BBDD
function desconectarBD($bd) {
    mysqli_close($bd);
}

//Comprobación de los datos
function comprobarDatos($dni, $clave){
    $bd = conectarBD();
    $consulta = "SELECT dni, clave, estado FROM usuarios where dni='$dni'";
    $consulta_res = mysqli_query($bd, $consulta);
    $res = false;

    if(mysqli_num_rows($consulta_res) > 0){
        $valores = mysqli_fetch_array($consulta_res);
    
        if($valores['estado'] != 'I'){
            if(password_verify($clave, $valores['clave'])){
                $res = true;
            }
        }
    }

    mysqli_free_result($consulta_res);
    mysqli_close($bd);

    return $res;
}

function obtenerTipoUsuario($dni){
    $usuario = strip_tags($dni);
    $usuario = addslashes($dni);

    $bd = conectarBD();
    $consulta = "SELECT rol FROM usuarios where dni='$usuario'";
    $consulta_res = mysqli_query($bd, $consulta);
    $rol = '';
    if(mysqli_num_rows($consulta_res) > 0){
        $rol = mysqli_fetch_array($consulta_res);
        $rol = $rol['rol'];
    }else{
        $rol = '0';
    }
    
    mysqli_free_result($consulta_res);
    mysqli_close($bd);
    return $rol;
}

function insertarUsuario($datos, $user){
    $bd = conectarBD();
    
    /*if(isset($datos['foto'])){
        $datos['fotografia'] = $datos['foto'];
    }*/

    if(isset($datos['fotografia']) && $datos['fotografia'] != ''){
        $indice = ['fotografia', 'dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
    }
    else{
        $indice = ['dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
    }

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
            }
        }
    }else{
        $mensaje = "El DNI no puede ser nulo.";
    }
    mysqli_close($bd);
    
    //0: éxito;   1: dni existe     2: error inserción      3: dni null
    return $mensaje;    
}

function actualizarUsuario($datos, $user){
    $bd = conectarBD();

    if(isset($datos['fotografia']) && $datos['fotografia'] != ''){
        $indice = ['fotografia', 'dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
    }
    else{
        $indice = ['dni', 'nombre', 'apellidos', 'email', 'fecha', 'sexo', 'telefono', 'rol', 'estado', 'clave'];
    }

    if($user == 'P' || $user == 'S'){
        $indice = ['email', 'telefono', 'fotografia','clave'];
    }
    $dni = $datos['dni'];
    $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';

    //comprobamos que el DNI no es nulo y que existe ya en la bd
    if($dni != null){
        $consulta_select = "SELECT dni FROM usuarios WHERE dni='$dni';";        
        $consulta_res = mysqli_query($bd, $consulta_select);

        //si ya hay un usuario
        if(mysqli_num_rows($consulta_res) < 0){
            $mensaje = "No se ha realizado la actualización del usuario, no existe uno con dicho DNI.";
        }
        //si no hay ningún usuario con ese dni lo añadimos a la bd
        else{
            $consulta = "UPDATE usuarios SET ";
            $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
            
            //construimos la consulta con los datos del argumento
            foreach($indice as $k){
                //si tiene información
                if($datos[$k] != ""){
                    //si es clave es el final, con lo que no se pone la coma (,)
                    if($k == 'clave'){
                        $consulta .= " ".$k." = '".mysqli_real_escape_string($bd,$datos[$k])."' ";
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
            $consulta .= "WHERE dni='".$dni."';";
            
            $consulta_res = mysqli_query($bd, $consulta);
            //si ha habido error
            if(!$consulta_res){
                //$mensaje = $consulta_res;
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
    
    return $mensaje;    
}

function obtenerDatosUsuario($dni){
    $bd = conectarBD();
    $consulta = "SELECT * FROM usuarios WHERE dni='$dni';";
    $consulta_res = mysqli_query($bd, $consulta);

    if(mysqli_num_rows($consulta_res) < 0){
        $datos = 1;
    }
    else{
        $datos = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
    }

    return $datos;
}

function borrarUsuario($dni){
    $bd = conectarBD();
    $consulta = "DELETE FROM usuarios WHERE dni='$dni';";
    $consulta_res = mysqli_query($bd, $consulta);

    if(!$consulta_res){
        $mensaje = 'No se ha podido eliminar el usuario con DNI'.$dni.'.';
    }
    else{
        $mensaje = "Usuario eliminado con éxito.";
    }


    return $mensaje;
}

function obtenerListado(){
    $bd = conectarBD();

    $consulta = "SELECT nombre, apellidos, email, dni FROM usuarios";
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

    //0: error bd   1: no hay filas
    return $listado;
}
?>