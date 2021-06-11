<?php
require_once "../controller/check_login.php";
require_once "../model/basedatos.php";
require_once "../model/backup.php";
require_once "../model/log.php";
require_once "../view/vistasComunes.php";
require_once "../view/vistasSistema.php";

$titulo="Restauración";
$titulo_form="Restauración de la base de datos";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
    //si ha pulsado en restauración, viene de la lista de los ficheros
    if(isset($_POST['restauracion'])){
        $fichero = "../backup/".$_POST['fichero'];
        $mensaje = restauracion($fichero);
        mensajesRestauracion($titulo_form, $mensaje);
        
        //si es un array ha habido algún error
        if(is_array($mensaje)){
            $mens = "Restauración: ".$_SESSION['usuario'].". Mensaje: Error.";
            log_sistema($mens);
        }
        else{
            $mens = "Restauración: ".$_SESSION['usuario'].". Mensaje: ".$mensaje."";
            log_sistema($mens);
        }
    }
    //si viene de nuevas, se le muestra el listado de ficheros de copias de seguridad 
    else{
        //realiza la copia de seguridad, que se almacena en la base de datos
        $copias = obtenerCopiasSeguridad();

        //si no es un array, ha habido error con lo que lo mostramos
        if(!is_array($copias)){ 
            mensaje($titulo_form, $copias);
        }
        else{
            //mostramos la lista de ficheros
            mostrarCopiasSeguridad($titulo_form, $copias);
        }
    }    
}
HTMLformulario($rol);
HTMLfooter();

?>