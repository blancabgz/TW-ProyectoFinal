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
            <!--<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">-->
            <!--Bootstrap JS-->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

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
    HTML;

    if($user != 'A'){
        $nav = ["Inicio", "Calendario de vacunación"];
        $links = ["../view/inicio.php", "../controller/calendario.php"];
        if($user == 'P'){
            $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Cartilla de vacunación"];
            $links = ["../view/inicio.php", "../controller/calendario.php", "../controller/edit.php", "../view/cartillaVacunacion.php"];
        }
        elseif($user == 'S'){
            $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Cartilla de vacunación", "Búsqueda de pacientes"];
            $links = ["../view/inicio.php", "../controller/calendario.php", "../controller/edit.php", "../view/cartillaVacunacion.php", "../view/busquedaPacientes.php"];
        }
        echo "<ul class='ul'>";
        foreach($nav as $k => $v)
            echo "<li> <a href='".$links[$k],"'>".$v."</a></li>";
        echo "</ul>";
    }else{
        echo <<< HTML
            <a href='../view/inicio.php'> Inicio </a>
            <a href='../controller/calendario.php'> Calendario de vacunación </a>
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dropdownMenuButton" >
                Gestión de usuarios </button>

                <div class="dropdown-menu" aria-labelledby="dropdowmMenuButton">
                    <a class="dropdown-item" href="../controller/add.php"> Añadir usuario </a>
                    <a class="dropdown-item" href="../controller/list.php"> Listado de usuarios </a>
                </div>
            </div>
        HTML;

        $nav = ["Inicio", "Calendario de vacunación", "Datos personales", "Búsqueda de pacientes"];
        $links = ["../view/inicio.php", "../controller/calendario.php", "../controller/edit.php", "../view/busquedaPacientes.php"];
    }
    echo <<< HTML
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
    else if($titulo == 'Inicio'){
        echo <<< HTML
            <main>
            <section id='contenido' class='borde_verde'>
                <h1>$titulo </h1>
                <!--<p> Que las vacunas  inmunizan contra enfermedades es algo que ya sabes, pero seguro que no tienes ni idea de quién creó la primera vacuna ni cómo lo consiguió.
                Según la Organización Mundial de la Salud (OMS), una vacuna es aquella preparación destinada a generar inmunidad contra una enfermedad. Esto se logra
                estimulando la producción de anticuerpos. Las vacunas pueden tratarse de una suspensión de microorganismos muertos o atenuados, o de productos o derivados
                de microorganismos. Para su administración el método más habitual es la inyección, aunque algunas se administran con un vaporizador nasal u oral, sobre todo en
                niños. Para conocer la primera vacuna hay que remontarse hasta el año 1796, en ese año Europa sufría una terrible epidemia de viruela. En Inglaterra el doctor
                Edward Jenner observó que las mujeres que ordeñaban vacas se contagiaban de viruela vacuna y tras recuperarse se volvían inmunes a su mortal variante humana.
                Al observar este hecho, Jenner tomó viruela de la granjera Sarah Nelmes e insertó el fluido en el niño de ocho años James Phipps, quien desarrolló la enfermedad
                y se recuperó 48 días más tarde. Más tarde se infectó al niño con virus de la viruela humana, pero no mostró síntomas de la enfermedad. La primera vacuna fue
                descubierta y, de hecho, la viruela ha sido la única enfermedad erradicada de la tierra. </p>
                <img src='../vacuna.png' alt="Vacuna" width='100'> -->
                <div class="credenciales">
                  <h2> Credenciales</h2>
                  <p><span>Paciente:</span> 12345678P <span> Clave:</span> 123456</p>
                  <p><span>Sanitario:</span> 12345678S <span>Clave:</span> 123456</p>
                  <p> <span>Administrador:</span> 12345678A <span>Clave:</span> 123456</p>
                </div>

            </section>
        HTML;
    }
}

function HTMLformulario($user){

    if($user=="V" || $user == 'E'){
        echo
            "<div id='barra_lateral'>
                <div class='borde_verde form form-group'>
                    <h1> Login </h1>";

        if($user=='E'){
            echo "  <p id='error'> Error al identificarse, vuelva a rellenar el formulario. </p>";

        }

        echo "
                    <form class='row' action='../controller/login.php' method='post'>
                      <div class='col-12'>
                        <label class='form-label'> Usuario </label>
                        <input type='text' name='usuario' class='form-control'>
                      </div>
                      <div class='col-12'>
                        <label class='form-label'> Clave </label>
                        <input class='form-control' type='password' name='clave'>
                      </div>
                      <div class='col-12 boton'>
                        <input class='btn' type='submit' name='login' value='Login'>
                      </div>
                    </form>
                    <a href='../controller/solicitud.php'> Solicitar darse de alta </a>
                </div>";
    }else if($user == "P" || $user == "A" || $user == "S"){
        echo <<< HTML
            <div id="barra_lateral">
                <div class="borde_verde form">
                    <h1> Logout </h1>
                    <form action="../controller/logout.php" method="post">
                      <div class='col-12 boton'>
                        <input class='btn' type="submit" name="logout" value="Logout">
                      </div>
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
