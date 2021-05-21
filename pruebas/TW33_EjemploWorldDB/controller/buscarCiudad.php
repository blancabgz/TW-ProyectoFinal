<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para buscar una ciudad.
*/

require('pagcode.php');
require('../view/html.php');     // Maquetado de página
require('../view/vciudad.php');  // Vistas del modelo Ciudad
require('../model/ciudad.php');  // Modelo de datos para manipular ciudades
require('../core/db.php');       // Operaciones con BBDD

// Conexión con la BBDD
if (is_string($db=DB_conexion())) {
  $msg_err = $db;
  require('error.php');
}

if (isset($_POST['accion'])) {
  $datos = checkRequest($_POST);
} else {
  $datos = checkRequestPaginas($_GET);
  $datos = array_merge($datos,checkRequestBusqueda($_GET));
}

// ************* Inicio de la página
htmlStart('Buscar ciudad','Búsqueda');

// ************* Contenido
if (isset($datos['busqueda']))
	VCiudad_buscarCiudad('Datos de la búsqueda:',$datos['busqueda']);
else
	VCiudad_buscarCiudad('Datos de la búsqueda:');

if ($datos['accion']=='Buscar') {
  $busc = MCiudad_buildSearch($db,$datos['busqueda']);
  $numciudades=MCiudad_getNumCiudades($db,$busc);
  if ($numciudades>0) {
	  $ciudades=MCiudad_getCiudades($db,$datos['primero'],$datos['numitems'],$busc);
		// Mostrar listado
	  if ($ciudades!==false)
	  	VCiudad_listadoCiudadesBotones($ciudades,'editarCiudad.php');
	  else {
	  	$datos['info'][] = 'Ha habido un error en la consulta a la BBDD';
	  	$datos['info'][] = mysqli_error($db);
	  }
	} else
    $datos['info'][] = 'No se han encontrado ciudades con ese criterio';

	// Barra de paginación
	if ($numciudades>0 && $datos['numitems']>0)
    htmlNav('paginador', build_pagLinks($numciudades, $datos['numitems'], $datos['primero'], $datos['busqueda']));
}

if (isset($info) && msgCount($info)>0)
  msgError($info);

// ************* Fin de la página
htmlEnd();

// Desconectar de la BBDD (se puede omitir)
DB_desconexion($db);


// ************* Funciones auxiliares
// Verificar argumentos de la petición web
function checkRequestPaginas($param) {
  $datos = [];

  // ************* Argumentos GET de la página
  //  primero: Primer item a visualizar
  //  items : cuantos items incluir (<=0 para ver todos)
  if (!isset($param['items']))
    $datos['numitems'] = 10;   // Valor por defecto
  else if ($param['items']<1 || !is_numeric($param['items']))
    $datos['numitems'] = 0;    // Para mostrar todos los items
  else
    $datos['numitems'] = $param['items'];

  if ($datos['numitems']==0)
    $datos['primero']=0;      // Ver todos los items
  else {
    $datos['primero'] = isset($param['primero']) ? $param['primero'] : 0;
    if ($datos['primero']<0 || !is_numeric($datos['primero']))
      $datos['primero']=0;
  }
  return $datos;
}

function checkRequestBusqueda($param) {
  $datos = [];
  $datos['accion'] = '';

  $aux = [];
  $aux['bnombre'] = $param['bnombre'] ?? null;
  $aux['bcomunidad'] = $param['bcomunidad'] ?? null;
  $aux['bpobmin'] = (isset($param['bpobmin']) && is_numeric($param['bpobmin']) && $param['bpobmin']>=0) ? $param['bpobmin'] : null;
  $aux['bpobmax'] = (isset($param['bpobmax']) && is_numeric($param['bpobmax']) && $param['bpobmax']>=0) ? $param['bpobmax'] : null;

  if (isset($aux['bnombre']) || isset($aux['bcomunidad']) || isset($aux['bpobmin']) || isset($aux['bpobmax'])) {
    $datos['busqueda'] = $aux;
    $datos['accion'] = "Buscar";
  }
  
  return $datos;
}

function checkRequest($param) {
  $datos = [];
  $datos['accion'] = '';

  $aux = [];
  $aux['bnombre'] = (isset($param['name']) && $param['name']!='') ? $param['name'] : null;
  $aux['bcomunidad'] = (isset($param['district']) && $param['district']!='') ? $param['district'] : null;
  $aux['bpobmin'] = (isset($param['population_min']) && $param['population_min']!='' && 
                       is_numeric($param['population_min']) && $param['population_min']>=0) ? $param['population_min'] : null;
  $aux['bpobmax'] = (isset($param['population_max']) && $param['population_max']!='' && 
                       is_numeric($param['population_max']) && $param['population_max']>=0) ? $param['population_max'] : null;

  if (isset($aux['bnombre']) || isset($aux['bcomunidad']) || isset($aux['bpobmin']) || isset($aux['bpobmax'])) {
    $datos['busqueda'] = $aux;
    $datos['accion']='Buscar';
    $datos['primero']=0;
    $datos['numitems']=10;
  } else {
    $datos['info'] = [];
    $datos['info'][] = 'No ha indicado ningún campo de búsqueda';
  }

  return $datos;
}


?>