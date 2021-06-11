<?php
require_once 'credenciales.php';

/* CONEXIÓN Y DESCONEXIÓN DE LA BASE DE DATOS*/
// Conexión a la BBDD
function conectarBD(){
    $bd = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);
    $dato = 1;
    
    if(!$bd)
        $dato = 0;

    mysqli_set_charset($bd,"utf8");

    //dato: 0 -> error; dato: 1 -> se ha conectado
    return $bd;
}

// Desconexión de la BBDD
function desconectarBD($bd) {
    mysqli_close($bd);
}
?>