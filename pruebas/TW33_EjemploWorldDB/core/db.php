<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones de conexión y desconexión con la BBDD.
*/

require_once('db_credenciales.php');

// Conexión a la BBDD
// Devuelve un resource si hay éxito o una cadena con una descripción del error
function DB_conexion() {
  $db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE,DB_PORT);
  if (!$db)
    return "Error de conexión a la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();

  // Establecer el conjunto de caracteres del cliente
  mysqli_set_charset($db,"utf8");

  return $db;
}

// Desconexión de la BBDD
function DB_desconexion($db) {
  mysqli_close($db);
}

?>
