<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Calendario de vacunación Andalucia 2020";
HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);
echo <<< HTML
  <main>
    <section id='contenido' class='borde_verde'>
        <h1> $titulo </h1>
        <div class="container table-responsive py-5">
          <table class="table table-bordered table-hover">
              <tr>
                <th scope="col">Vacuna</th>
                <th scope="col">Pre natal</th>
HTML;
                for ($i=0; $i<5 ; $i+=2) {
                  echo "<th scope='col'> " . $i . " meses</th>";
                }

                for ($j=11; $j < 13 ; $j++) {
                  echo "<th scope='col'> " . $j . " meses</th>";
                }
                echo "<th scope='col'> 15 meses</th>";
                for ($k=3; $k<13 ; $k*=2) {
                  echo "<th scope='col'> " . $k . " años</th>";
                }
                echo <<< HTML
                <th scope="col">14 años</th>
                <th scope="col">18 años</th>
                <th scope="col">50 años</th>
                <th scope="col">65 años</th>
                <th scope="col">>65 años</th>
              </tr>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
  HTML;

HTMLformulario($rol);
HTMLfooter();
?>
