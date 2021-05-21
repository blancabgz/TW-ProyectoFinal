<?php
if (session_status()==PHP_SESSION_NONE) 
  session_start();

// Al comenzar anotamos si hemos llegado aquí tras una petición de acceso a una página privada.
// En ese caso en la variable $_SESSION['desdedonde'] se habrá anotado la URL --> la copiamos
// a una variable local al script y la eliminamos de la sessión para evitar el siguiente caso:
//  1.- se intenta acceder a una página privada
//  2.- se asigna valor a $_SESSION['desdedonde']
//  3.- se redirige a login.php (este script)
//  4.- en lugar de identificarse:
//      4.1.- el usuario va a otra página que no requiere autenticación
//      4.2.- el usuario accede a la página de login desde el menú (y por tanto no hay que redireccionar)
//            si no hubiésemos borrado la variable, permanecería y habría redirección
unset($_SESSION['desdedonde']);
?>