<?php
function formularioVAC_CAR01($titulo, $form, $nombre, $id){

    echo <<< HTML
        <main class="row">
        <section id="contenido" class="borde_verde formulario col-md-9">
            <h1> $titulo </h1>
            <form action="$form"  method="post" enctype="multipart/form-data" id="add">
                <div class="form-group">
                    <label> Nombre: <input readonly class="form-control" type="text" name="nombre" value="$nombre"></label>
                </div>
                <div class="form-group">
                    <label> Fecha: <input type="date" class="form-control" name="fecha"></label>
                </div>
                <div class="form-group">
                    <label> Fabricante: <input type="text" class="form-control" name="fabricante"></label>
                </div>
                <div class="form-group">
                    <label> Comentarios: <input type="text" class="form-control" name="comentarios"></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='validarDatos' value='Validar datos'>
                    <input class='btn' type='hidden' name='calendario_id' value="$id">
                </div>
            </form>
        </section>
    HTML;
}

function formularioVAC_CAR02($titulo, $form, $datos, $validar, $accion){

    echo "
        <main class='row'>
        <section id='contenido' class='borde_verde formulario col-md-9'>
            <h1> $titulo </h1>
            <form action='".$form."'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input readonly class='form-control' type='text' name='nombre' value='".$datos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Fecha: <input type='date' class='form-control' name='fecha' value='".$datos['fecha']."'></label>
                </div>
                <div class='form-group'>
                    <label> Fabricante: <input type='text' class='form-control' name='fabricante' value='".$datos['fabricante']."'></label>
                </div>
                <div class='form-group'>
                    <label> Comentarios: <input type='text' class='form-control' name='comentarios' value='".$datos['comentarios']."'></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='validarDatos' value='Validar datos'>
                    <input type='hidden' name='calendario_id' value='".$datos['calendario_id']."'>";
                    if($accion == 'e'){
                        echo "<input type='hidden' name='id' value='".$datos['id']."'>";
                    }
        echo "  </div>
            </form>";
    if($validar != ''){
        foreach($validar as $k){
            echo "<p> ".$k."</p>";
        }
    }
    echo "</section>";
}

function formularioVAC_CAR03($titulo, $form, $datos){

    echo "
        <main class='row'>
        <section id='contenido' class='borde_verde formulario col-md-9'>
            <h1> $titulo </h1>
            <form action='".$form."'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input readonly class='form-control' type='text' name='nombre' value='".$datos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Fecha: <input readonly type='date' class='form-control' name='fecha' value='".$datos['fecha']."'></label>
                </div>
                <div class='form-group'>
                    <label> Fabricante: <input readonly type='text' class='form-control' name='fabricante' value='".$datos['fabricante']."'></label>
                </div>
                <div class='form-group'>
                    <label> Comentarios: <input readonly type='text' class='form-control' name='comentarios' value='".$datos['comentarios']."'></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='eliminarDatos' value='Eliminar vacunación'>
                    <input type='hidden' name='id' value='".$datos['id']."'>
                </div>
            </form>
        </section>";
}
?>