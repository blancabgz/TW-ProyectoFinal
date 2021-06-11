<?php
require_once 'credenciales.php';
require_once 'basedatos.php';

function obtenerLogSistema(){

    $bd = conectarBD();
    $logSistema = 'Error al conectarse con la base de datos.';
    $fecha = date("Y-m-d G:i:s");
    
    if($bd){
        $consulta = "SELECT * FROM log";
        $consulta_res = mysqli_query($bd, $consulta);

        if(mysqli_num_rows($consulta_res) < 0){
            $logSistema = 'No hay datos registrados en la base de datos.';
        }
        else{
            $logSistema = mysqli_fetch_all($consulta_res, MYSQLI_ASSOC);
        }
    }
    return $logSistema;
}

function log_sistema($descripcion){
    $bd = conectarBD();
    $fecha = date("Y-m-d G:i:s");
    
    if($bd){
        $consulta = "INSERT INTO log (fecha, descripcion) VALUES ('".$fecha."', '".$descripcion."')";
        $consulta_res = mysqli_query($bd, $consulta);
    }
}
?>