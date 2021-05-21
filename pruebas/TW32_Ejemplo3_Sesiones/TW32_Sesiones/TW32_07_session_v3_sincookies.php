<?php

iniciarSesion();

// Comprobar estado previo
if (isset($_POST["usuario"])) {
  // Acceso desde formulario de login
  // Comprobar credenciales comprobando usuario/clave ...
  $_SESSION["usuario"] = $_POST["usuario"];
} else if (isset($_POST["logout"])) {
  // Acceso desde botón de logout
  acabarSesion();
}

htmlInicio();      // HTML: head e inicio de body

if (isset($_SESSION["usuario"])) {
  htmlBienvenido($_SESSION["usuario"]);
  echo "<a href='".$_SERVER['SCRIPT_NAME']."'>Enlace a este mismo script</a>";
} else {
  htmlLogin();
}

htmlFin();

/*******************************************************/

function iniciarSesion()
{
	/*ini_set("session.use_cookies", 0);
	ini_set("session.use_only_cookies", 0);
	ini_set("session.use_trans_sid", 1);
	session_start();*/
	session_start(["use_cookies" => "0",
		           "use_only_cookies" => "0",
		           "use_trans_sid" => "1"
             ]);
}

function acabarSesion()
{
  // La sesión debe estar iniciada
  if (session_status()==PHP_SESSION_NONE) {
		iniciarSesion();
  }

  // Borrar variables de sesión
	session_unset();

	// Borrar el parámetro de ID de sesión
	if (isset($_GET[session_name()]))
		unset($_GET[session_name()]);
	if (isset($_POST[session_name()]))
		unset($_POST[session_name()]);

	// Destruir sesión
	session_destroy();
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
  <p>Introduzca sus credenciales (v3):</p>
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