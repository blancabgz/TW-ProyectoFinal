<?php
function formularioUSU01($titulo, $user, $form){
echo <<< HTML
    <main>
        <section id="contenido" class="borde_verde formulario">
            <h1> $titulo </h1>
            <form action="$form"  method="post" enctype="multipart/form-data" id="add">
              <div class="form-group">
                <label> Fotografía: <input type="file"  name="fotografia" value="Seleccionar fotografía"></label>
              </div>
              <div class="form-group">
                <label> Nombre: <input class="form-control" type="text" name="nombre"></label>
              </div>
              <div class="form-group">
                <label> Apellidos: <input type="text" class="form-control" name="apellidos"></label>
              </div>
              <div class="form-group">
                <label> DNI: <input type="text" class="form-control" name="dni"></label>
              </div>
              <div class="form-group">
                <label> Email: <input type="text" class="form-control" name="email"></label>
              </div>
              <div class="form-group">
                <label> Teléfono: <input type="text" class="form-control" name="telefono"></label>
              </div>
              <div class="form-group">
                <label> Fecha nac: <input type="date" class="form-control" name="fecha"></label>
              </div>
              <div class="form-group">
                <label> Sexo:
                    <label> <input type="radio" name="sexo" value="M"> Mujer </label>
                    <label> <input type="radio" name="sexo" value="H"> Hombre </label>
                </label>
              </div>
              <div class="form-group">
                <label> Clave:
                    <input class="form-control" type="password" name="clave">
                    <input class="form-control" type="password" name="clave2">
                </label>
              </div>
              <div class="form-group">
                <label> Rol: <select name="rol">
                    <option value="P"> Paciente </option>
                    <option value="S"> Sanitario </option>
                    <option value="A"> Administrador</option>
                </select></label>
              </div>

HTML;
        if($user == 'A'){
            echo <<< HTML
            <label> Estado: <select name="estado">
                    <option value="A"> Activo</option>
                    <option value="I"> Inactivo</option>
                </select></label>
            HTML;
        }
        echo "
        <div class='form-group form boton'>
          <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
        </div>
        </form>
        </section>";
}

function formularioUSU02($datos, $validar, $form, $titulo, $user){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '2');
    $sexo = formularioSexo($campos['sexo'], '0');
    $rol = formularioRol($campos['rol'], '0');
    $estado = formularioEstado($campos['estado'], '0');

    if($validar == 'e'){
        $campos['clave2'] = $campos['clave'];
    }

    echo "
    <main>
    <section id='contenido' class='borde_verde formulario'>
        <h1>".$titulo."</h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
        <div class='form-group'>
            ".$foto."
            </div>
            <div class='form-group'>
            <label> Nombre: <input type='text' name='nombre' value='".$campos['nombre']."' ></label>
            </div>
            <div class='form-group'>
            <label> Apellidos: <input type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            </div>
            <div class='form-group'>
            <label> DNI: <input type='text' name='dni' value='".$campos['dni']."' ></label>
            </div>
            <div class='form-group'>
            <label> Email: <input type='text' name='email' value='".$campos['email']."'></label>
            </div>
            <div class='form-group'>
            <label> Teléfono: <input type='text' name='telefono' value='".$campos['telefono']."' ></label>
            </div>
            <div class='form-group'>
            <label> Fecha nac: <input type='date' name='fecha' value='".$campos['fecha']."' ></label>
            </div>
            <div class='form-group'>
            <label> Sexo: ".$sexo." </label>
            </div>
            <div class='form-group'>
            <label> Clave:
                <input type='password' name='clave' value='".$campos['clave']."'>
                <input type='password' name='clave2' value='".$campos['clave2']."'>
            </label>
            </div>
            <div class='form-group'>
            <label> Rol:".$rol." </label>";
            if($user != 'V'){
               echo "<label> Estado: ".$estado."
                </label> </div>";
            }
            echo "
              <div class='form-group form boton'>
                <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
              </div>
        </form>";
    if($validar != 'e'){
        foreach($validar as $k){
            echo "<p> ".$k."</p>";
        }
    }
    echo "</section>";
}

