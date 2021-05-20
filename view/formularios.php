<?php
function formularioUSU01(){
echo <<< HTML
    <main>
        <section id="contenido" class="borde_verde">
            <h1> Nuevo usuario </h1>
            <form action="add.php" method="post" enctype="multipart/form-data" id="add">
                <label> Fotografía: <input type="file" name="fotografia" value="Seleccionar fotografía"></label>
                <label> Nombre: <input type="text" name="nombre"></label>
                <label> Apellidos: <input type="text" name="apellidos"></label>
                <label> DNI: <input type="text" name="dni"></label>
                <label> Email: <input type="text" name="email"></label>
                <label> Teléfono: <input type="text" name="telefono"></label>
                <label> Fecha nac: <input type="date" name="fecha"></label>
                <label> Sexo:
                    <label> <input type="radio" name="sexo" value="M"> Mujer </label>
                    <label> <input type="radio" name="sexo" value="H"> Hombre </label>
                </label>
                <label> Clave: 
                    <input type="password" name="clave">
                    <input type="password" name="clave2">
                </label>
                <label> Rol: <select name="rol">
                    <option value="C"> Colaborador</option>
                    <option value="A"> Administrador</option>
                </select></label>
                <label> Estado: <select name="estado">
                    <option value="A"> Activo</option>
                    <option value="I"> Inactivo</option>
                </select></label>
                <input type="submit" name="enviarDatos" value="Enviar datos">
            </form>
        </section>
HTML;
}

