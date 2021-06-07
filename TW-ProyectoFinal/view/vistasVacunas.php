<?php

/* VISTAS DE LAS VACUNAS */
function mostrarListaVacunas($lista, $titulo){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde col-md-9'>
        <h1> $titulo </h1>
    HTML;

    foreach($lista as $k){
        echo "
            ".$k['nombre']."
            ".$k['acronimo']."
            <form action='../controller/editVac.php' method='post'>
                <input type='submit' name='editVac' value='Editar'/>
                <input type='hidden' name='idVac' value='".$k['id']."'/>
            </form>
            <form action='../controller/deleteVac.php' method='post'>
                <input type='submit' name='deleteVac' value='Borrar'/>
                <input type='hidden' name='idVac' value='".$k['id']."'/>
            </form>
        ";
    }
    echo "</section>";
}
?>
