<?php

function HTMLnav_alternativo($activo) {
echo <<< HTML
<nav>
<h2>Índice</h2>
<ul>
HTML;
$items = ["Inicio", "Alan Turing", "Ada Lovelace", "Contacto"];
foreach ($items as $k => $v)
  echo "<li".($k==$activo?" class='activo'":"").">".
       "<a href='pag_unica.php?p=".($k)."'>".$v."</a></li>";
echo <<< HTML
</ul>
</nav>
HTML;
}

?>