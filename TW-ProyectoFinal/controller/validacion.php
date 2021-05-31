<?php

/* PROCESAMIENTO Y VALIDACIÓN DE USUARIOS */
function procesarDatos($datos){
    $campos = [];
    $indice = ['nombre', 'apellidos', 'dni', 'email', 'telefono', 'fecha', 'sexo', 'clave', 'clave2', 'rol', 'estado'];
    
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

    //procesamos la imagen
    $campos['fotografia'] = '';

    //si fotografia existe
    if(isset($datos['fotografia'])){
        
        //si es el nombre de la imagen la codificamos
        if(preg_match("/\/tmp\/(\d*)/", $datos['fotografia'])){
            $campos['fotografia'] = base64_encode(file_get_contents($datos['fotografia']));
        }
        //si no es el nombre de la imagen, vemos si está vacía o ya recoge el contenido de la imagen
        else{
            if($datos['fotografia'] == ''){
                $campos['fotografia'] = '';
            }else $campos['fotografia'] = $datos['fotografia'];
        }
    }
    //si foto existe, le asignamos el valor a fotografia
    else if(isset($datos['foto'])){
        $campos['fotografia'] = $datos['foto'];
    }
    
    return $campos;
}

function procesarFotografia(){
    //si se ha insertado imagen
    if(isset($_FILES['fotografia']['tmp_name']) && !empty($_FILES['fotografia']['tmp_name'])){

        //$_POST['fotografia] toma el nombre de la imagen
        $_POST['fotografia'] = $_FILES["fotografia"]["tmp_name"];
    }
    //si se viene de un formulario con la imagen ya puesta
    else if(isset($_POST['foto'])){
        $_POST['fotografia'] = $_POST['foto'];
    }
    //si no se ha insertado imagen
    else{
        //vemos si $_POST['fotografia] tiene valor
        if(!isset($_POST['fotografia'])){
            $_POST['fotografia'] = '';
        }
    }
}

function validarNombre($nombre, $validar){
    if($nombre!= null){
        if(!preg_match('/^([a-zA-ZñÑ.]+\s*)*$/', $nombre)){
            $validar[] = "El nombre debe componerse de letras o puntos para abreviación.";
        }
        if(strlen($nombre) > 50){
            $validar[] = "El nombre debe tener menos de 50 caracteres.";
        }
    }
    return $validar;
}

function validarApellidos($apellidos, $validar){
    if($apellidos != null){
        if(!preg_match('/^([a-zA-ZñÑ.]+\s*)*$/', $apellidos)){
            $validar[] = "Los apellidos debe componerse de letras o puntos para abreviación.";
        }
        if(strlen($apellidos) > 100){
            $validar[] = "Los nombre debe tener menos de 100 caracteres.";
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
        if(strlen($email) > 200){
            $validar[] = "El email debe tener menos de 200 caracteres.";
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

    $campos = $datos;
    $validar = [];
    $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2', 'estado', 'rol'];
    if($user == 'V') $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2'];
    if($user == 'P' || $user == 'S') $nonulos = ['dni', 'nombre', 'apellidos', 'sexo', 'clave', 'clave2'];

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
    
    if($user == 'A'){
        $validar = validarRol($campos['rol'], $validar, $user);
        $validar = validarEstado($campos['estado'], $validar, $user);
    }
    
    return $validar;
}

/* PROCESAMIENTO Y VALIDACIÓN DE VACUNAS */
function procesarDatosVacuna($datos){
    
    $campos = [];
    $indice = ['nombre', 'acronimo', 'descripcion'];
    
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

function validarNombreVac($nombre, $validar){

    if($nombre == ''){
        $validar[] = "El nombre no puede ser nulo.";
    }

    if(strlen($nombre) > 100){
        $validar[] = "El nombre debe tener menos de 100 caracteres.";
    }

    return $validar;
}

function validarAcronimo($acronimo, $validar){

    if($acronimo == ''){
        $validar[] = "El acronimo no puede ser nulo.";
    }

    if(strlen($acronimo) > 15){
        $validar[] = "El acronimo debe tener menos de 15 caracteres.";
    }

    return $validar;
}

function validarDatosVacuna($datos){
    $validar = [];

    $validar = validarNombreVac($datos['nombre'], $validar);
    $validar = validarAcronimo($datos['acronimo'], $validar);

    return $validar;
}
?>