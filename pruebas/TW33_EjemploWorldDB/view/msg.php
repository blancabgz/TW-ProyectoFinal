<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones para mostrar mensajes de error en la aplicación PHP.
*/

function _msgErrorR($msg) {
  if (is_array($msg))
    foreach ($msg as $v)
      _msgErrorR($v);
  else
    echo "<p>$msg</p>";
}

function msgError($msg, $tipo='msgerror') {
  echo "<div class='$tipo'>";
  _msgErrorR($msg);
  echo '</div>';
}

function msgCount($msg) {
  if (is_array($msg))
    if (count($msg)==0)
      return 0;
    else
      return msgCount($msg[0])+msgCount(array_slice($msg,1));
  else if (!is_bool($msg))
    return 1;
  else
    return 0;
}

?>
