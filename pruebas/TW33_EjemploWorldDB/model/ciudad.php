<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Modelo de datos de ciudades (tabla 'city').
  Se implementan funciones para acceder a la BBDD y trabajar con la tabla que almacena ciudades.
*/

// Borrar una ciudad
function MCiudad_delCiudad($db,$id) {
  $prep = mysqli_prepare($db,"DELETE FROM city WHERE id=?");
  $val = $id;
  mysqli_stmt_bind_param($prep,'s',$val);
  mysqli_stmt_execute($prep);
  if (mysqli_stmt_affected_rows($prep)==1)
    $ret = true;
  else
    $ret = false;
  mysqli_stmt_close($prep);
  return $ret;
}

// Actualizar una ciudad
function MCiudad_actCiudad($db,$datos) {
  $prep = mysqli_prepare($db, "SELECT id,name FROM city WHERE name=? AND countrycode='ESP'");
  $val = $datos['name'];
  mysqli_stmt_bind_param($prep,'s',$val);
  mysqli_stmt_execute($prep);
  $res = mysqli_stmt_get_result($prep);
  $ciudad = mysqli_fetch_assoc($res);
  mysqli_free_result($res);
  mysqli_stmt_close($prep);

  if (isset($ciudad) && $ciudad['name']==$datos['name'] && $ciudad['id']!=$datos['id'])
    $info[] = 'Ya hay otra ciudad con ese nombre';
  else {
    $prep = mysqli_prepare($db, "UPDATE city SET name=?, district=?, population=? WHERE id=?");
    $val1 = $datos['name'];
    $val2 = $datos['district'];
    $val3 = $datos['population'];
    $val4 = $datos['id'];
    mysqli_stmt_bind_param($prep,'sssi',$val1,$val2,$val3,$val4);
    $result = mysqli_stmt_execute($prep);

    if (!$result) {
      $info[] = 'Error al actualizar';
      $info[] = mysqli_stmt_error($db);
    }
    mysqli_stmt_close($prep);
  }

  if (isset($info))
    return $info;
  else
    return true;  // OK
}

// Consulta para obtener datos de una ciudad
function MCiudad_getCiudad($db, $id) {
  $prep = mysqli_prepare($db,"SELECT id,name,district,population FROM city WHERE id=?");
  $val = $id;
  mysqli_stmt_bind_param($prep,'s',$val);
  if (mysqli_stmt_execute($prep)) {
    mysqli_stmt_bind_result($prep,$rid,$rname,$rdistrict,$rpopulation);
    if (mysqli_stmt_fetch($prep)) {
      $ciudad['id'] = $rid;
      $ciudad['name'] = $rname;
      $ciudad['district'] = $rdistrict;
      $ciudad['population'] = $rpopulation;
    } else
      $ciudad = false;  // No hay resultados
  } else
    $ciudad = false;  // Error en consulta
  mysqli_stmt_close($prep);
  return $ciudad;
}

// Consulta para obtener el número de ciudades
function MCiudad_getNumCiudades($db,$cadenab='') {
  if ($cadenab!='')
    $cadenab .= ' AND ';
  $res = mysqli_query($db, "SELECT COUNT(*) FROM city WHERE $cadenab countrycode='ESP'");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);
  return $num;
}

/*
// Consulta para obtener el número de ciudades
function MCiudad_getNumCiudades($db) {
  $res = mysqli_query($db, "SELECT COUNT(*) FROM city WHERE countrycode='ESP'");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);
  return $num;
}
*/

// Consulta para obtener listado de ciudades
function MCiudad_getCiudades($db,$primero=0,$numitems=0,$cadenab='') {
  if ($numitems<=0) {  // Listarlos todos
    $rango='';
  } else {
    $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
  }
  // Consulta a la BBDD
  if (strlen($cadenab)!=0)
    $cadenab.=' AND ';

  $res = mysqli_query($db,
         "SELECT id,name,district,population FROM city WHERE $cadenab countrycode='ESP' ORDER BY name $rango");
  if ($res) {
    // Si no hay error
    if (mysqli_num_rows($res)>0) {
      // Si hay alguna tupla de respuesta
      $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
    } else {
      //echo "<p>No hay resultados para la consulta</p>";
      $tabla = [];
    }
    // Liberar memoria de la consulta
    mysqli_free_result($res);
  } else {
    //echo '<p>Error en la consulta '.__FUNCTION__.'</p>';
    //echo '<p>'.mysqli_error($db).'</p>';
    $tabla = false;
  }
  return $tabla;
}

