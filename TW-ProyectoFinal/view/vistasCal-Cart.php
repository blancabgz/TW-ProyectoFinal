<?php

/* Calendario */
//cabecera del calendario
function cabeceraCalendario($titulo, $user){
	$cabecera = ['Vacuna', 'Pre natal', '0 meses', '2 meses', '4 meses',
		'11 meses', '12 meses', '15 meses', '3 años', '6 años', '12 años',
		'14 años', '18 años', '50 años', '65 años', '> 65 años'];

    if($user == 'A' || $user == 'S'){
        echo <<< HTML
            <div class='container table-responsive py-5'>
                <table class='table table-bordered table-hover'>
                    <tr>
        HTML;
    }
    else{
echo <<< HTML
	        <main class='row'>
	            <section id='contenido' class='borde_verde col-md-9'>
	    	        <h1> $titulo </h1>
	    	        <div class='container table-responsive py-5'>
	    			    <table class='table table-bordered table-hover'>
	    				    <tr>
HTML;
    }

	foreach($cabecera as $cab){
		echo "<th scope='col'>".$cab."</th>";
	}
	echo "</tr>";
}

/*  -------------------------------------------------- */
function botonAddVacunaCalendario($titulo, $form, $name, $value, $dni){
    echo <<< HTML
        <main class='row'>
        <section id='contenido' class='borde_verde col-md-9'>
            <h1> $titulo </h1>
            <form action='$form' method='post'>
                <input type='submit' name='$name' value='$value'>
                <input type='hidden' name='dnipaciente' value='$dni'>
            </form>
    HTML;
}

function celdaNombre($nombre){
    echo "<th scope='col'>".$nombre."</th>";
}

function celdaVacia(){
    echo "<th scope='col'> </th>";
}

function finFila(){
    echo "</tr>";
}

function finTabla(){
	echo "</table> </div> </section>";
}

function celdaCalendario($acronimo, $idCalendario, $idVacuna, $sexo, $tipo, $comentarios, $c, $user){
    $color = '';
    if($c == 'y'){
        $color = "style='background-color: #FFA07A'";
    }

    echo "
    <th scope='col' ".$color.">
        <form action='../controller/datosVacuna.php' method='post'>
            <input type='submit' name='datosVacuna' value='$acronimo'>
            <input type='hidden' name='id' value='$idCalendario'>
            <input type='hidden' name='idVac' value='$idVacuna'>
            <input type='hidden' name='sexo' value='$sexo'>
            <input type='hidden' name='tipo' value='$tipo'>
            <input type='hidden' name='comment' value='$comentarios'>
            <input type='hidden' name='cartilla' value='$c'>
		</form>";
		if($user =='A'){
			echo "<form action='../controller/deleteVacCalendario.php' method='post'>
					<input type='submit' name='deleteVac' value='Borrar'>
            		<input type='hidden' name='id' value='$idCalendario'>
                    <input type='hidden' name='idvacuna' value='$idVacuna'>
                    <input type='hidden' name='acronimo' value='$acronimo'>
				  </form>";
		}
    echo "</th>";
}

function celdaCartilla($acronimo, $idCalendario, $idVacuna, $dnipaciente){
    echo "
    <th scope='col' style='background-color: #BDB76B'>
        <form action='../controller/datosVacunacion.php' method='post'>
            <input type='submit' name='datosCartilla' value='$acronimo'>
            <input type='hidden' name='id' value='$idCalendario'>
            <input type='hidden' name='idVac' value='$idVacuna'>
            <input type='hidden' name='dnipaciente' value='$dnipaciente'>
        </form>
    </th>";
}

function datosVacunas($datos, $titulo, $rol){
    $botonAdd = '';
    if($datos['vienede'] == 'Cartilla' && $rol == 'S'){
        $botonAdd = "
        <form action='../controller/addVacunaCartilla.php' method='post'>
        <input type='submit' name='vacunaCartilla' value='Añadir vacuna a la cartilla'>
        </form>";
    }

    echo "
    <main class='row'>
        <section id='contenido' class='borde_verde vacuna col-md-9'>
            <h1> $titulo </h1>
						<table class='table table-bordered table-striped table-responsive-stack' id='tableOne'>
							<thead class='thead-dark'>
								<tr>
									<th>Nombre</th>
									<th>Acronimo</th>
									<th>Sexo</th>
									<th>Tipo</th>
									<th>Comentarios</th>
							 	</tr>
							</thread>
							<tbody>
								<tr>
									<td>".$datos['nombre']."</td>
									<td>".$datos['acronimo']."</td>
									<td>".$datos['sexo']."</td>
									<td>".$datos['tipo']."</td>
									<td>".$datos['comentarios']."</td>
							 	</tr>
							</tbody>

						</table>

                <form class='row form-group form boton' action='".$datos['form']."' method='post'>
					<div class='col-12 boton'>
	        	<input class='btn' type='submit' name='".$datos['name']."' value=' Volver a ".$datos['vienede']."'>
					</div>
				</form>
        ".$botonAdd."
    </section>
    ";
}

//muestra los datos de una vacuna de la cartilla
function datosCartilla($datos, $titulo, $rol){

    $botones = '';
    if($rol == 'S'){
        $botones = "
            <form action='../controller/editVacCartilla' method='post'>
            <input type='submit' name='editVac' value='Editar vacuna de la cartilla'>
            </form>
            <form action='../controller/deleteVacCartilla' method='post'>
            <input type='submit' name='deleteVac' value='Eliminar vacuna de la cartilla'>
            </form>
        ";
    }

    echo "
    <main class='row'>
        <section id='contenido' class='borde_verde col-md-9'>
            <h1> $titulo </h1>
            <p> Nombre: ".$datos['nombre']." </p>
            <p> Acronimo: ".$datos['acronimo']." </p>
            <p> Fecha: ".$datos['fecha']." </p>
            <p> Fabricante: ".$datos['fabricante']." </p>
            <p> Comentarios: ".$datos['comentarios']." </p>

        <form action='".$datos['form']."' method='post'>
        <input type='submit' name='".$datos['name']."' value=' Volver a ".$datos['vienede']."'>
        <input type='hidden' name='dnipaciente' value='".$datos['dnipaciente']."'>
        </form>
        ".$botones."
    </section>";
}
?>
