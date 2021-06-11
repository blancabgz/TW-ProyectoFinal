<?php
require_once "../controller/check_login.php";
require_once "../model/basedatos.php";
require_once "../model/backup.php";
require_once "../view/vistasComunes.php";
require_once "../model/log.php";

$titulo="Copia de seguridad";
$titulo_form="Copia de seguridad del sistema";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
    //realiza la copia de seguridad, que se almacena en la base de datos
    $mensaje = copiaSeguridad();
    mensaje($titulo_form, $mensaje);

    $mens = "Copia de seguridad: ".$_SESSION['usuario'].". Mensaje: ".$mensaje."";
    log_sistema($mens);
}
HTMLformulario($rol);
HTMLfooter();

?>