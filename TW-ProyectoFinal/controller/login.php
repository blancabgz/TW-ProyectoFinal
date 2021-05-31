<?php
//comprobamos hay una sesión iniciada
if(session_status()==PHP_SESSION_NONE)
    session_start();

require_once '../view/vistasHTML.php';
require_once '../model/basedatos.php';

HTMLinicio("Login");
HTMLheader("Login");
HTMLnav('V');
HTMLcontenido("Login");

//se comprueba si los datos introducidos son correctos
if(isset($_POST['login']) && isset($_POST['usuario']) && isset($_POST['clave'])){
    
    //saneamiento de cadenas
    $usuario = strip_tags($_POST['usuario']);
    $usuario = addslashes($usuario);
    $clave = $_POST['clave'];
    
    if(comprobarDatos($usuario, $clave)){
        $_SESSION['usuario'] = $usuario;
        $accion = "loggeado";
        
        if(isset($_SESSION['deaquivengo']))
            $accion = "redireccion";
    }
    else{
        $accion = "error_login";
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
    HTMLformulario('E');
    HTMLfooter();
}
else{
    header("Location: ../view/inicio.php");
}
?>