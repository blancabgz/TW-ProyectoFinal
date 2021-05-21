<?php

// La cookie se almacena pero no está disponible hasta la próxima
// visita a la página
 
if (isset($_POST['borrar'])) {
  setcookie("visitas",'0',time()-1000);  // Caducar cookie
  $numvisita = 0;
} else {
  if (!isset($_COOKIE['visitas']) || isset($_POST['poneracero']))
    $numvisita = 1;  // Primera visita o reseteo
  else
    $numvisita = $_COOKIE["visitas"] + 1;

  setcookie("visitas",$numvisita,time()+60*60*24*1000);
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Tecnologías Web</title>
  </head>
  <body id="principal">
    <?php
      if ($numvisita==1) {
        echo '<p>Bienvenido, esta es su primera visita</p>';
        if (isset($_POST['poneracero']))
          echo '<p> ... pero ya has estado antes, y lo sabes</p>';
      } else if ($numvisita>1)
        echo "<p>¿Qué hay de nuevo? esta es su visita número $numvisita</p>";
      else   // $numvisita==0
        echo "<p>Vale, ha borrado su contador de visitas</p>";
    ?>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='POST'>
      <input type='submit' value='Resetear cookie' name='poneracero'>
      <input type='submit' value='Borrar cookie' name='borrar'>
    </form>
  </body>
</html>
