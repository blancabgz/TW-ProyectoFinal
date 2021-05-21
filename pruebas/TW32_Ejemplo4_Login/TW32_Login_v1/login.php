<?php
if (session_status()==PHP_SESSION_NONE) 
  session_start();
require('include.php');

// Comprobamos si el usuario está autenticado o no y decidimos qué hay que hacer
if (isset($_SESSION['usuario'])) {
  // El usuario ya está identificado
  $accion = "yaidentificado";
} else if (isset($_POST['submit']) && isset($_POST['usuario']) && isset($_POST['password'])) {
  // Se han recibido datos del formulario de login
  // Hay que verificar si el usuario es válido (login/password) ... acceso a BBDD / etc
  if ($_POST['usuario']=="pepito" && $_POST['password']=="secreto") {
    // Autenticación correcta: almacenamos datos en variables de sesión
    $_SESSION['usuario'] = $_POST['usuario'];
    $_SESSION['nombre'] = "Pepito Pérez ({$_POST['usuario']})";
    //if ($desdedonde != '')

    $accion="bienvenida";
  } else
    $accion = "errorautenticacion";   // Los datos no son válidos
} else
  $accion = "formulario";   // Acceso directo a la página de login

HTMLinicio('Login');
HTMLencabezado();

switch ($accion) {
  case "yaidentificado":
      echo "<h1>Usted ya está autenticado {$_SESSION['nombre']}</h1>";
      break;
  case "errorautenticacion":
    echo "<h1>Identificación incorrecta</h1>";
    echo "<h2>Inténtelo de nuevo</h2>";
    FORM_login('');
    break;
  case "formulario":
    FORM_login('');
    break;
  case "bienvenida":
    echo "<h1>Bienvenido, {$_SESSION['nombre']}, se ha identificado correctamente</h1>";
    break;
  default:
    echo "default";
}

HTMLpiepagina();
HTMLfin();
?>