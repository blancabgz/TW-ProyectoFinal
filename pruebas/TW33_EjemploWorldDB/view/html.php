<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones para hacer el maquetado general de la página web.
*/

// $titulo: title de la página
// $activo: enlace activo del menu
function htmlStart($titulo,$activo='') {
  __htmlInicio($titulo);
  __htmlEncabezado($activo);
  __htmlContenidosIni();
}

function htmlEnd() {
  __htmlContenidosFin();
  __htmlPiepagina();
  __htmlFin();
}

function htmlNav($clase,$menu,$activo='') {
  echo "<nav class='$clase'>";
  foreach ($menu as $elem)
    echo "<a ".($activo==$elem['texto']?"class='activo' ":'')."href='{$elem['url']}'>{$elem['texto']}</a>";
  echo '</nav>';
}

// ******** Funciones privadas de este módulo

// Cabecera de página web
function __htmlInicio($titulo) {
echo <<< HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="../view/estilo.css" />
<title>{$titulo}</title>
</head>
<body>	
HTML;
}

// Contenidos INICIO
function __htmlContenidosIni() {
echo '<div class="contenidos">';
}

// Encabezado
function __htmlEncabezado($activo) {
echo <<< HTML
<div class='encabezado'>
  <h1>GeoWeb: geografía política</h1>
  <h2>Una web con datos geográficos</h2>
</div>
HTML;
htmlNav('menu',[['texto'=>'Listado', 'url'=>'listado.php'],
                ['texto'=>'Listado Paginado', 'url'=>'listado_paginado.php'],
                ['texto'=>'Listado Paginado (botones)', 'url'=>'listado_paginadoBotones.php'],
                ['texto'=>'Búsqueda', 'url'=>'buscarCiudad.php'],
                ['texto'=>'Nueva ciudad', 'url'=>'addCiudad.php'],
                ['texto'=>'Backup', 'url'=>'backup.php'],
                ['texto'=>'Restore', 'url'=>'restore.php']],$activo);
}

// Pie de página
function __htmlPiepagina() {
echo <<< HTML
<div class='piepagina'>
  <h1>&copy; Tecnologías Web</h2>
</div>
HTML;
}

// Contenidos FIN
function __htmlContenidosFin() {
echo '</div>';
}

// Cierre de página web
function __htmlFin() {
echo '</body></html>';
}

?>