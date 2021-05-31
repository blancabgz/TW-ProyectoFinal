<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Inicio";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);
HTMLcontenido($titulo);
HTMLformulario($rol);
HTMLfooter();
?>
