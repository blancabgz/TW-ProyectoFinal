<?php
function procesarDatos($datos){
    $campos = [];
    $indice = ['fotografia', 'nombre', 'apellidos', 'dni', 'email', 'telefono', 'fecha', 'sexo', 'clave', 'clave2', 'rol', 'estado'];
    
    //copiamos los valores de los campos campos
    foreach($indice as $k){
        if(isset($datos[$k])){
            $campos[$k] = strip_tags($datos[$k]);
            $campos[$k] = addslashes($datos[$k]);
        }
        else{
            $campos[$k] = '';
        }
    }
    return $campos;
}

function validarNombre($nombre, $validar){
    if($nombre!= null){
        if(!preg_match('/^([a-zA-ZñÑ.]+\s*)*$/', $nombre)){
            $validar[] = "El nombre debe componerse de letras o puntos para abreviación.";
        }
        if(strlen($nombre) > 20){
            $validar[] = "El nombre debe tener menos de 20 caracteres.";
        }
    }
    return $validar;
}

function validarApellidos($apellidos, $validar){
    if($apellidos != null){
        if(!preg_match('/^([a-zA-ZñÑ.]+\s*)*$/', $apellidos)){
            $validar[] = "Los apellidos debe componerse de letras o puntos para abreviación.";
        }
        if(strlen($apellidos) > 20){
            $validar[] = "Los nombre debe tener menos de 20 caracteres.";
        }
    }
    return $validar;
}

function validarDNI($dni, $validar){
    if($dni != null){
        if(!preg_match('/^[0-9]{8}[A-Z]{1}$/', $dni)){
            $validar[] = "El DNI debe componerse de 8 números y 1 caracter.";
        }
    }
    return $validar;
}

function validarEmail($email, $validar){
    if($email != null){
        if(!preg_match('/^[A-z0-9._-]+@[A-z0-9][A-z0-9-]+(\.[A-z0-9_-]+)+$/', $email)){
            $validar[] = "El email debe seguir el patrón xxx@yyy.zzz .";
        }
        if(strlen($email) > 40){
            $validar[] = "El email debe tener menos de 40 caracteres.";
        }
    }
    return $validar;
}

function validarTelf($telf, $validar){
    if($telf != null){
        if(!preg_match('/^(\(\+[0-9]{2}\))?\s*[0-9]{3}\s+[0-9]{6}$/', $telf)){
            $validar[] = "El teléfono debe seguir el patrón (+xx) xxx xxxxxx .";
        }
    }
    return $validar;
}

function validarFecha($fec, $validar){
    if(isset($fec) && $fec!=null){
        $fecha = explode('-', $fec);    //fecha aaaa-mm-dd
        $actual = date('Y-m-d', time());            //
        $actual = explode('-', $actual);
        
        
        //fecha válida
        if(!checkdate($fecha[1], $fecha[2], $fecha[0])){
            $validar[] = "La fecha debe ser válida";
        }
        
        //fecha posterior a 1/1/90 y a la fecha actual
        if($fecha[0] < 1900 || $fecha[0] > $actual[0]){
            $validar[] = "El año no puede ser anterior a 1900 o posterior a ".$actual[0].".";
        }
        elseif($fecha[0]==$actual[0]){
            if($fecha[1] > $actual[1]){
                $validar[] = "El mes no puede ser posterior a ".$actual[1]."-".$actual[0].".";
            }
            elseif($fecha[1] == $actual[1]){
                if($fecha[2] > $actual[2]){
                    $validar[] = "La fecha no puede ser posterior a la actual: ".$actual[2]."-".$actual[1]."-".$actual[0].".";
                }
            }
        }
    }
    return $validar;
}

function validarSexo($sexo, $validar){
    if($sexo != NULL){
        if($sexo != 'M' && $sexo != 'H'){
            $validar[] = "El sexo debe ser Hombre o Mujer.";
        }
    }
    return $validar;
}

function validarClave($clave, $clave2,  $validar){
    if($clave != NULL && $clave2 != NULL){
        if(strlen($clave) < 6 || strlen($clave2) < 6){
            $validar[] = "La contraseña debe tener al menos 6 caracteres.";
        }

        if($clave != $clave2){
            $validar[] = "Las claves deben coincidir.";
        }
    }
    return $validar;
}

function validarRol($rol, $validar){
    if($rol != NULL){
        if($rol != 'A' && $rol != 'P' && $rol != 'S'){
            $validar[] = "El rol debe ser Administrador, Paciente o Sanitario.";
        }
    }
    return $validar;
}

function validarEstado($estado, $validar){
    if($estado != NULL){
        if($estado != 'I' && $estado != 'A'){
            $validar[] = "El estado debe ser Inactivo o Activo.";
        }
    }
    return $validar;
}

function validarDatos($datos, $user){

    $campos = procesarDatos($datos);
    $validar = [];
    $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2', 'estado', 'rol'];
    //if($user == 'c') $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2'];
    if($user == 'V') $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2', 'rol'];
    
    //validamos si alguno está vacío
    foreach($nonulos as $k){
        if(isset($campos[$k]) && $campos[$k] == null){
            $validar[] = "El campo ".$k." no puede ser nulo.";
        }
    }
    $validar = validarNombre($campos['nombre'], $validar);
    $validar = validarApellidos($campos['apellidos'], $validar);
    $validar = validarDNI($campos['dni'], $validar);
    $validar = validarEmail($campos['email'], $validar);
    $validar = validarTelf($campos['telefono'], $validar);
    $validar = validarFecha($campos['fecha'], $validar);
    $validar = validarSexo($campos['sexo'], $validar);
    $validar = validarClave($campos['clave'], $campos['clave2'], $validar);
    $validar = validarRol($campos['rol'], $validar, $user);

    if($user != 'V'){
        $validar = validarEstado($campos['estado'], $validar, $user);
    }
    
    return $validar;
}
?>