<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Presentación";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);
HTMLcontenido($rol);

HTMLformulario($rol);
HTMLfooter();
?>
