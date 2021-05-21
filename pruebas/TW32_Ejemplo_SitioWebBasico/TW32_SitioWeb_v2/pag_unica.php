<?php
require "VistasHTML.php"; 

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"]>=0 || $_GET["p"]<=3))
  $opc = $_GET['p'];

HTMLinicio("Personajes históricos");

HTMLnav_alternativo($opc);
switch ($opc) { 
  case 0: HTMLpag_inicio(); break; 
  case 1: HTMLpag_alan(); break; 
  case 2: HTMLpag_ada(); break; 
  case 3: HTMLpag_contacto(); break;
}

HTMLfooter();
HTMLfin();
?> 