//$accion: a(añadir), v(ver), e(editar), b(borrar)
function formularioUSU03($datos, $accion, $form, $titulo, $user){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '3');
    $sexo = formularioSexo($campos['sexo'], '1');
    $rol = formularioRol($campos['rol'], '1');
    $estado = formularioEstado($campos['estado'], '1');
    $submit = formularioSubmit($accion);

    if($accion == 'v' || $accion == 'b' || $accion == 'e') $campos['clave2'] = $campos['clave'];

    echo "
    <main>
    <section id='contenido' class='borde_verde formulario'>
        <h1> ".$titulo." </h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
            <div class='form-group'>
            ".$foto."
            </div>
            <div class='form-group'>
            <label> Nombre: <input readonly type='text' name='nombre' value='".$campos['nombre']."' ></label>
            </div>
            <div class='form-group'>
            <label> Apellidos: <input readonly type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            </div>
            <div class='form-group'>
            <label> DNI: <input readonly type='text' name='dni' value='".$campos['dni']."' ></label>
            </div>
            <div class='form-group'>
            <label> Email: <input readonly type='text' name='email' value='".$campos['email']."'></label>
            </div>
            <div class='form-group'>
            <label> Teléfono: <input readonly type='text' name='telefono' value='".$campos['telefono']."' ></label>
            </div>
            <div class='form-group'>
            <label> Fecha nac: <input readonly type='date' name='fecha' value='".$campos['fecha']."' ></label>
            </div>
            <div class='form-group'>
            <label> Sexo: ".$sexo." </label>
            </div>
            <div class='form-group'>
            <label> Clave:
                <input readonly type='password' name='clave' value='".$campos['clave']."'>
                <input readonly type='password' name='clave2' value='".$campos['clave2']."'>
            </label>
            </div>
            <div class='form-group'>
            <label> Rol:".$rol." </label></div>";
            if($user != 'V'){
                echo "<div class='form-group'><label> Estado: ".$estado."
                </label></div> ";
            }
            echo "<div class='form-group form boton'> ".$submit."

        </div></form> </section>";
}

/**/
function formularioUSU05($datos, $validar, $titulo){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '2');
    $sexo = formularioSexo($campos['sexo'], '1');
    $campos['clave2'] = $campos['clave'];

    echo "
    <main>
    <section id='contenido' class='borde_verde formulario'>
        <h1>".$titulo."</h1>
        <form action='edit.php' method='post' enctype='multipart/form-data' id='add'>
        <div class='form-group'>
            ".$foto."
        </div>
        <div class='form-group'>
            <label> Nombre: <input readonly type='text' name='nombre' value='".$campos['nombre']."' ></label>
            </div>
            <div class='form-group'>
            <label> Apellidos: <input readonly type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            </div>
            <div class='form-group'>
            <label> DNI: <input readonly type='text' name='dni' value='".$campos['dni']."' ></label>
            </div>
            <div class='form-group'>
            <label> Email: <input type='text' name='email' value='".$campos['email']."'></label>
            </div>
            <div class='form-group'>
            <label> Teléfono: <input type='text' name='telefono' value='".$campos['telefono']."' ></label>
            </div>
            <div class='form-group'>
            <label> Fecha nac: <input readonly type='date' name='fecha' value='".$campos['fecha']."' ></label>
            </div>
            <div class='form-group'>
            <label> Sexo: ".$sexo." </label>
            </div>
            <div class='form-group'>
            <label> Clave:
                <input type='password' name='clave' value='".$campos['clave']."'/>
                <input type='password' name='clave2' value='".$campos['clave2']."'/>
            </label>
            </div>
            <div class='form-group form boton'>
            <input type='submit' name='enviarDatos' value='Enviar datos'/>
            </div>

        </form>
    ";
    if($validar != 'e'){
        foreach($validar as $k){
            echo "<p> ".$k."</p>";
        }
    }
    echo "</section>";
}


