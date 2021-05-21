<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para hacer un backup de la BBDD.
*/

require('../view/html.php');     // Maquetado de página
require('../core/db.php');       // Operaciones con BBDD
require('../core/db_backup.php');    // Operaciones de backup de la BBDD
require('../view/msg.php');

if (isset($_GET['download'])) {
  if (!is_string($db=DB_conexion())) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="db_backup.sql"');
    echo DB_backup($db);
    DB_desconexion($db);
  }
} else {
  htmlStart('Copia de seguridad','Backup');
  msgError("<a href='".$_SERVER['SCRIPT_NAME']."?download'>Pulse aquí</a> para descargar un fichero con los datos de la copia de seguridad",'msginfo');
  htmlEnd();
}
?>
