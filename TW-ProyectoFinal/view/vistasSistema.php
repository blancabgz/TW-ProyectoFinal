<?php

function mostrarLogSistema($titulo, $log){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde col-md-9'>
        <h1> $titulo </h1>
        <div class="row listausuarios">
              <table class="table table-striped table-responsive">
                <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                      </tr>
                </thead>
                <tbody>
    HTML;
    foreach($log as $k){
        echo "
          <tr>
              <td>".$k['fecha']."</td>
              <td>".$k['descripcion']."</td>";
    }
    echo "
          </tbody>
        </table>
      </div>
    </section>";
}

function mostrarCopiasSeguridad($titulo, $copias){
    echo <<< HTML
    <main class="row">
        <section id='contenido' class='borde_verde col-md-9'>
        <h1> $titulo </h1>
        <div class="row listausuarios">
              <table class="table table-striped table-responsive">
                <thead>
                      <tr>
                        <th>Copias de seguridad</th>
                        <th>Restaurar</th>
                      </tr>
                </thead>
                <tbody>
    HTML;

    $num = count($copias);
    //recorremos todos los ficheros, excepto los dos últimos que son el padre y el hijo
    for($i = 0; $i < $num-2; $i = $i + 1){
        echo "
          <tr>
              <td>".$copias[$i]."</td>
              <td>
                <form action='../controller/restauracion.php' method='post'>
                    <input type='submit' name='restauracion' value='Seleccionar'/>
                    <input type='hidden' name='fichero' value='".$copias[$i]."'/>
                </form>
              </td>";
    }
    echo "
          </tbody>
        </table>
      </div>
    </section>";
}

function mensajesRestauracion($h1, $mensajes){

    echo <<< HTML
    <main class='row'>
        <section id='contenido' class="borde_verde col-md-9">
            <h1> $h1 </h1>
            <p> $mensajes </p>
        </section>
    HTML;
}
?>