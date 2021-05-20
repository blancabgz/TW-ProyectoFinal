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
            <!--<base href="https://void.ugr.es/~postdata92021/practicaPHP/">-->
        </head>
    HTML;
}

function HTMLheader($titulo){
    echo <<< HTML
        <body>
            <header>
                <h1>$titulo</h1>
                <img src="../view/hamburguesa.png" alt="Hamburguesa sonriente" width="100"/>
                <img src="../view/hamburguesa.png" alt="Hamburguesa sonriente" width="100"/>
                <img src="../view/hamburguesa.png" alt="Hamburguesa sonriente" width="100"/>
            </header>
    HTML;
}

function HTMLnav($user){
    echo <<< HTML
            <nav class="color">
                <ul class="ul">
    HTML;
    
    $nav = ["Presentación", "Sobre el autor"];
    $links = ["../view/presentacion.php", "../view/autor.php"];
    if($user == 'C'){
        $nav = ["Presentación", "Sobre el autor", "Listado de usuarios"];
        $links = ["../view/presentacion.php", "../view/autor.php", "../controller/list.php"];
    }
    elseif($user == 'A'){
        $nav = ["Presentación", "Sobre el autor", "Listado de usuarios", "Añadir usuario"];
        $links = ["../view/presentacion.php", "../view/autor.php", "../controller/list.php", "../controller/add.php"];
    }

    foreach($nav as $k => $v)
        echo "<li> <a href='".$links[$k],"'>".$v."</a></li>";
    echo <<< HTML
                </ul>
            </nav>
        </body>
    HTML;
}

function HTMLcontenido($user){
    echo <<< HTML
    <main>
            <section id='contenido' class='borde_verde'>
                <h1> Presentación </h1>
                <p> Administrador: Usuario: 12345678A Contraseña: 123456 </p>
                <p> Colaborador: Usuario: 98765432C Contraseña: 654321 </p>
                <p> Ahora eres $user </p>
            </section>
    HTML;
}

function HTMLformulario($user){

    if($user=="V"){
        echo <<< HTML
            <div id="barra_lateral">
                <div class="borde_verde form">
                    <h1> Login </h1>
                    <form action="../controller/login.php" method="post">
                        <label> Usuario <input type="text" name="usuario"> </label>
                        <label> Clave <input type="password" name="clave"> </label>
                        <input type="submit" name="login" value="Login">
                    </form>
                </div>
        HTML;
    }else if($user=="C" || $user=="A"){
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
    else if($user=="E"){
        echo <<< HTML
        <div id="barra_lateral">
            <div class="borde_verde form">
                <h1> Login </h1>
                <p id="error"> Error al identificarse, vuelva a rellenar el formulario. </p>
                <form action="../controller/login.php" method="post">
                    <label> Usuario <input type="text" name="usuario"> </label>
                    <label> Clave <input type="password" name="clave"> </label>
                    <input type="submit" name="login" value="Login">
                </form>
            </div>
        HTML;
    }

    echo <<< HTML
        <div class="borde_verde form">
            <h1>Receta Hamburguesas </h1>
            <p> Para realizar unas hamburguesas deliciosas necesitaremos
                carne picada, huevo, pan rallado y especias al gusto. 
                Añadir todos los ingredientes y amasar, hacerle forma, 
                cocinar en la sartén y... ¡a disfrutar!</p>
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
                    <li>Mapa del sitio</li>
                    <li>Contacto</li>
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