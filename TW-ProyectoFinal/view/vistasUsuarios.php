<?php
/* VISTAS DEL USUARIO */
function mostrarLista($lista){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde col-md-9 col-sm-12'>
        <h1> Listado de usuarios </h1>
        <div class="row listausuarios">
            <table class="table table-striped table-responsive">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Nombre</th>
                  <th>Apellidos</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Editar</th>
                  <th>Borrar</th>
                </tr>
              </thead>
              <tbody>


    HTML;

    foreach($lista as $k){
        echo "
          <tr>
        ";
          if($k['fotografia'] != ''){
              echo "<td><img class='img-responsive img-rounded img-thumbnail' src='data:img/png; base64, ".$k['fotografia']." alt='imagen'/></td>";
          }else{
            echo "<td><img class='img-responsive img-rounded img-thumbnail' src='../not-available.png' alt='imagen'/></td>";
          }
          echo " <td><label class='name'>".$k['nombre']." </label></td>
           <td><label>".$k['apellidos']." </label></td>
           <td><label>".$k['rol']." </label></td>
           <td><label> ".$k['estado']." </label></td>
           <td><label class='float-right'>
              <form action='../controller/editUser.php' method='post'>
                  <input class='btn btn-primary  btn-xs glyphicon glyphicon-trash' type='submit' name='editarUser' value='Editar'/>
                  <input type='hidden' name='dni' value='".$k['dni']."'/>
              </form>
          </label></td>
          <td><label class='pull-right'>
              <form action='../controller/deleteUser.php' method='post'>
                  <input class='btn btn-danger  btn-xs glyphicon glyphicon-trash' type='submit' name='borrar' value='Borrar'/>
                  <input type='hidden' name='dni' value='".$k['dni']."'/>
              </form>
          </label></td>
        </tr>
            ";
    }
    echo "";
    echo <<< HTML
            </tbody>
          </table>
        </div>
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1">Atrás</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Siguiente</a>
            </li>
          </ul>
        </nav>

      </section>
    HTML;
}

function mostrarListaPacientes($pacientes, $titulo){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde'>
        <h1> $titulo </h1>
    HTML;

    foreach($pacientes as $p){
        echo "
            ".$p['nombre']."
            ".$p['estado']."
            <form action='../controller/cartillaVacunacion.php' method='post'>
                <input type='submit' name='cartillaVacunacion' value='Cartilla de Vacunación'/>
                <input type='hidden' name='dnipaciente' value='".$p['dni']."'/>
            </form>";

            if($p['estado'] == 'I'){
                echo "
                <form action='../controller/activarPaciente.php' method='post'>
                    <input type='submit' name='activarPaciente' value='Activar Paciente'/>
                    <input type='hidden' name='dnipaciente' value='".$p['dni']."'/>
                </form>";
            }
    }
    echo "</section>";
}

?>
