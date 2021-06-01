<?php
require_once '../controller/check_login.php';
require_once '../model/basedatos.php';
require_once '../view/vistasHTML.php';

$titulo="Listado de usuarios";

if($rol == 'A' || $rol == 'S'){

    HTMLinicio($titulo);
    HTMLheader($titulo);
    HTMLnav($rol);

    //obtenemos el listado
    $lista = obtenerListado();
    if($lista == 0){
        mensaje($titulo, "Error al conectarse a la base de datos.");
    }
    else if($lista == 1){
        mensaje($titulo, "No hay nada para mostrar, la base de datos está vacía.");
    }
    else{
        //mostramos el listado
        mostrarLista($lista);
    }
    
    HTMLformulario($rol);
    HTMLfooter();
}
else{
    header("Location: ../view/inicio.php");
}
?>
