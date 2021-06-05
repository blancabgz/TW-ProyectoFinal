<?php
require_once "../controller/check_login.php";
require_once "../controller/validacion.php";
require_once "../model/basedatos.php";
require_once "../view/vistasHTML.php";
require_once "../view/formularios.php";

$titulo="Editar usuario";
$form = '../controller/editUser.php';
$accion = 'e';

HTMLinicio($titulo);
HTMLheader($titulo);
HTMLnav($rol);

//si es administrador, puede modificar cualquier campo del usuario
if($rol == 'A'){
	$titulo_form = "Modificar usuario";

	//si se ha enviado los datos, se procesan los datos, la fotografía y se muestra el formulario
	if(isset($_POST['enviarDatos'])){
        procesarFotografia();
        $datos = procesarDatos($_POST);
        formularioUSU03($datos, $accion, $form, $titulo_form, $rol);
	}

    //si viene del listado se obtiene los datos y se muestran en un formulario
    else if(isset($_POST['editarUser'])){
        $_SESSION['dniOld'] = $_POST['dni'];
        $datos = obtenerDatosUsuario($_SESSION['dniOld']);

        //si es un array, es la consulta, se muestra por el formulario
        if(is_array($datos)){
            formularioUSU02($datos, '', $form, $titulo_form, $rol, $accion);
        }
        //si no, ha habido error
        else{
            mensaje($titulo, $datos);
        }
    }

    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, $rol);

        //si hay errores se muestra el formulario
        if(!empty($validar)){
            formularioUSU02($datos, $validar, $form, $titulo_form, $rol, $accion);
        }
        //si está todo OK se actualiza el usuario
        else{
            $mensaje = actualizarUsuario($datos, $rol, $_SESSION['dniOld']);
            mensaje($titulo_form, $mensaje);
        }
	}
	else{
		header("Location: ../view/inicio.php");
	}
}

//si es el paciente o el sanitario, puede modificar solo unos campos concretos
else if($rol == 'P' || $rol == 'S'){
    $titulo_form = "Modificar mis datos";

	//si se ha enviado los datos, se procesa la imagen y los datos y se muestra el formulario
	if(isset($_POST['enviarDatos'])){
        formularioUSU06($_POST, $accion, $form, $titulo_form);
	}

    //si va a validar los datos, se procesan y validan los datos
	else if(isset($_POST['validarDatos'])){
        $datos = procesarDatos($_POST);
	    $validar = validarDatos($datos, $rol);

        //si hay errores, se muestra el formulario
        if(!empty($validar)){
            formularioUSU05($datos, $validar, $form, $titulo_form);
        }
        //si está todo OK se actualiza el usuario
        else{
            $mensaje = actualizarUsuario($datos, $rol, $_SESSION['usuario']);
            mensaje($titulo_form, $mensaje);
        }
	}
    
    //si ha pulsado en Datos Personales
	else{
        $datos = obtenerDatosUsuario($_SESSION['usuario']);
        
        //si es un array, es la consulta, se muestra por el formulario
        if(is_array($datos)){
            formularioUSU05($datos, $accion, $form, $titulo_form);
        }
        //si no, ha habido error
        else{
            mensaje($titulo, $datos);
        }        
	}
}

//si no es administrador, ni paciente ni sanitario, se le redirige al inicio
else{
    echo "<br>".$rol;
	//header("Location: ../view/inicio.php");
}

HTMLformulario($rol);
HTMLfooter();
?>