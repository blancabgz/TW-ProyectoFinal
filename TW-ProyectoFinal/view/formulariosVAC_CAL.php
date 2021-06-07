<?php




/* FORMULARIOS DE VACUNAS PARA CALENDARIO */
function formularioVAC_CAL01($titulo, $form, $vacunas){
    //$campos = procesarDatosVacuna($datos);

    echo "
        <main>
        <section id='contenido' class='borde_verde formulario'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre de la vacuna: <select name='idvacuna'>";
                    foreach($vacunas as $v){
                        echo "<option value='".$v['id']."'>".$v['nombre']."</option>";
                    }
                echo "   </select> </label>
                </div>
                <div class='form-group'>
                    <label> Sexo:
                        <label> <input type='radio' name='sexo' value='M'> Mujeres </label>
                        <label> <input type='radio' name='sexo' value='H'> Hombres </label>
                        <label> <input type='radio' name='sexo' value='T' default> Para todas las personas </label>
                    </label>
                </div>
                <div class='form-group'>
                    <label> Intervalo de tiempo de administración:
                        <label> Mes de inicio: <input type='text' class='form-control' name='meses_ini'> </label>
                        <label> Mes de fin: <input type='text' class='form-control' name='meses_fin'> </label>
                        <p> Para expresar los meses en años, multiplíquelos por 12. </p> 
                    </label>
                </div>
                <div class='form-group'>
                    <label> Tipo: <select name='tipo'>
                        <option value='S'> Administración Sistemática </option>
                        <option value='N'> Administración en personas susceptibles o no vacunadas con anterioridad </option>
                        <option value='R'> Administración en recién nacidos </option>
                        </select>
                    </label>
                </div>
                <div class='form-group'>
                    <label> Comentarios: <input type='text' class='form-control' name='comentarios' value=''></label>
                </div>
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
                </div>
            </form>
        </section>";
}

function formularioVAC_CAL02($datos, $titulo, $form, $validar, $vacunas){

    $sexo = formularioSexoCalendario($datos, 0);
    $tipo = formularioTipoCalendario($datos, 0);
    
    echo "
        <main>
        <section id='contenido' class='borde_verde formulario'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre de la vacuna: <select name='idvacuna'>";
                    foreach($vacunas as $v){
                        if($v['id'] == $datos['idvacuna']){
                            echo "<option value='".$v['id']."' selected>".$v['nombre']."</option>";    
                        }
                        else{
                            echo "<option value='".$v['id']."'>".$v['nombre']."</option>";
                        }
                    }
                echo "   </select> </label>
                </div>
                <div class='form-group'>
                    <label> Sexo: ".$sexo."</label>
                </div>
                <div class='form-group'>
                    <label> Intervalo de tiempo de administración:
                        <label> Mes de inicio: <input type='text' class='form-control' name='meses_ini' value='".$datos['meses_ini']."'> </label>
                        <label> Mes de fin: <input type='text' class='form-control' name='meses_fin' value='".$datos['meses_fin']."'> </label>
                    </label>
                </div>
                <div class='form-group'> <label> <select name='tipo'>".$tipo." </select> </label>
                </div>
                <div class='form-group'>
                    <label> Comentarios: <input type='text' class='form-control' name='comentarios' value='".$datos['comentarios']."'></label>
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

function formularioVAC_CAL03($post, $titulo, $form, $vacunas, $submit){
    $datos = procesarDatosVacunaCalendario($post, $vacunas);
    $sexo = formularioSexoCalendario($datos, 1);
    $tipo = formularioTipoCalendario($datos, 1);

    if($submit == 'b'){
        $submit = "<input class='btn' type='submit' name='borrarVac' value='Borrar del Calendario'>";
    }
    else{
        $submit = "<input class='btn' type='submit' name='validarDatos' value='Validar datos'>";
    }

    echo "
        <main>
        <section id='contenido' class='borde_verde formulario'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre de la vacuna: <select name='idvacuna'>";
                    foreach($vacunas as $v){
                        if($v['id'] == $datos['idvacuna']){
                            echo "<option value='".$v['id']."' selected>".$v['nombre']."</option>";    
                        }
                        else{
                            echo "<option value='".$v['id']."' disabled>".$v['nombre']."</option>";
                        }
                    }
                echo "   </select> </label>
                </div>
                <div class='form-group'><label> Sexo: ".$sexo."</label></div>
                <div class='form-group'>
                    <label> Intervalo de tiempo de administración:
                        <label> Mes de inicio: <input readonly type='text' class='form-control' name='meses_ini' value='".$datos['meses_ini']."'> </label>
                        <label> Mes de fin: <input readonly type='text' class='form-control' name='meses_fin' value='".$datos['meses_fin']."'> </label>
                    </label>
                </div>
                <div class='form-group'> <label> <select name='tipo'>".$tipo." </select> </label>
                </div>
                <div class='form-group'>
                    <label> Comentarios: <input readonly type='text' class='form-control' name='comentarios' value='".$datos['comentarios']."'></label>
                </div>
                <div class='form-group form boton'>".$submit." </div>
            </form>
        </section>";
}

function formularioSexoCalendario($datos, $deshabilitado){

    $campos = array(
        'M' => 'Mujeres',
        'H' => 'Hombres',
        'T' => 'Para todas las personas',
    );
    $indice = ['M', 'H', 'T'];
    $sexo = '';

    foreach($indice as $i){
        if(isset($datos['sexo'])){
            //si no está en disabled
            if($deshabilitado == 0){
                if($datos['sexo'] == $i){
                    $sexo .= "<label> <input type='radio' name='sexo' value='".$i."' checked> ".$campos[$i]." </label>";
                }
                else{
                    $sexo .= "<label> <input type='radio' name='sexo' value='".$i."'> ".$campos[$i]." </label>";
                }
            }
            //si está en disabled
            else{
                if($datos['sexo'] == $i){
                    $sexo .= "<label> <input type='radio' name='sexo' value='".$i."' checked> ".$campos[$i]." </label>";
                }
                else{
                    $sexo .= "<label> <input type='radio' name='sexo' value='".$i."' disabled> ".$campos[$i]." </label>";
                }
            }
        }
        else{
            $sexo .= "<label> <input type='radio' name='sexo' value='".$i."'> ".$campos[$i]." </label>";
        }
    }

    return $sexo;
}

function formularioTipoCalendario($datos, $deshabilitado){

    $campos = array(
        'S' => 'Administración Sistemática',
        'N' => 'Administración en personas susceptibles o no vacunadas con anterioridad',
        'R' => 'Administración en recién nacidos',
    );
    $indice = ['S', 'N', 'R'];
    $tipo = '';

    foreach($indice as $i){
        if(isset($datos['tipo'])){
            //si no está en disabled
            if($deshabilitado == 0){
                if($datos['tipo'] == $i){
                    $tipo .= "<option value='".$i."' selected> ".$campos[$i]." </option>";
                }
                else{
                    $tipo .= "<option value='".$i."'> ".$campos[$i]." </option>";
                }
            }
            //si está en disabled
            else{
                if($datos['tipo'] == $i){
                    $tipo .= "<option value='".$i."' selected> ".$campos[$i]." </option>";
                }
                else{
                    $tipo .= "<option value='".$i."' disabled> ".$campos[$i]." </option>";
                }
            }
        }
        else{
            $tipo .= "<option value='".$i."'> ".$campos[$i]." </option>";
        }
    }

    return $tipo;
}
?>