//$accion: a(añadir), v(ver), e(editar), b(borrar)
function formularioUSU06($datos, $accion, $form, $titulo){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '3');
    $sexo = formularioSexo($campos['sexo'], '1');
    $campos['clave2'] = $campos['clave'];
    $submit = formularioSubmit($accion);

    echo "
    <main>
    <section id='contenido' class='borde_verde formulario'>
        <h1> ".$titulo." </h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
        <div class='form-group'>
            ".$foto."
        </div>
        <div class='form-group'>
            <label> Nombre: <input readonly type='text' name='nombre' value='".$campos['nombre']."' ></label>
            </div>
            <div class='form-group'>
            <label> Apellidos: <input readonly type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            </div>
            <div class='form-group'>
            <label> DNI: <input readonly type='text' name='dni' value='".$campos['dni']."' ></label>
            </div>
            <div class='form-group'>
            <label> Email: <input readonly type='text' name='email' value='".$campos['email']."'></label>
            </div>
            <div class='form-group'>
            <label> Teléfono: <input readonly type='text' name='telefono' value='".$campos['telefono']."' ></label>
            </div>
            <div class='form-group'>
            <label> Fecha nac: <input readonly type='date' name='fecha' value='".$campos['fecha']."' ></label>
            </div>
            <div class='form-group'>
            <label> Sexo: ".$sexo." </label>
            </div>
            <div class='form-group'>
            <label> Clave:
                <input readonly type='password' name='clave' value='".$campos['clave']."'/>
                <input readonly type='password' name='clave2' value='".$campos['clave2']."'/>
            </label>
            </div>
            <div class='form-group'>
            ".$submit."
        </form>
    ";

    echo "</section>";
}


function formularioSexo($datos, $desactivado){

    if($desactivado == '1'){
        if($datos == 'M'){
            $sexo = "<label> <input type='radio' name='sexo' value='M' checked> Mujer </label>
            <label> <input type='radio' name='sexo' value='H' disabled> Hombre </label>";
        }
        else if($datos == 'H'){
            $sexo = "<label> <input type='radio' name='sexo' value='M' disabled> Mujer </label>
            <label> <input type='radio' name='sexo' value='H' checked> Hombre </label>";
        }else{
            $sexo = "<label> <input type='radio' name='sexo' value='M' disabled> Mujer </label>
            <label> <input type='radio' name='sexo' value='H' disabled> Hombre </label>";
        }
    }else{
        if($datos == 'M'){
            $sexo = "<label> <input type='radio' name='sexo' value='M' checked> Mujer </label>
            <label> <input type='radio' name='sexo' value='H'> Hombre </label>";
        }
        else if($datos == 'H'){
            $sexo = "<label> <input type='radio' name='sexo' value='M'> Mujer </label>
            <label> <input type='radio' name='sexo' value='H' checked> Hombre </label>";
        }else{
            $sexo = "<label> <input type='radio' name='sexo' value='M'> Mujer </label>
            <label> <input type='radio' name='sexo' value='H'> Hombre </label>";
        }
    }

    return $sexo;
}

