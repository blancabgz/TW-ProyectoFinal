<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ejemplo</title>
</head>
<body>

<?php

// Conexión a la BBDD
$db = mysqli_connect("localhost","jbaena","laclave","world","8889");
if ($db) {
  echo "<p>Conexión con éxito</p>";
} else {
  echo "<p>Error de conexión</p>";
  echo "<p>Código: ".mysqli_connect_errno()."</p>";
  echo "<p>Mensaje: ".mysqli_connect_error()."</p>";
  die("Adiós");
}

// Establecer la codificación de los datos almacenados ("collation")
mysqli_set_charset($db,"utf8");

// Consulta a la BBDD
$res = mysqli_query($db, 
       "SELECT name,district,population FROM city WHERE countrycode='ESP'");
if ($res) {
  // Si no hay error
  if (mysqli_num_rows($res)>0) {
    // Si hay alguna tupla de respuesta
    echo "<table><tr><th>Nombre</th><th>Comunidad</th><th>Población</th></tr>";
    while ($tupla=mysqli_fetch_array($res)) {
      // Para cada tupla ...
      echo "<tr><td>{$tupla['name']}</td>".
               "<td>{$tupla['district']}</td>".
               "<td>{$tupla['population']}</td></tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No hay resultados para la consulta</p>";
  }

  // Liberar memoria de la consulta
  mysqli_free_result($res);
} else {
  echo "<p>Error en la consulta</p>";
  echo "<p>Código: ".mysqli_connect_errno()."</p>";
  echo "<p>Mensaje: ".mysqli_connect_error()."</p>";  
}

// Desconexión de la BBDD
// Si no se pone: se cierra al finalizar el script
mysqli_close($db);

?>

</body>
</html>