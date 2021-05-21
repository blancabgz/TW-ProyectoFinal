<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones que presentan vistas de la funcionalidad de backup/restauración de la BBDD.
*/

function Vdb_restore() {
echo <<< HTML
<div class='frm_ciudad'>
  <form action='restore.php' method='POST' enctype="multipart/form-data">
    <div class='frm_ciudad_input'>
      <label for='fichero'>Fichero:</label>
      <input type='file' name='fichero' />
    </div>
    <div class='frm_ciudad_submit'>
      <input type='submit' name='submit' value='Subir' />
    </div>
  </form>
</div>
HTML;
}

?>