function formularioUSU02($datos, $validar, $form, $titulo){
    $campos = procesarDatos($datos);
    
    $sexo = formularioSexo($campos['sexo'], '0');
    $rol = formularioRol($campos['rol'], '0');
    $estado = formularioEstado($campos['estado'], '0');

    if($validar == 'e'){
        $campos['clave2'] = $campos['clave'];
    }

    echo "
    <main>
    <section id='contenido' class='borde_verde'>
        <h1>".$titulo."</h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
            <label> Fotografía: <input type='file' name='fotografia' value='Seleccionar fotografía'></label>
            <label> Nombre: <input type='text' name='nombre' value='".$campos['nombre']."' ></label>
            <label> Apellidos: <input type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            <label> DNI: <input type='text' name='dni' value='".$campos['dni']."' ></label>
            <label> Email: <input type='text' name='email' value='".$campos['email']."'></label>
            <label> Teléfono: <input type='text' name='telefono' value='".$campos['telefono']."' ></label>
            <label> Fecha nac: <input type='date' name='fecha' value='".$campos['fecha']."' ></label>
            <label> Sexo: ".$sexo." </label>
            <label> Clave:
                <input type='password' name='clave' value='".$campos['clave']."'>
                <input type='password' name='clave2' value='".$campos['clave2']."'>
            </label>
            <label> Rol:".$rol." </label>
            <label> Estado: ".$estado."
            </label>
            <input type='submit' name='enviarDatos' value='Enviar datos'>
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
function formularioUSU03($datos, $accion, $form, $titulo){
    $campos = procesarDatos($datos);
    $sexo = formularioSexo($campos['sexo'], '1');
    $rol = formularioRol($campos['rol'], '1');
    $estado = formularioEstado($campos['estado'], '1');
    
    if($accion == 'v' || $accion == 'b' || $accion == 'e'){
        $campos['clave2'] = $campos['clave'];
    }
    
    if($accion == 'e' || $accion == 'a'){
        $fotografia = "<label> Fotografía: <input readonly type='file' name='fotografia' value='Seleccionar fotografía'></label>";
    }
    else{
        $fotografia = "<label> Fotografía: <input disabled type='file' name='fotografia' value='Seleccionar fotografía'></label>";
    }
    $submit = formularioSubmit($accion);

    echo "
    <main>
    <section id='contenido' class='borde_verde'>
        <h1> ".$titulo." </h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
            ".$fotografia."
            <label> Nombre: <input readonly type='text' name='nombre' value='".$campos['nombre']."' ></label>
            <label> Apellidos: <input readonly type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            <label> DNI: <input readonly type='text' name='dni' value='".$campos['dni']."' ></label>
            <label> Email: <input readonly type='text' name='email' value='".$campos['email']."'></label>
            <label> Teléfono: <input readonly type='text' name='telefono' value='".$campos['telefono']."' ></label>
            <label> Fecha nac: <input readonly type='date' name='fecha' value='".$campos['fecha']."' ></label>
            <label> Sexo: ".$sexo." </label>
            <label> Clave:
                <input readonly type='password' name='clave' value='".$campos['clave']."'>
                <input readonly type='password' name='clave2' value='".$campos['clave2']."'>
            </label>
            <label> Rol:".$rol." </label>
            <label> Estado: ".$estado."
            </label>
            ".$submit."
        </form>
    ";

    echo "</section>";
}

function formularioUSU05($datos, $validar, $form, $titulo){
    $campos = procesarDatos($datos);
    $sexo = formularioSexo($campos['sexo'], '0');
    $campos['clave2'] = $campos['clave'];

    echo "
    <main>
    <section id='contenido' class='borde_verde'>
        <h1>".$titulo."</h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
            <label> Fotografía: <input type='file' name='fotografia' value='Seleccionar fotografía'></label>
            <label> Nombre: <input type='text' name='nombre' value='".$campos['nombre']."' ></label>
            <label> Apellidos: <input type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            <label> DNI: <input type='text' name='dni' value='".$campos['dni']."' ></label>
            <label> Email: <input type='text' name='email' value='".$campos['email']."'></label>
            <label> Teléfono: <input type='text' name='telefono' value='".$campos['telefono']."' ></label>
            <label> Fecha nac: <input type='date' name='fecha' value='".$campos['fecha']."' ></label>
            <label> Sexo: ".$sexo." </label>
            <label> Clave:
                <input type='password' name='clave' value='".$campos['clave']."'/>
                <input type='password' name='clave2' value='".$campos['clave2']."'/>
            </label>
            <input type='submit' name='enviarDatos' value='Enviar datos'/>
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
    
    $sexo = formularioSexo($campos['sexo'], '1');
    $campos['clave2'] = $campos['clave'];
    $submit = formularioSubmit($accion);

    echo "
    <main>
    <section id='contenido' class='borde_verde'>
        <h1> ".$titulo." </h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
            <label> Fotografía: <input readonly type='file' name='fotografia' value='Seleccionar fotografía'></label>
            <label> Nombre: <input readonly type='text' name='nombre' value='".$campos['nombre']."' ></label>
            <label> Apellidos: <input readonly type='text' name='apellidos' value='".$campos['apellidos']."'></label>
            <label> DNI: <input readonly type='text' name='dni' value='".$campos['dni']."' ></label>
            <label> Email: <input readonly type='text' name='email' value='".$campos['email']."'></label>
            <label> Teléfono: <input readonly type='text' name='telefono' value='".$campos['telefono']."' ></label>
            <label> Fecha nac: <input readonly type='date' name='fecha' value='".$campos['fecha']."' ></label>
            <label> Sexo: ".$sexo." </label>
            <label> Clave:
                <input readonly type='password' name='clave' value='".$campos['clave']."'/>
                <input readonly type='password' name='clave2' value='".$campos['clave2']."'/>
            </label>
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
            <option value='C'> Colaborador </option>
            <option value='A' selected> Administrador</option> </select>";
        }
        else if($datos == 'C'){
            $rol = "<select name='rol'>
            <option value='C' selected> Colaborador </option>
            <option value='A'> Administrador</option> </select>";
        }else{
            $rol = "<select name='rol'>
            <option value='C'> Colaborador </option>
            <option value='A'> Administrador</option> </select>";  
        }
    }else{
        if($datos == 'A'){
            $rol = "<select name='rol'>
            <option value='C' disabled> Colaborador </option>
            <option value='A' selected> Administrador</option> </select>";
        }
        else if($datos == 'C'){
            $rol = "<select name='rol'>
            <option value='C' selected> Colaborador </option>
            <option value='A' disabled> Administrador</option> </select>";
        }else{
            $rol = "<select name='rol'>
            <option value='C' disabled> Colaborador </option>
            <option value='A' disabled> Administrador</option> </select>";  
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
        $input = "<input type='submit' name='validarDatos' value='Validar datos si son correctos'>";
    }else if($accion == 'b'){
        $input = "<input type='submit' name='borrarUsuario' value='Borrar usuario definitivamente'>";
    }
    return $input;
}
?>