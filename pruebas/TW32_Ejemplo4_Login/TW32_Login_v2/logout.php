<?php
if (session_status()==PHP_SESSION_NONE) 
  session_start();
require('include.php');
if (isset($_SESSION['usuario']))
  acabarSesion();

HTMLinicio('Logout');
HTMLencabezado();
echo '<h1>La sesión ha terminado</h1>';
HTMLpiepagina();
HTMLfin();
?>