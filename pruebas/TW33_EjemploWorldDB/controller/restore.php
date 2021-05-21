<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Controlador para restaurar la BBDD.
*/

require('../view/html.php');     // Maquetado de página
require('../core/db.php');       // Operaciones con BBDD
require('../core/db_backup.php');    // Operaciones de backup de la BBDD
require('../view/msg.php');
require('../view/vdb.php');

// ************* Inicio de la página
htmlStart('Restaurar Base de Datos','Restore');

if (isset($_POST['submit'])) {
	/* Comprobar que se ha subido algún fichero */
	if ((sizeof($_FILES)==0) || !array_key_exists("fichero",$_FILES))
		$error = "No se ha podido subir el fichero";
	else if (!is_uploaded_file($_FILES['fichero']['tmp_name']))
		$error = "Fichero no subido. Código de error: ".$_FILES['fichero']['error'];
	else {
    $db=DB_conexion();
		$error = DB_restore($db,$_FILES['fichero']['tmp_name']);
    DB_desconexion($db);
	}
	if (isset($error) && msgCount($error)>0)
  	msgError($error);		
  else
    msgError("Base de datos restaurada correctamente",'msginfo');
} else
	Vdb_restore();

// ************* Fin de la página
htmlEnd();
?>
