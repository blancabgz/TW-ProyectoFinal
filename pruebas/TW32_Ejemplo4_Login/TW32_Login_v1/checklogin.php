<?php
if (session_status()==PHP_SESSION_NONE) 
  session_start();
if (!isset($_SESSION['usuario'])) {
  // Es conveniente hacer alguna verificación de REQUEST_URI por si hubiese sido manipulado, por ejemplo comprobar que el servidor es el mismo o que la URL base de la aplicación es la nuestra
  header("Location: login.php");
}
?>