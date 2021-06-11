<?php
/* FORMULARIOS DE VACUNAS */
//formulario vacío para vacunas
function formularioVAC01($titulo, $form){

    echo <<< HTML
        <main class="row">
        <section id="contenido" class="borde_verde formulario col-md-9">
            <h1> $titulo </h1>
            <form action="$form"  method="post" enctype="multipart/form-data" id="add">
                <div class="form-group">
                    <label> Nombre: <input class="form-control" type="text" name="nombre"></label>
                </div>
                <div class="form-group">
                    <label> Acrónimo: <input type="text" class="form-control" name="acronimo"></label>
                </div>
                <div class="form-group">
                    <label> Descripción: <input type="text" class="form-control" name="comentarios"></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
                </div>
            </form>
        </section>
    HTML;
}


//formulario para vacunas relleno para modificar
function formularioVAC02($datos, $titulo, $form, $validar){
    $campos = procesarDatosVacuna($datos);

    echo "
        <main class='row'>
        <section id='contenido' class='borde_verde formulario col-md-9'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input class='form-control' type='text' name='nombre' value='".$campos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Acrónimo: <input type='text' class='form-control' name='acronimo' value='".$campos['acronimo']."'></label>
                </div>
                <div class='form-group'>
                    <label> Descripción: <input type='text' class='form-control' name='comentarios' value='".$campos['comentarios']."'></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
                </div>
            </form>";

    if($validar != ''){
        foreach($validar as $k){
            echo "<p> ".$k."</p>";
        }
    }

    echo "</section>";
}

//formulario para vacunas relleno para no modificar
function formularioVAC03($datos, $titulo, $form, $accion){
    $campos = procesarDatosVacuna($datos);

    $submit = "<input class='btn' type='submit' name='validarDatos' value='Validar datos'>";

    if($accion == 'bV'){
        $submit = "<input class='btn' type='submit' name='borrarVac' value='Borrar vacuna definitivamente'>";
    }

    echo "
        <main class='row'>
        <section id='contenido' class='borde_verde formulario col-md-9'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input readonly class='form-control' type='text' name='nombre' value='".$campos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Acrónimo: <input readonly type='text' class='form-control' name='acronimo' value='".$campos['acronimo']."'></label>
                </div>
                <div class='form-group'>
                    <label> Descripción: <input readonly type='text' class='form-control' name='comentarios' value='".$campos['comentarios']."'></label>
                </div>
                <div class='form-group form boton'>".$submit." </div>
            </form>
        </section>";
}
?>
