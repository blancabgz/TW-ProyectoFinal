<?php
require_once '../controller/check_login.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';

$titulo="Listado de vacunas";

if($rol == 'A'){

    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //obtenemos el listado
    $lista = obtenerListadoVacunas();
    if($lista == 0){
        echo "<p> Error al conectarse a la base de datos</p>";
    }
    else if($lista == 1){
        echo "<p> No hay vacunas para mostrar, la bd está vacía. </p>";
    }
    else{
        //mostramos el listado
        mostrarListaVacunas($lista, $titulo);
    }

    HTMLformulario($rol);
    HTMLfooter();    
}
else{
    header("Location: ../view/inicio.php");
}
?>