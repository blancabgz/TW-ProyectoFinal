<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Inicio";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

echo <<< HTML
    <main>
            <section id='contenido' class='borde_verde'>
                <h1> $titulo </h1>
                <p> Que las vacunas  inmunizan contra enfermedades es algo que ya sabes, pero seguro que no tienes ni idea de quién creó la primera vacuna ni cómo lo consiguió.
                Según la Organización Mundial de la Salud (OMS), una vacuna es aquella preparación destinada a generar inmunidad contra una enfermedad. Esto se logra
                estimulando la producción de anticuerpos. Las vacunas pueden tratarse de una suspensión de microorganismos muertos o atenuados, o de productos o derivados
                de microorganismos. Para su administración el método más habitual es la inyección, aunque algunas se administran con un vaporizador nasal u oral, sobre todo en
                niños. Para conocer la primera vacuna hay que remontarse hasta el año 1796, en ese año Europa sufría una terrible epidemia de viruela. En Inglaterra el doctor
                Edward Jenner observó que las mujeres que ordeñaban vacas se contagiaban de viruela vacuna y tras recuperarse se volvían inmunes a su mortal variante humana.
                Al observar este hecho, Jenner tomó viruela de la granjera Sarah Nelmes e insertó el fluido en el niño de ocho años James Phipps, quien desarrolló la enfermedad
                y se recuperó 48 días más tarde. Más tarde se infectó al niño con virus de la viruela humana, pero no mostró síntomas de la enfermedad. La primera vacuna fue
                descubierta y, de hecho, la viruela ha sido la única enfermedad erradicada de la tierra. </p>
                <img src='../vacuna.png' alt="Vacuna" width='100'>
            </section>
    HTML;

HTMLformulario($rol);
HTMLfooter();
?>
