<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones auxiliares para crear barra de paginación.
*/

function build_pagLinks($numciudades, $numitems, $primero, $busq=null) {
  $links = [];

  $ultima = $numciudades - ($numciudades%$numitems);
  $anterior = $numitems>$primero ? 0 : ($primero-$numitems);
  $siguiente = ($primero+$numitems)>$numciudades ? $ultima : ($primero+$numitems);
  
  $links[] = ['texto'=>'Primera', 'url'=>'?primero=0&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Anterior', 'url'=>'?primero='.urlencode($anterior).'&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Siguiente', 'url'=>'?primero='.urlencode($siguiente).'&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Última', 'url'=>'?primero='.urlencode($ultima).'&items='.urlencode($numitems)];

  if ($busq!=null)
    $links = add_query2links($links,$busq);

  return $links;
}

function add_query2links($links,$query) {
  foreach ($links as &$l)
    $l['url'] = $l['url'].'&'.http_build_query($query);
  return $links;
}

?>
