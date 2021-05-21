<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para ver listado de ciudades incluyendo una barra de paginación.
*/

require('pagcode.php');
require('../core/db.php');       // Operaciones con BBDD
require('../view/html.php');     // Maquetado de página
require('../view/vciudad.php');  // Vistas del modelo Ciudad
require('../model/ciudad.php');  // Modelo de datos para manipular ciudades

// Conexión con la BBDD
if (is_string($db=DB_conexion())) {
  $msg_err = $db;
  require('error.php');
}

// ************* Argumentos GET de la página
$datos = checkRequest($_GET);

// ************* Contenido

// ************* Inicio de la página
htmlStart('Listado paginado','Listado Paginado');

// Obtener listado de ciudades
$numciudades=MCiudad_getNumCiudades($db);
$ciudades=MCiudad_getCiudades($db,$datos['primero'],$datos['numitems']);

// Mostrar listado
if ($ciudades!==false)
	VCiudad_listadoCiudades($ciudades);
else
	$info[] = 'Ha habido un error en la consulta a la BBDD';

// Barra de paginación
if ($datos['numitems']>0) {
  htmlNav('paginador', build_pagLinks($numciudades, $datos['numitems'], $datos['primero']));
}

if (isset($info) && msgCount($info)>0)
  msgError($info);

// ************* Fin de la página
htmlEnd();

// Desconectar de la BBDD (se puede omitir)
DB_desconexion($db);

// ************* Funciones auxiliares
// Verificar argumentos de la petición web
function checkRequest($get) {
  $datos = [];

  // ************* Argumentos GET de la página
  //  primero: Primer item a visualizar
  //  items : cuantos items incluir (<=0 para ver todos)
  if (!isset($get['items']))
    $datos['numitems'] = 10;   // Valor por defecto
  else if ($get['items']<1 || !is_numeric($get['items']))
    $datos['numitems'] = 0;    // Para mostrar todos los items
  else
    $datos['numitems'] = $get['items'];

  if ($datos['numitems']==0)
    $datos['primero']=0;      // Ver todos los items
  else {
    $datos['primero'] = isset($get['primero']) ? $get['primero'] : 0;
    if ($datos['primero']<0 || !is_numeric($datos['primero']))
      $datos['primero']=0;
  }
  return $datos;
}

?>