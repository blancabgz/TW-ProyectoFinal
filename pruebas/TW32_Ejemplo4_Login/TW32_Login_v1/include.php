<?php

// **************************************************

function acabarSesion() {
  // La sesión debe estar iniciada 
  if (session_status()==PHP_SESSION_NONE) 
    session_start();

  // Borrar variables de sesión
  //$_SESSION = array(); 
  session_unset(); 

  // Obtener parámetros de cookie de sesión
  $param = session_get_cookie_params(); 

  // Borrar cookie de sesión 
  setcookie(session_name(), $_COOKIE[session_name()], time()-2592000, 
            $param['path'], $param['domain'], $param['secure'], $param['httponly']);

  // Destruir sesión 
  session_destroy(); 
} 

// **************************************************

function FORM_login($action) {
echo <<< HTML
<div class='frm_login'>
  <form action='$action' method='POST'>
    <div class='frm_login_input'>
      <label for='usuario'>Usuario:</label>
      <input type='text' name='usuario' />
    </div>
    <div class='frm_login_input'>
      <label for='password'>Clave:</label>
      <input type='password' name='password' />
    </div>
    <div class='frm_login_submit'>
      <input type='submit' name='submit' value='Acceder' />
    </div>
  </form>
</div>
HTML;
}

// **************************************************

// Cabecera de página web
function HTMLinicio($titulo) {
echo <<< HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="estilo.css" />
<title>{$titulo}</title>
</head>
<body>  
HTML;
}

// Cierre de página web
function HTMLfin() {
echo '</body></html>';
}

// Menú de navegación
function HTMLmenu() {
echo <<< HTML
<nav class='menu'>
  <a href='paginapub.php'>Página pública</a>
  <a href='paginapriv.php'>Página privada</a>
  <a href='login.php'>Login</a>
  <a href='logout.php'>Logout</a>
</nav>
HTML;
}

// Contenidos INICIO
function HTMLcontenidosIni() {
echo '<div class="contenidos">';
}

// Contenidos FIN
function HTMLcontenidosFin() {
echo '</div>';
}

// Encabezado
function HTMLencabezadoAux($items) {
echo <<< HTML
<div class='encabezado'>
  <div class='titulo'>
    <h1>Sitio Web</h1>
  </div>
  <div class='identifica'>
    <ul>
HTML;
      foreach ($items as $k => $v)
        if (!empty($v))
          echo "<li><a href='$v'>$k</a></li>";
        else
          echo "<li>$k</li>";
echo <<< HTML
    </ul>
  </div>
</div>
HTML;
HTMLmenu();
}

function HTMLencabezado() {
  if (isset($_SESSION['usuario']))
    HTMLencabezadoAux([$_SESSION['nombre'] => '',
                  'Logout' => 'logout.php']);
  else
    HTMLencabezadoAux(['Ud. no está identificado' => '',
                  'Login' => 'login.php']);
}


// Pie de página
function HTMLpiepagina() {
echo <<< HTML
<div class='piepagina'>
  <h1>&copy; Tecnologías Web</h2>
</div>
HTML;
}


?>
