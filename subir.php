<?php
require_once "../view/vistasHTML.php";
require_once "../model/basedatos.php";
$titulo="Presentación";
$rol = 'V';
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

if(isset($_POST['enviado'])){
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    
    if($check != false){
        //$image = file_get_contents($_FILES['foto']['tmp_name']);
        $mensaje = insertarBD($_FILES['foto']['tmp_name']);

        if($mensaje == "Imagen añadida con éxito"){
            $foto = recuperarImagen();
            echo "<main>
            <section id='contenido' class='borde_verde'>
            <h1> Imagen subida </h1>
            <img src='data:image/png;base64, ".$foto." alt='imagen'/>
            </section>";
        }
        else{
        echo <<< HTML
            <main>
                <section id='contenido' class='borde_verde'>
                    <h1>Subir imagen</h1>
                    <p> $mensaje </p>
                </section>
        HTML;
        }
    }

}
else{
    echo <<< HTML
        <main>
            <section id='contenido' class='borde_verde'>
                <h1>Subir imagen</h1>
                <form action='subir.php' method='post' enctype='multipart/form-data'>
                    Fotografía::
                    <input type="file" name="foto" value="Seleccionar fotografía"/>
                    <input type="submit" name='enviado' value="Enviar Foto"/>
                </form>
            </section>
    HTML;
}

HTMLformulario($rol);
HTMLfooter();

function insertarBD($foto){
    $bd = conectarBD();
    $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';
    $datos = base64_encode(file_get_contents($foto));
    //$datos = mysqli_real_escape_string($bd, $foto);
    $consulta = "UPDATE usuario SET fotografia='$datos' WHERE dni='01234567I'";
    $consulta_res = mysqli_query($bd, $consulta);
    
    //si ha habido error
    if(!$consulta_res){
        $mensaje = "Error de inserción, vuelva a intentarlo.";
        echo mysqli_error($bd);
    }
    else{
        $mensaje = "Imagen añadida con éxito";
    }
    
    desconectarBD($bd);
    
    //0: éxito;   1: dni existe     2: error inserción      3: dni null
    return $mensaje;    
}

function recuperarImagen(){
    $bd = conectarBD();
    $mensaje = 'Se desconoce el error. Vuelva a intentarlo.';

    $consulta = "SELECT fotografia FROM usuario WHERE dni='01234567I'";
    $consulta_res = mysqli_query($bd, $consulta);
    $valores = mysqli_fetch_array($consulta_res, MYSQLI_ASSOC);
    
    //si ha habido error
    if(!$consulta_res){
        $mensaje = "Error de inserción, vuelva a intentarlo.";
        echo mysqli_error($bd);
    }
    else{
        $mensaje = "Imagen conseguida con éxito";
    }
    
    desconectarBD($bd);
    
    //0: éxito;   1: dni existe     2: error inserción      3: dni null
    return $valores['fotografia'];
}
?>