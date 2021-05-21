<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para añadir una ciudad.
*/

require('../core/db.php');       // Operaciones con BBDD
require('../model/ciudad.php');  // Modelo de datos para manipular ciudades
require('../view/html.php');     // Maquetado de página
require('../view/vciudad.php');  // Vistas del modelo Ciudad

// Conexión con la BBDD
if (is_string($db=DB_conexion())) {
  $msg_err = $db;
  require('error.php');
}

// ************* Argumentos POST de la página
$datos = checkRequest($_POST);

// ************* Inicio de la página
htmlStart('Añadir ciudad','Nueva ciudad');

// ************* Contenido
if ($datos['accion']=='Añadir') {
	$res = MCiudad_addCiudad($db,$datos);
  if ($res===true)
  	$datos['info'][] = 'Se ha añadido la ciudad con éxito';
  else
  	$datos['info'][] = $res;
} else
  VCiudad_editCiudad('Indique los datos:',$datos,'Añadir Ciudad');

if (isset($datos['info']) && msgCount($datos['info'])>0)
  msgError($datos['info']);

// ************* Fin de la página
htmlEnd();

// Desconectar de la BBDD (se puede omitir)
DB_desconexion($db);


// ************* Funciones auxiliares
// Verificar argumentos de la petición web
// Devuelve un array con
// 'accion' : Acción a realizar a continuación
// 'info'   : cadena o array de cadenas con mensajes para mostrar al usuario (errores, avisos, etc)
// ... más resto de campos recogidos del formulario
function checkRequest($post) {
  $datos = [];
  $datos['accion'] = '';
  $datos['error'] = false;
  if (($post['accion']??'')=='Añadir Ciudad') {    // (isset($_POST['accion']) && $_POST['accion']=='Añadir Ciudad')
    $datos['id'] = '';
    $datos['name'] = $post['name'] ?? '';
    $datos['district'] = $post['district'] ?? '';
    $datos['population'] = $post['population'] ?? '';

    if ($datos['name']=='' || $datos['district']=='' || $datos['population']=='')
      $datos['info'][]='No puede dejar campos vacíos';

    if (!is_numeric($datos['population']) || $datos['population']<=0)
      $datos['info'][]='El valor de población debe ser numérico y superior a cero';

    if (!isset($datos['info']))
      $datos['accion']='Añadir';
  }
  return $datos;
}

?>