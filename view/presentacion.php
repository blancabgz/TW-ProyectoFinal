<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Presentación";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

echo <<< HTML
        <main>
            <section id='contenido' class='borde_verde'>
                <h1> Presentación </h1>
                <p> Administrador: Usuario: 12345678A Contraseña: 123456 </p>
                <p> Colaborador: Usuario: 98765432C Contraseña: 654321 </p>
                <p> Ahora eres $rol</p>
            </section>
HTML;
HTMLformulario($rol);
HTMLfooter();
?>