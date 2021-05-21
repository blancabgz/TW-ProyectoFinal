<?php
require('checklogin.php');
require('include.php');

HTMLinicio('Página privada');
HTMLencabezado();
echo '<h1>Página privada</h1>';
echo '<p>Esta página solo se muestra a usuarios autenticados</p>';
HTMLpiepagina();
HTMLfin();
?>