<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para editar una ciudad.
*/

require('../core/db.php');       // Operaciones con BBDD
require('../view/html.php');     // Maquetado de página
require('../view/vciudad.php');  // Vistas del modelo Ciudad
require('../model/ciudad.php');  // Modelo de datos para manipular ciudades

// Conexión con la BBDD
if (is_string($db=DB_conexion())) {
  $msg_err = $db;
  require('error.php');
}

// ************* Argumentos POST de la página
$datos = checkRequest($_POST);

// ************* Inicio de la página
htmlStart('Modificar datos de ciudad');

if (isset($datos['id'])) {
	switch ($datos['accion']) {
		case 'Borrar': 
  	  $ciudad = MCiudad_getCiudad($db,$datos['id']);
		  $ciudad['editable']=false;
		  VCiudad_editCiudad('Confirme borrado de esta ciudad:',$ciudad,'Confirmar Borrado');
	  	break;
	  case 'Editar':
	  	$ciudad = MCiudad_getCiudad($db,$datos['id']);
	  	VCiudad_editCiudad('Edite los datos:',$ciudad,'Modificar Datos');
	  	break;
	  case 'BorrarOK':
	    if (MCiudad_delCiudad($db,$datos['id']))
	    	$datos['info'][] = 'La ciudad '.htmlentities($datos['name']??'').' ha sido borrada';
	    else
	    	$datos['info'][] = 'No se ha podido borrar '.htmlentities($datos['name']??'');
      //header('refresh: 5; url=listado_paginadoBotones.php');
	  	break;
	  case 'Modificar':
	    $msg = MCiudad_actCiudad($db,['id'=>$datos['id'], 'name'=>$datos['name'], 
	    	                    'district'=>$datos['district'],
	    	                    'population'=>$datos['population']]);
      if ($msg===true)
	    	$datos['info'][] = 'La ciudad '.htmlentities($datos['name']).' ha sido actualizada';
	    else {
	    	$datos['info'][] = 'No se ha podido actualizar '.htmlentities($datos['name']);
        $datos['info'][] = $msg;
      }
      //header('refresh: 5; url=listado_paginadoBotones.php');
	  	break;
	}

} else {
	// Si los parámetros no son correctos: volver al listado
	header('Location: listado_paginadoBotones.php');
}

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
  if (isset($post['accion']) && isset($post['id'])) {
    switch ($post['accion']) {
      case 'Borrar': $datos['accion'] = 'Borrar'; 
                     $datos['id'] = $post['id'];
                     break;
      case 'Editar': $datos['accion'] = 'Editar';
                     $datos['id'] = $post['id'];
                     break;
      case 'Modificar Datos':
                     $datos['accion'] = 'Modificar';
                     $datos['id'] = $post['id'];
                     $datos['name'] = $post['name'] ?? '';
                     $datos['district'] = $post['district'] ?? '';
                     $datos['population'] = $post['population'] ?? '';
                     if ($datos['name']=='' || $datos['district']=='' || $datos['population']=='') {
                       $datos['info'][]='No puede dejar campos vacíos';
                       $datos['accion'] = 'Editar';
                     }
                     if (!is_numeric($datos['population']) || $datos['population']<=0) {
                       $datos['info'][]='El valor de población debe ser numérico y superior a cero';                     
                       $datos['accion'] = 'Editar';
                     }
                     break;
      case 'Confirmar Borrado':
                     $datos['accion'] = 'BorrarOK';
                     $datos['id'] = $post['id'];
                     $datos['name'] = $post['name'] ?? '';
                     break;
      case 'Cancelar': break;
    }
  }
  return $datos;
}
?>