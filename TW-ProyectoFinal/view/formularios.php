<?php

/* FORMULARIOS DE USUARIOS */
//añadir
function formularioUSU01($titulo, $form){
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
                    </select> </label>
                </div>
                <div class="form-group">
                    <label> Estado: <select name="estado">
                        <option value="A"> Activo</option>
                        <option value="I"> Inactivo</option>
                    </select> </label>
                </div>

                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
                </div>
            </form>
        </section>
HTML;
}


//añadir, editar, solicitar
function formularioUSU02($datos, $validar, $form, $titulo, $user, $accion){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '2');
    $sexo = formularioSexo($campos['sexo'], '0');
    $rol = formularioRol($campos['rol'], '0');
    $estado = formularioEstado($campos['estado'], '0');
    //$dni = formularioDNI($campos, $accion);
    
    if($accion == 'e'){
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
            <label> Nombre: <input type='text' class='form-control' name='nombre' value='".$campos['nombre']."' ></label>
            </div>
            <div class='form-group'>
            <label> Apellidos: <input type='text' class='form-control' name='apellidos' value='".$campos['apellidos']."'></label>
            </div>
            <div class='form-group'>
                <label> DNI: <input type='text' class='form-control' name='dni' value='".$campos['dni']."' ></label>            
            </div>
            <div class='form-group'>
            <label> Email: <input type='text' class='form-control' name='email' value='".$campos['email']."'></label>
            </div>
            <div class='form-group'>
            <label> Teléfono: <input type='text' class='form-control' name='telefono' value='".$campos['telefono']."' ></label>
            </div>
            <div class='form-group'>
            <label> Fecha nac: <input type='date'  name='fecha' value='".$campos['fecha']."' ></label>
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
                <label> Rol: ".$rol." </label>
            </div>
            <div class='form-group'>
                <label> Estado: ".$estado." </label>
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

//añadir, ver, editar, borrar, solicitar
function formularioUSU03($datos, $accion, $form, $titulo, $user){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '3');
    $sexo = formularioSexo($campos['sexo'], '1');
    $rol = formularioRol($campos['rol'], '1');
    $estado = formularioEstado($campos['estado'], '1');
    $submit = formularioSubmit($accion);
    //$dni = formularioDNI($campos, $accion);

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
                <label> DNI: <input readonly type='text' name='dni' value='".$datos['dni']."' ></label>
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
                <label> Rol: ".$rol." </label>
            </div>
            <div class='form-group'>
                <label> Estado: ".$estado." </label>
            </div>
            <div class='form-group form boton'> ".$submit." </div>
        </form> </section>";
}


//solicitar
function formularioUSU04($titulo, $form){
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
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'>
                </div>
            </form>
        </section>
HTML;        
}

//editar
function formularioUSU05($datos, $validar, $form, $titulo){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '2');
    $sexo = formularioSexo($campos['sexo'], '1');
    $campos['clave2'] = $campos['clave'];

    echo "
    <main>
    <section id='contenido' class='borde_verde formulario'>
        <h1>".$titulo."</h1>
        <form action='".$form."' method='post' enctype='multipart/form-data' id='add'>
        <div class='form-group'>
            ".$foto."
        </div>
        <div class='form-group'>
            <label> Nombre: <input readonly type='text' class='form-control' name='nombre' value='".$campos['nombre']."' ></label>
        </div>
        <div class='form-group'>
            <label> Apellidos: <input readonly type='text' class='form-control' name='apellidos' value='".$campos['apellidos']."'></label>
        </div>
        <div class='form-group'>
            <label> DNI: <input readonly type='text' class='form-control' name='dni' value='".$campos['dni']."' ></label>
        </div>
        <div class='form-group'>
            <label> Email: <input type='text' class='form-control' name='email' value='".$campos['email']."'></label>
        </div>
        <div class='form-group'>
            <label> Teléfono: <input type='text' class='form-control' name='telefono' value='".$campos['telefono']."' ></label>
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
            <input class='btn' type='submit' name='enviarDatos' value='Enviar datos'/>
        </div>

        </form>";

    if($validar != 'e'){
        foreach($validar as $k){
            echo "<p> ".$k."</p>";
        }
    }
    echo "</section>";
}


//editar, solicitar
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
        <div class='form-group form boton'>
            ".$submit."
        </div>
        </form>
    </section>";
}

//solicitar
function formularioUSU07($datos, $validar, $form, $titulo){
    $campos = procesarDatos($datos);

    $foto = formularioFoto($campos['fotografia'], '2');
    $sexo = formularioSexo($campos['sexo'], '0');
    $rol = formularioRol($campos['rol'], '0');
    $estado = formularioEstado($campos['estado'], '0');
    
    $campos['clave2'] = $campos['clave'];
    
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
            <label> DNI: <input type='text' name='dni' value='".$datos['dni']."' ></label>            
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

/* Funciones auxiliares para los formularios de usuarios */
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
    if($accion == 'a' || $accion == 'e' || $accion == 's'){
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


/* FORMULARIOS DE VACUNAS */
//formulario vacío para vacunas
function formularioVAC01($titulo, $form){

    echo <<< HTML
         <main>
        <section id="contenido" class="borde_verde formulario">
            <h1> $titulo </h1>
            <form action="$form"  method="post" enctype="multipart/form-data" id="add">
                <div class="form-group">
                    <label> Nombre: <input class="form-control" type="text" name="nombre"></label>
                </div>
                <div class="form-group">
                    <label> Acrónimo: <input type="text" class="form-control" name="acronimo"></label>
                </div>
                <div class="form-group">
                    <label> Descripción: <input type="text" class="form-control" name="descripcion"></label>
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
         <main>
        <section id='contenido' class='borde_verde formulario'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input class='form-control' type='text' name='nombre' value='".$campos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Acrónimo: <input type='text' class='form-control' name='acronimo' value='".$campos['acronimo']."'></label>
                </div>
                <div class='form-group'>
                    <label> Descripción: <input type='text' class='form-control' name='descripcion' value='".$campos['descripcion']."'></label>
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
         <main>
        <section id='contenido' class='borde_verde formulario'>
            <h1> $titulo </h1>
            <form action='$form'  method='post' enctype='multipart/form-data' id='add'>
                <div class='form-group'>
                    <label> Nombre: <input readonly class='form-control' type='text' name='nombre' value='".$campos['nombre']."'></label>
                </div>
                <div class='form-group'>
                    <label> Acrónimo: <input readonly type='text' class='form-control' name='acronimo' value='".$campos['acronimo']."'></label>
                </div>
                <div class='form-group'>
                    <label> Descripción: <input readonly type='text' class='form-control' name='descripcion' value='".$campos['descripcion']."'></label>
                </div>
                <div class='form-group form boton'>".$submit." </div>
            </form>
        </section>";
}


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

function formularioVAC_CAL03($post, $titulo, $form, $vacunas){
    $datos = procesarDatosVacunaCalendario($post, $vacunas);
    $sexo = formularioSexoCalendario($datos, 1);
    $tipo = formularioTipoCalendario($datos, 1);

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
                <div class='form-group form boton'>
                    <input class='btn' type='submit' name='validarDatos' value='Validar datos'>
                </div>
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
