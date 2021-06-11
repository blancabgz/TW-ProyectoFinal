<?php
require_once '../model/basedatos.php';

// copia de seguridad de la base de datos 
function copiaSeguridad() {

    $bd = conectarBD();
    $mensaje = 'Error al conectarse a la base de datos.';

    if($bd){
        // Obtener listado de tablas
        $nombreTabla = array();
        $consulta = mysqli_query($bd,'SHOW TABLES');
        
        //para cada tabla, almacenamos el identificador de dicha tabla
        while($nombre = mysqli_fetch_row($consulta))
            $nombreTabla[] = $nombre[0];
        
        //realizamos la copia de seguridad de cada tabla
        $restore = '';

        //para cada tabla
        foreach ($nombreTabla as $nt) {

            //obtenemos todas las filas de cada tabla y guardamos el número de tuplas
            $consulta = mysqli_query($bd,'SELECT * FROM '.$nt);
            $tuplas = mysqli_num_fields($consulta);

            //creamos la consulta de restore 
            $restore .= 'DROP TABLE IF EXISTS '.$nt.';';

            //almacenamos la creación de la tabla
            //[0] contiene el nombre, [1] contiene la consulta de creación
            $tabla = mysqli_fetch_row(mysqli_query($bd,'SHOW CREATE TABLE '.$nt));
            $restore .= "\n\n".$tabla[1].";\n\n";

            //para cada tupla
            while ($fila = mysqli_fetch_row($consulta)) {

                //realizamos la inserción en la tabla nt con los valores de la tabla
                $restore .= 'INSERT INTO '.$nt.' VALUES(';

                //para cada tupla
                for ($j=0; $j < $tuplas; $j++) {

                    //si hay valores, los tratamos y los almacenamos
                    if (!is_null($fila[$j])) {
                        $fila[$j] = addslashes($fila[$j]);
                        $fila[$j] = preg_replace("/\n/","\\n",$fila[$j]);
                        if (isset($fila[$j]))
                          $restore .= '"'.$fila[$j].'"';
                        else
                          $restore .= '""';
                    }
                    //si son nulos, almacenamos nulo
                    else
                        $restore .= 'NULL';

                    //si hay más tuplas, escribimos una , para la consutla
                    if ($j < ($tuplas-1))
                        $restore .= ',';
                }
                //al finalizar la tupla, escribimos );
                $restore .= ");\n";
            }

            $restore .= "\n\n\n";
            $fecha = date("Y-m-d_G:i:s");
            $copiaSeguridad = fopen('../backup/copia-seguridad-'.$fecha.'-'.(md5(implode(',',$nombreTabla))).'.sql','a+');

            if($copiaSeguridad){
                fwrite($copiaSeguridad, $restore);
                fclose($copiaSeguridad);
                $mensaje = 'Copia realizada con éxito, almacenada en la carpeta backup.';
            }
            else{
                $mensaje = 'Error al abrir el fichero para registrar la copia de seguridad.';
            }
        }
    }

    return $mensaje;
}

// restauración de la base de datos 
function restauracion($copiaSeguridad) {

    $bd = conectarBD();
    $mensaje = '';

    if($bd){
        //deshabilitamos las claves externas para facilitar la restauración
        mysqli_query($bd,'SET FOREIGN_KEY_CHECKS=0');

        //eliminamos la base de datos actual
        borrarBD($bd);

        //obtenemos los datos del fichero
        $sql = file_get_contents($copiaSeguridad);

        //dividimos las consultas
        $consultas = explode(';',$sql);
        
        //ejecutamos cada consulta
        foreach ($consultas as $cons) {

            //eliminamos los espacios en blanco del principio y final de la consulta
            $cons = trim($cons);

            //si la consulta no está vacía y ha habido error al ejecutarla
            //lo almacenamos en el mensaje
            if($cons != '' && !mysqli_query($bd, $cons))
                $mensaje .= mysqli_error($bd).". ";
        }

        //consignamos la transacción para la base de datos
        mysqli_commit($bd);

        //habilitamos las claves externas
        mysqli_query($bd,'SET FOREIGN_KEY_CHECKS=1');

        //si mensaje está vacío supone que no ha habido errores
        if(empty($mensaje)){
            $mensaje = "Restauración completada con éxito.";
        }
    }
    else{
        $mensaje = 'Error al conectarse a la base de datos.';
    }

    return $mensaje;
}

// borrar el contenido de las tablas de la BBDD 
function borrarBD($bd) {

    //obtenemos las tablas
    $consulta = mysqli_query($bd,'SHOW TABLES');
    
    //para cada tabla, la eliminamos
    while ($tupla = mysqli_fetch_row($consulta))
        mysqli_query($bd,'DELETE FROM *'.$tupla[0]);

    //consignamos la transacción para la base de datos
    mysqli_commit($bd);
}

// obtener el nombre de los ficheros de las copias de seguridad
function obtenerCopiasSeguridad(){
    
    //obtenemos los nombres de los ficheros de la carpeta donde se almacenan las copias de seguridad
    $directorio = '../backup';
    $copias = scandir($directorio, 1);

    //si ha habido error
    if(!$copias){
        $copias = 'Error al obtener los ficheros.';
    }

    return $copias;
}
?>