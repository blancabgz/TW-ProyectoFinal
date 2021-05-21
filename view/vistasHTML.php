<?php

function HTMLinicio($titulo){
    echo <<< HTML

    <!DOCTYPE HTML>
    <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="author" content="Paula Santos Ortega">
            <title>$titulo</title>
            <link rel="stylesheet" href="../view/style.css"/>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
            <!--<base href="https://void.ugr.es/~postdata92021/practicaPHP/">-->
        </head>
    HTML;
}

function HTMLheader($titulo){
    echo <<< HTML
        <body>
            <header>
                <h1>Vacunas</h1>
                <img src='../view/jeringa.png' alt='Jeringuilla' width='100'/>
                <h2>$titulo</h2>
            </header>
    HTML;
}

function HTMLnav($user){
    echo <<< HTML
            <nav class="color">
                <ul class="ul">
    HTML;

    $nav = ["Inicio", "Calendario de vacunación"];
    $links = ["../view/inicio.php", "../controller/calendario.php"];
    if($user == 'P'){
        $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Cartilla de vacunación"];
        $links = ["../view/inicio.php", "../controller/calendario.php", "../view/datosPaciente.php", "../view/cartillaVacunacion.php"];
    }
    elseif($user == 'S'){
        $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Cartilla de vacunación", "Búsqueda de pacientes"];
        $links = ["../view/inicio.php", "../controller/calendario.php", "../view/datosPaciente.php", "../view/cartillaVacunacion.php", "../view/busquedaPacientes.php"];
    }
    elseif($user == 'A'){
        $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Búsqueda de pacientes"];
        $links = ["../view/inicio.php", "../controller/calendario.php", "../view/datosPaciente.php", "../view/busquedaPacientes.php"];
    }
    foreach($nav as $k => $v)
        echo "<li> <a href='".$links[$k],"'>".$v."</a></li>";
    echo <<< HTML
                </ul>
            </nav>
        </body>
    HTML;
}

function HTMLcontenido($titulo){

    if($titulo == 'Login'){
        echo <<< HTML
        <main>
                <section id='contenido' class='borde_verde'>
                    <h1> $titulo </h1>
                    <p> Si acaba de realizar la solicitud de alta en la plataforma, debe esperar a que la
                    persona administradora le conceda permiso para loguearse. </p>
                </section>
        HTML;
    }
}

function HTMLformulario($user){

    if($user=="V" || $user == 'E'){
        echo
            "<div id='barra_lateral'>
                <div class='borde_verde form'>
                    <h1> Login </h1>";

        if($user=='E'){
            echo "  <p id='error'> Error al identificarse, vuelva a rellenar el formulario. </p>";

        }

        echo "      <form action='../controller/login.php' method='post'>
                        <label> Usuario <input type='text' name='usuario'> </label>
                        <label> Clave <input type='password' name='clave'> </label>
                        <input type='submit' name='login' value='Login'>
                    </form>
                    <a href='../controller/solicitud.php'> Solicitar darse de alta </a>
                </div>";
    }else if($user == "P" || $user == "A" || $user == "S"){
        echo <<< HTML
            <div id="barra_lateral">
                <div class="borde_verde form">
                    <h1> Logout </h1>
                    <form action="../controller/logout.php" method="post">
                        <input type="submit" name="logout" value="Logout">
                    </form>
                </div>
        HTML;
    }

    echo <<< HTML
        <div class="borde_verde form">
            <h1> Estadísticas </h1>
            <ul id='estadistica'>
                <li>Número de vacunas totales puestas (últimos 30 días): XX </li>
                <li>Número total de usuarios del sistema: XX </li>
            </ul>
        </div>
    </div>
    HTML;
}

function HTMLfooter(){
    echo <<< HTML
            </main>
            <footer class="color">
                <ul class="ul">
                    <li>(C) 2020 Tecnologías Web</li>
                    <li> Proyecto </li>
                    <li> Blanca Abril González </li>
                    <li> Paula Santos Ortega </li>
                </ul>
            </footer>
        </body>
    </html>
    HTML;
}

function mostrarLista($rol, $lista){
    echo <<< HTML
    <main>
        <section id='contenido' class='borde_verde'>
        <h1> Listado de usuarios </h1>
    HTML;
    foreach($lista as $k){
        echo "
        <section class='"."listado"."'>
        <!-- <img/> -->
        <div class='"."inf_pers"."'>
        <p>".$k['nombre']." ".$k['apellidos']."</p>";

        if($k['email'] != null){
            echo "<p> ".$k['email']."</p>";
        }

        if($rol == 'A'){
            echo "
            </div>
            <form action='../controller/see.php' method='post'>
                <input type='submit' name='ver' value='Ver'/>
                <input type='hidden' name='dni' value='".$k['dni']."'/>
            </form>
            <form action='../controller/edit.php' method='post'>
                <input type='submit' name='editarUser' value='Editar'/>
                <input type='hidden' name='dni' value='".$k['dni']."'/>
            </form>
            <form action='../controller/delete.php' method='post'>
                <input type='submit' name='borrar' value='Borrar'/>
                <input type='hidden' name='dni' value='".$k['dni']."'/>
            </form>";
        }
        if($rol == 'C' && $_SESSION['usuario']==$k['dni']){
            echo "
                </div>
                <form action='../controller/see.php' method='post'>
                    <input type='submit' name='ver' value='Ver'/>
                    <input type='hidden' name='dni' value='".$k['dni']."'/>
                </form>
                <form action='../controller/edit.php' method='post'>
                    <input type='submit' name='editarUser' value='Editar'/>
                    <input type='hidden' name='dni' value='".$k['dni']."'/>
                </form>";
        }
        echo "</section>";
    }
    echo "</section>";
}


function mensaje($h1, $mensaje){
    echo <<< HTML
    <main>
        <section id='contenido' class="borde_verde">
            <h1> $h1 </h1>
            <p> $mensaje </p>
        </section>
    HTML;
}
?>
