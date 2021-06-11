<?php

/* VISTAS DE LAS VACUNAS */
function mostrarListaVacunas($lista, $titulo){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde col-md-9'>
        <h1> $titulo </h1>
        <div class="row listausuarios">
          <table class="table table-striped table-responsive">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Acrónimo</th>
                <th>Comentarios</th>
                <th>Editar</th>
                <th>Borrar</th>
              </tr>
            </thead>
            <tbody>


    HTML;

    foreach($lista as $k){
        echo "
          <tr>
              <td>".$k['nombre']."</td>
              <td>".$k['acronimo']."</td>
              <td>".$k['comentarios']."</td>
              <form action='../controller/editVac.php' method='post'>
                  <td><input type='submit' name='editVac' value='Editar'/></td>
                  <input type='hidden' name='idVac' value='".$k['id']."'/>
              </form>
              <form action='../controller/deleteVac.php' method='post'>
                  <td><input type='submit' name='deleteVac' value='Borrar'/></td>
                  <input type='hidden' name='idVac' value='".$k['id']."'/>
              </form>
              </tr>


        ";
    }
    echo "
          </tbody>
        </table>
      </div>
    </section>
    ";
}
?>
