<?php
//comprobamos hay una sesión iniciada
if(session_status()==PHP_SESSION_NONE)
    session_start();

require_once '../view/vistasHTML.php';
require_once '../model/basedatos.php';

HTMLinicio("Login");
HTMLheader("Login");
HTMLnav('V');

//se comprueba si los datos introducidos son correctos
if(isset($_POST['login']) && isset($_POST['usuario']) && isset($_POST['clave'])){
    
    //saneamiento de cadenas
    $usuario = strip_tags($_POST['usuario']);
    $usuario = addslashes($usuario);
    $clave = $_POST['clave'];
    
    $id = comprobarDatos($usuario, $clave);
    //si se ha identificado, se loggea
    if($id == 1){
        $_SESSION['usuario'] = $usuario;
        $accion = "loggeado";
        
        if(isset($_SESSION['deaquivengo']))
            $accion = "redireccion";
    }
    //si ha habido error de identificación
    else if($id == 0){
        $accion = "error_login";
    }
    //si ha habido error de bd
    else{
        $accion = "error_bd";
    }
}
else{
    $accion = "formulario";
}

// se comprueba la acción
if($accion == "loggeado" || $accion == "formulario"){
    header("Location: ../view/inicio.php");
}
elseif($accion == "redireccion"){
    header("Location: {$_SESSION['deaquivengo']}");
}
elseif($accion == "error_login"){
    HTMLcontenido("Login");
    HTMLformulario('E');
    HTMLfooter();
}
else if($accion == "error_bd"){

    mensaje("Login", "Error al conectar con la base de datos.");
    HTMLformulario('V');
    HTMLfooter();
}
else{
    header("Location: ../view/inicio.php");
}
?>