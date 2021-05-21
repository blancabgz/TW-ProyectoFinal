<?php

//echo "<p>*** Variables de sesión antes de session_start:</p>";
//htmlVariablesSESSION();

session_start();

//echo "<p>*** Variables de sesión después de session_start:</p>";
//htmlVariablesSESSION();

// Comprobar estado previo
if (isset($_POST["usuario"])) {
  // Acceso desde formulario de login
  // Comprobar credenciales comprobando usuario/clave ...
  $_SESSION["usuario"] = $_POST["usuario"];
} else if (isset($_POST["logout"])) {
  // Acceso desde botón de logout
  acabarSesion();

  //echo "<p>*** Variables de sesión después de cerrar sesión:</p>";
  //htmlVariablesSESSION();
}

htmlInicio();      // HTML: head e inicio de body

if (isset($_SESSION["usuario"])) {
  htmlBienvenido($_SESSION["usuario"]);
} else {
  htmlLogin();
}

htmlFin();

/*******************************************************/

function acabarSesion()
{
  // La sesión debe estar iniciada
  if (session_status()==PHP_SESSION_NONE)
    session_start();

  // Borrar variables de sesión
  //$_SESSION = array();
  session_unset();

	// Destruir sesión
	session_destroy();

  // Borrar cookie de sesión 
  // (se deben usar los mismos parámetros usados durante su creación)
  $param = session_get_cookie_params();
	setcookie(session_name(),$_COOKIE[session_name()],time()-2592000,
		      $param['path'], $param['domain'], $param['secure'], 
		      $param['httponly']);
}

function htmlInicio() {
echo <<< HTML
<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title>Ejemplo de sesión</title>
  </head>
  <body>
HTML;
}

function htmlFin() {
echo <<< HTML
  </body>
</html>
HTML;
}

function htmlLogin() {
echo <<< HTML
  <p>Introduzca sus credenciales (v2):</p>
  <form action="{$_SERVER['SCRIPT_NAME']}" method="post">
    <label style='display:block'>Usuario
    <input type="text" placeholder="usuario" name="usuario" required></label>
    <label style='display:block'>Clave
    <input type="password" placeholder="clave" name="pwd" required></label>
    <input type="submit" name="login" value="Login">
  </form>
HTML;
}

function htmlBienvenido($nombre) {
echo <<< HTML
  <p>Bienvenido $nombre, sesión establecida</p>
  <form action="{$_SERVER['SCRIPT_NAME']}" method="post">
    <input type="submit" name="logout" value="Logout">
  </form>
HTML;
}

function htmlVariablesSESSION() {
	print_r($_SESSION ?? 'Array sin definir');
  echo "<br>";
}

?>