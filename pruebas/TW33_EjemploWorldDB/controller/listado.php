<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para ver listado de ciudades.
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

// ************* Inicio de la página
htmlStart('Listado completo','Listado');

// Obtener listado de ciudades
$ciudades=MCiudad_getCiudades($db);
  
// Mostrar listado
VCiudad_listadoCiudades($ciudades);

// ************* Fin de la página
htmlEnd();

// Desconectar de la BBDD (se puede omitir)
DB_desconexion($db);
?>