/*
// Consulta para obtener listado de ciudades
function MCiudad_getCiudades($db,$primero=0,$numitems=0) {
  if ($numitems<=0) {  // Listarlos todos
    $rango='';
  } else {
    $rango = 'LIMIT '.(int)($numitems).' OFFSET '.abs($primero);
  }
  // Consulta a la BBDD
  $res = mysqli_query($db,
         "SELECT id,name,district,population FROM city WHERE countrycode='ESP' ORDER BY name $rango");
  if ($res) {
    // Si no hay error
    if (mysqli_num_rows($res)>0) {
      // Si hay alguna tupla de respuesta
      $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
    } else {
      //echo "<p>No hay resultados para la consulta</p>";
      $tabla = [];
    }
    // Liberar memoria de la consulta
    mysqli_free_result($res);
  } else {
    //echo '<p>Error en la consulta '.__FUNCTION__.'</p>';
    //echo '<p>'.mysqli_error($db).'</p>';
    $tabla = false;
  }
  return $tabla;
}
*/


/*
// Consulta para obtener listado de ciudades
function MCiudad_getCiudades($db) {
  $res = mysqli_query($db,
         "SELECT id,name,district,population FROM city WHERE countrycode='ESP' ORDER BY name");
  if ($res) {
    // Si no hay error
    if (mysqli_num_rows($res)>0) {
      // Si hay alguna tupla de respuesta
      $tabla = mysqli_fetch_all($res,MYSQLI_ASSOC);
    } else {
      //echo "<p>No hay resultados para la consulta</p>";
      $tabla = [];
    }
    // Liberar memoria de la consulta
    mysqli_free_result($res);
  } else {
    //echo '<p>Error en la consulta '.__FUNCTION__.'</p>';
    //echo '<p>'.mysqli_error($db).'</p>';
    $tabla = false;
  }
  return $tabla;
}
*/

//function DB_array2SQL($db,$query) {
/* A partir de un array con datos de búsqueda construye la parte de la cláusula SQL que los incorpora (para luego unirlo a la consulta completa SQL) */
function MCiudad_buildSearch($db,$query) {
  $cadenab='';
  if (isset($query['bnombre']))
    $cadenab .= " name LIKE '%".mysqli_real_escape_string($db,$query['bnombre'])."%' AND";
  if (isset($query['bcomunidad']))
    $cadenab .= " district LIKE '%".mysqli_real_escape_string($db,$query['bcomunidad'])."%' AND";
  if (isset($query['bpobmin']))
    $cadenab .= " population>='".mysqli_real_escape_string($db,$query['bpobmin'])."' AND";
  if (isset($query['bpobmax']))
    $cadenab .= " population<='".mysqli_real_escape_string($db,$query['bpobmax'])."' AND";
  if (strlen($cadenab)>0)
    $cadenab = substr_replace($cadenab, '', strlen($cadenab)-4, 4);
  return $cadenab;
}

function MCiudad_addCiudad($db,$datos) {
  // Comprobar si ya hay una ciudad con el mismo nombre

  // COMENTAR PARA PROBAR SQL INJECTION
  $res = mysqli_query($db, "SELECT COUNT(*) FROM city WHERE name='".mysqli_real_escape_string($db,$datos['name'])."' AND countrycode='ESP'");
  $num = mysqli_fetch_row($res)[0];
  mysqli_free_result($res);

  // Poner para SQL INJECTION
  // $num=0;

  if ($num>0)
    $info[] = 'Ya existe una ciudad con ese nombre';
  else {
    // Ejemplo de SQL INJECTION en campo con nombre de ciudad
    // INPUT:     Alfacar');DELETE FROM city WHERE name='Albacete';#
    // CONSULTA:  INSERT INTO city (district,countrycode,population,name) VALUES ('Andalucía','ESP','4424','Alfacar');DELETE FROM city WHERE name='Albacete';#')
    // mysqli_query solo permite una consulta, cambiar por mysqli_multi_query

    // ACTIVAR para probar SQL INJECTION
    /*$consulta = "INSERT INTO city (district,countrycode,population,name) VALUES ('{$datos['district']}','ESP','{$datos['population']}','{$datos['name']}')";
    echo $consulta, '<br>';
    $res = mysqli_multi_query($db, $consulta);*/

    // COMENTAR  PARA PROBAR SQL INJECTION
    $res = mysqli_query($db, "INSERT INTO city (name,district,population,countrycode) VALUES ('".mysqli_real_escape_string($db,$datos['name'])."','".mysqli_real_escape_string($db,$datos['district'])."','".mysqli_real_escape_string($db,$datos['population'])."','ESP')");

    if (!$res) {
      $info[] = 'Error en la consulta '.__FUNCTION__;
      $info[] = mysqli_error($db);
    }
  }
  if (isset($info))
    return $info;
  else
    return true;  // OK
}

?>
