<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Consultar log";
$titulo_form="Log del sistema";
$form = '../controller/logsistema.php';
$accion = 'l';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si no es la persona administradora, se le redirige al inicio
if($rol != 'A'){
	header("Location: ../view/inicio.php");
}
//si es la persona administradora
else{
	
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
	foreach($lista as $k){
        echo "
          <tr>
              <td>".$k['nombre']."</td>
              <td>".$k['acronimo']."</td>
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
HTMLformulario($rol);
HTMLfooter();

?>