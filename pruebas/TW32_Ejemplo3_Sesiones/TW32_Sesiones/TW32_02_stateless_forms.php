<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <?php
  if (isset($_POST['nombre']))
    if (isset($_POST['apellidos']))
      if (isset($_POST['trabajo']))
        showData($_POST['nombre'], $_POST['apellidos'], $_POST['trabajo']);
      else
        showFormWork($_POST['nombre'], $_POST['apellidos']);
    else
      showFormSurname($_POST['nombre']);
  else
    showFormName();
  ?>
</body>
</html>

<?php

function showFormName() {
  echo <<< HTML
       <h3>Introduce datos:</h3>
       <form action={$_SERVER['SCRIPT_NAME']} method='POST'> 
         <label>Nombre: <input type='text' name='nombre'></label>
         <input type='submit' name='submit' value='Enviar'>
       </form>
HTML;
}

function showFormSurname($nombre) {
  echo <<< HTML
       <h3>Introduce datos:</h3>
       <p>Bienvenido $nombre, continua ...</p>
       <form action={$_SERVER['SCRIPT_NAME']} method='POST'> 
         <label>Apellidos: <input type='text' name='apellidos'></label>
         <input type='hidden' name='nombre' value='$nombre'>
         <input type='submit' name='submit' value='Enviar'>
       </form>
HTML;
}

function showFormWork($nombre, $apellidos) {
  echo <<< HTML
       <h3>Introduce datos:</h3>
       <p>$nombre $apellidos, continua ...</p>
       <form action={$_SERVER['SCRIPT_NAME']} method='POST'> 
         <label>Trabajas en: <input type='text' name='trabajo'></label>
         <input type='hidden' name='nombre' value='$nombre'>
         <input type='hidden' name='apellidos' value='$apellidos'>
         <input type='submit' name='submit' value='Enviar'>
       </form>
HTML;
}

function showData($nombre, $apellidos, $trabajo) {
  echo <<< HTML
       <h3>Datos recopilados:</h3>
       <p>Nombre: $nombre</p>
       <p>Apellidos: $apellidos</p>
       <p>Trabajo: $trabajo</p>
HTML;
}


?>