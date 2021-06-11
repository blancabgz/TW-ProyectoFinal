<?php

/* Calendario */
//cabecera del calendario
function cabeceraCalendario($titulo, $user, $c){
	$cabecera = ['Vacuna', 'Pre natal', '0 meses', '2 meses', '4 meses',
		'11 meses', '12 meses', '15 meses', '3 años', '6 años', '12 años',
		'14 años', '18 años', '50 años', '65 años', '> 65 años'];

    $aviso = '';
    $boton = '';

    if($c == 'cartilla' || $c == 'cartilla2'){
        $aviso = '<p> Las vacunas puestas se muestran sobre fondo verde, 
        las vacunas no puestas están sobre fondo rojo.</p>';
    }
    
    if($c == 'cartilla2' && ($user == 'S' || $user == 'SP' || $user == 'A')){
        $aviso .= '<p> Si la cartilla está vacía, es decir, si todas las vacunas tienen
        fondo rojo puedes añadir una vacuna a la cartilla clicando en la vacuna.</p>';
    }

    if($user == 'SP'){
        $boton = "
        
        <form class='row form-group form boton' action='../controller/busquedaPacientes.php' method='post'>
            <div class='col-12 boton'>
            <input class='btn' type='submit' name='cartillaVac' value='Volver al listado de Pacientes'/>
            </div>
        </form>";
    }

    if($user == 'A' && $c == 'calendario'){
            echo <<< HTML
                $aviso
                $boton
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
                    $aviso
                    $boton
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
            <form action='$form' method='post' class='boton'>
                <input class='btn btnvacuna' type='submit' name='$name' value='$value'>
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
        $color = "style='background-color: #FFD6C5'";
    }

    echo "
    <th scope='col' ".$color.">
        <form action='../controller/datosVacuna.php' method='post'>
            <input class='btn col-md-12 btn btn btn-outline-success btn-xs glyphicon glyphicon-trash' type='submit' name='datosVacuna' value='$acronimo'>
					  <input type='hidden' name='id' value='$idCalendario'>
            <input type='hidden' name='idVac' value='$idVacuna'>
            <input type='hidden' name='sexo' value='$sexo'>
            <input type='hidden' name='tipo' value='$tipo'>
            <input type='hidden' name='comment' value='$comentarios'>
            <input type='hidden' name='cartilla' value='$c'>
    ";

    if($user == 'SP'){
        echo "<input type='hidden' name='cartillaVacunacionPaciente' value=''>";
    }

        echo "
		</form>";
		if($user =='A'){
			echo "<form action='../controller/deleteVacCalendario.php' method='post'>
					<input class='btn btn btn-outline-danger col-md-12 btn-xs glyphicon glyphicon-trash' type='submit' name='deleteVac' value='Borrar'>
							<input type='hidden' name='id' value='$idCalendario'>
                    <input type='hidden' name='idvacuna' value='$idVacuna'>
                    <input type='hidden' name='acronimo' value='$acronimo'>
					</form>";
		}
    echo "</th>";
}

function celdaCartilla($acronimo, $idCalendario, $idVacuna, $dnipaciente, $user, $idvacunacion){
    echo "
    <th scope='col' style='background-color: rgb(209, 255, 185)'>
        <form action='../controller/datosVacunacion.php' method='post'>
            <input class='btn col-md-12 btn btn btn-outline-success btn-xs glyphicon glyphicon-trash' type='submit' name='datosCartilla' value='$acronimo'>
            <input type='hidden' name='id' value='$idCalendario'>
            <input type='hidden' name='idVac' value='$idVacuna'>
            <input type='hidden' name='dnipaciente' value='$dnipaciente'>
            <input type='hidden' name='idvacunacion' value='$idvacunacion'>";
        if($user == 'SP'){
            echo "<input type='hidden' name='cartillaVacunacionPaciente' value=''>";
        }
        echo "
        </form>
    </th>";
}

function datosVacunas($datos, $titulo, $rol){
    $botonAdd = '';

    if($datos['vienede'] == 'Cartilla' && $rol == 'S'){
        $botonAdd = "
        <form class='row form-group form boton' action='../controller/addVacunaCartilla.php' method='post'>
            <div class='col-12 boton'>
                <input <input class='btn' type='submit' name='vacunaCartilla' value='Añadir vacuna a la cartilla'>
            </div>
                <input type='hidden' name='calendario_id' value='".$datos['calendario_id']."'>
                <input type='hidden' name='nombre' value='".$datos['nombre']."'>
        </form>";
    }

    echo "
    <main class='row'>
        <section id='contenido' class='borde_verde vacuna col-md-9'>
            <h1>".$titulo."</h1>
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
	        	        <input class='btn' type='submit' name='".$datos['name']."' value=' Volver a ".$datos['vienede']."'/>
                    </div>
                    <input type='hidden' name='dnipaciente' value='".$datos['dnipaciente']."'/>
				</form>
        ".$botonAdd."
        </section>";
}

//muestra los datos de una vacuna de la cartilla
function datosCartilla($datos, $titulo, $rol){

    $botones = '';
    if($rol == 'S'){
        $botones = "
            <form class='row form-group form boton' action='../controller/editVacunaCartilla.php' method='post'>
                <div class='col-12 boton'>
                    <input class='btn' type='submit' name='editVac' value='Editar vacuna de la cartilla'>
                    <input type='hidden' name='idcalendario' value='".$datos['idcalendario']."'>
                    <input type='hidden' name='nombre' value='".$datos['nombre']."'>
                    <input type='hidden' name='dnipaciente' value='".$datos['dnipaciente']."'>
                    <input type='hidden' name='id' value='".$datos['idvacunacion']."'>
                </div>
            </form>

            <form class='row form-group form boton' action='../controller/deleteVacCartilla.php' method='post'>
                <div class='col-12 boton'>
                    <input class='btn' type='submit' name='deleteVac' value='Eliminar vacuna de la cartilla'>
                    <input type='hidden' name='idcalendario' value='".$datos['idcalendario']."'>
                    <input type='hidden' name='nombre' value='".$datos['nombre']."'>
                    <input type='hidden' name='dnipaciente' value='".$datos['dnipaciente']."'>
                    <input type='hidden' name='id' value='".$datos['idvacunacion']."'>
                </div>
            </form>
        ";
    }

    echo "
    <main class='row'>
        <section id='contenido' class='borde_verde col-md-9'>
            <h1> $titulo </h1>
            <table class='table table-bordered table-striped table-responsive-stack' id='tableOne'>
				<thead class='thead-dark'>
					<tr>
						<th>Nombre</th>
						<th>Acronimo</th>
						<th>Fecha</th>
						<th>Fabricante</th>
						<th>Comentarios</th>
				 	</tr>
				</thread>
				<tbody>
					<tr>
						<td>".$datos['nombre']."</td>
						<td>".$datos['acronimo']."</td>
						<td>".$datos['fecha']."</td>
						<td>".$datos['fabricante']."</td>
						<td>".$datos['comentarios']."</td>
					</tr>
				</tbody>
            </table>
            <form class='row form-group form boton' action='".$datos['form']."' method='post'>
                <div class='col-12 boton'>
                    <input class='btn' type='submit' name='".$datos['name']."' value=' Volver a ".$datos['vienede']."'>
                    <input type='hidden' name='dnipaciente' value='".$datos['dnipaciente']."'>
                </div>
            </form>
            ".$botones."
            </section>";
}
?>
