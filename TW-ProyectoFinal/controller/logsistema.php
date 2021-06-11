<?php
require_once "../controller/check_login.php";
require_once "../model/basedatos.php";
require_once "../model/log.php";
require_once "../view/vistasComunes.php";

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
	
    $log = obtenerLogSistema();

    if(!is_array($log)){
        mensaje($titulo, $mensaje);
    }
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
}
HTMLformulario($rol);
HTMLfooter();

?>