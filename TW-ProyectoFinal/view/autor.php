<?php
require_once "../controller/check_login.php";
require_once "../view/vistasComunes.php";
$titulo="Autor";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);
echo <<< HTML
        <main>
            <section id='contenido' class='borde_verde'>
                <h1> Autoras </h1>
                <p> Esta práctica ha sido realizada por Blanca Abril González y Paula Santos Ortega
                para la asignatura de Tecnologías Web en el curso 20/21.</p>
            </section>
HTML;
HTMLformulario($rol);
HTMLfooter();
?>
