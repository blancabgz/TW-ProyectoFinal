<?php
require_once "../controller/check_login.php";
require_once "../view/vistasHTML.php";
$titulo="Calendario de vacunación Andalucia 2020";

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//se establece la cabecera de la tabla
//cabeceraCalendario();

//se obtienen los datos de la vacunas
$vacunas = obtenerListadoVacunas();
$calendario = obtenerCalendarioVacunas();
$cabecera = ['Vacuna', 'Pre natal', '0 meses', '2 meses',
'4 meses', '11 meses', '12 meses', '15 meses', '3 años', '6 años',
'12 años', '14 años', '18 años', '50 años', '65 años', '> 65 años'];


echo "
<main class='row'>
  <section id='contenido' class='borde_verde col-md-9'>
	  <h1> $titulo </h1>
	  <div class='container table-responsive py-5'>
			<table class='table table-bordered table-hover'>
				<tr>";
 
foreach($cabecera as $cab){
	echo "<th scope='col'>".$cab."</th>";
}
echo "
				</tr>
		  </table>
	  </div>
  </section>";

/*
echo <<< HTML

  <main class='row'>
    <section id='contenido' class='borde_verde col-md-9'>
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
			</table>
		</div>
	</section>
HTML;

/*echo <<< HTML
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
*/
	foreach($calendario as $c){
		//obtenemos el id de la vacuna
		$id = $c['idvacuna'];

		foreach($vacunas as $v){
			if($v['id'] == $id){
				$nombre = $v['nombre'];
				$mes_ini;
			}
		}
		
	}

HTMLformulario($rol);
HTMLfooter();
?>
