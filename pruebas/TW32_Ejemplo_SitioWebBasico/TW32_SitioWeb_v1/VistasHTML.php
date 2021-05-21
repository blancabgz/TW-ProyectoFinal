<?php
function HTMLinicio($titulo) {
echo <<< HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="estilo.css">
<title>$titulo</title>
</head>
<body>
HTML;
}

function HTMLfin() {
echo <<< HTML
</body>
</html>
HTML;
}

function HTMLnav($activo) {
echo <<< HTML
<nav>
<h2>Índice</h2>
<ul>
HTML;
$items = ["Inicio", "Alan Turing", "Ada Lovelace", "Contacto"];
$links = ["pag_inicio.php", "pag_alan.php", "pag_ada.php", "pag_contacto.php"];
foreach ($items as $k => $v)
  echo "<li".($k==$activo?" class='activo'":"").">"."<a href='".$links[$k]."'>".$v."</a></li>";
echo <<< HTML
</ul>
</nav>
HTML;
}

function HTMLpag_inicio() {
echo <<< HTML
<main>
<h2>Personajes históricos</h2>
<p>Pulsa para ver la biografía de alguno de ellos.</p>
</main>	
HTML;
}

function HTMLpag_contacto() {
echo <<< HTML
<main>
<h2>Contacto</h2>
<p>Envía un correo a PepitoPerez@servidor.de.correo.com</p>
</main>	
HTML;
}

function HTMLpag_alan() {
echo <<< HTML
<main>
<h2>Alan Turing</h2>
<p>Alan Mathison Turing, OBE (Paddington, Londres, 23 de junio de 1912-Wilmslow, Cheshire, 7 de junio de 1954), fue un matemático, lógico, científico de la computación, criptógrafo, filósofo, maratoniano y corredor de ultra distancia británico.</p>
<p>Es considerado uno de los padres de la ciencia de la computación y precursor de la informática moderna. Proporcionó una influyente formalización de los conceptos de algoritmo y computación: la máquina de Turing. Formuló su propia versión de la hoy ampliamente aceptada tesis de Church-Turing (1936).</p>
</main>	
HTML;
}

function HTMLpag_ada() {
echo <<< HTML
<main>
<h2>Ada Lovelace</h2>
<p>Augusta Ada King, Condesa de Lovelace , (nacida Augusta Ada Byron en Londres, 10 de diciembre de 1815 - Londres, 27 de noviembre de 1852), conocida habitualmente como Ada Lovelace, fue una matemática y escritora británica conocida principalmente por su trabajo sobre la máquina calculadora mecánica de uso general de Charles Babbage, la denominada máquina analítica. Entre sus notas sobre la máquina se encuentra lo que se reconoce hoy como el primer algoritmo destinado a ser procesado por una máquina, por lo que se la considera como la primera programadora de ordenadores.</p>
</main>	
HTML;
}

function HTMLfooter() {
echo <<< HTML
<footer>
<small>(C) Pepito Pérez</small>
</footer>
HTML;
}

?>