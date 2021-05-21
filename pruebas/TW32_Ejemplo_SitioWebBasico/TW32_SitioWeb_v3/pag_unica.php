<?php
require "VistasHTML.php"; 
include "templ_head.html";

$opc = 0;
if (isset($_GET["p"]) && ($_GET["p"]>=0 || $_GET["p"]<=3))
  $opc = $_GET['p'];

HTMLnav_alternativo($opc);
switch ($opc) { 
  case 0: include "templ_inicio.html"; break; 
  case 1: include "templ_alan.html"; break; 
  case 2: include "templ_ada.html"; break; 
  case 3: include "templ_contacto.html"; break;
}

include "templ_foot.html";
?> 