function formularioRol($datos, $desactivado){

    if($desactivado == '0'){
        if($datos == 'A'){
            $rol = "<select name='rol'>
            <option value='P'> Paciente </option>
            <option value='S'> Sanitario </option>
            <option value='A' selected> Administrador</option></select>";
        }
        else if($datos == 'S'){
            $rol = "<select name='rol'>
            <option value='P'> Paciente </option>
            <option value='S' selected> Sanitario </option>
            <option value='A'> Administrador</option></select>";
        }
        else if($datos == 'P'){
            $rol = "<select name='rol'>
            <option value='P' selected> Paciente </option>
            <option value='S'> Sanitario </option>
            <option value='A'> Administrador</option></select>";
        }else{
            $rol = "<select name='rol'>
            <option value='P'> Paciente </option>
            <option value='S'> Sanitario </option>
            <option value='A'> Administrador</option></select>";
        }
    }else{
        if($datos == 'A'){
            $rol = "<select name='rol'>
            <option value='P' disabled> Paciente </option>
            <option value='S' disabled> Sanitario </option>
            <option value='A' selected> Administrador</option></select>";
        }
        else if($datos == 'S'){
            $rol = "<select name='rol'>
            <option value='P' disabled> Paciente </option>
            <option value='S' selected> Sanitario </option>
            <option value='A' disabled> Administrador</option></select>";
        }
        else if($datos == 'P'){
            $rol = "<select name='rol'>
            <option value='P' selected> Paciente </option>
            <option value='S' disabled> Sanitario </option>
            <option value='A' disabled> Administrador</option></select>";
        }else{
            $rol = "<select name='rol'>
            <option value='P' disabled> Paciente </option>
            <option value='S' disabled> Sanitario </option>
            <option value='A' disabled> Administrador</option></select>";
        }
    }
    return $rol;
}

function formularioEstado($datos, $desactivado){

    if( $desactivado == '0'){
        if($datos == 'I'){
            $estado = "<select name='estado'>
            <option value='A'> Activo</option>
            <option value='I' selected> Inactivo</option> </select>";
        }
        else if($datos == 'A'){
            $estado = "<select name='estado'>
            <option value='A' selected> Activo</option>
            <option value='I'> Inactivo</option> </select>";
        }else{
            $estado = "<select name='estado'>
            <option value='A'> Activo</option>
            <option value='I'> Inactivo</option> </select>";
        }
    }else{
        if($datos == 'I'){
            $estado = "<select name='estado'>
            <option value='A'disabled> Activo</option>
            <option value='I' selected> Inactivo</option> </select>";
        }
        else if($datos == 'A'){
            $estado = "<select name='estado'>
            <option value='A' selected> Activo</option>
            <option value='I' disabled> Inactivo</option> </select>";
        }else{
            $estado = "<select name='estado' disabled>
            <option value='A'> Activo</option>
            <option value='I'> Inactivo</option> </select>";
        }
    }

    return $estado;
}

//$accion: a(añadir), v(ver), e(editar), b(borrar)
function formularioSubmit($accion){
    $input = '';
    if($accion == 'a' || $accion == 'e'){
        $input = "<input class='btn' type='submit' name='validarDatos' value='Validar datos si son correctos'>";
    }else if($accion == 'b'){
        $input = "<input class='btn' type='submit' name='borrarUsuario' value='Borrar usuario definitivamente'>";
    }
    return $input;
}

//formulario para mostrar o no la imagen
function formularioFoto($foto, $n){
    $fotografia = '';
    //si no se ha puesto imagen
    if($foto == ''){
        //y es el formulario USU03
        if($n == '3'){
            $fotografia = "<label> Fotografía: <input disabled type='file' name='fotografia' value='Seleccionar fotografía'></label>";
        }
        elseif($n == '2'){
            $fotografia = "<label> Fotografía: <input type='file' name='fotografia' value='Seleccionar fotografía'></label>";
        }
    }
    //si se ha puesto imagen
    else{
        if($n == '3'){
            $fotografia = "<img src='data:img/png; base64, ".$foto." alt='imagen'/>
            <input type='hidden' name='foto' value='".$foto."'/>";
        }elseif($n == '2'){
            $fotografia = "<img src='data:img/png; base64, ".$foto." alt='imagen'/>
            <label> Fotografía: <input type='file' name='fotografia' value='Seleccionar fotografía'></label>
            <input type='hidden' name='foto' value='".$foto."'/>";
        }
    }

    return $fotografia;
}
?>
