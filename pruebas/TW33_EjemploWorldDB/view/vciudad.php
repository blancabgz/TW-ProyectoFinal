<?php
/*
  (C) 2021. Javier Martínez Baena (jbaena@ugr.es)
  Tecnologías Web. Grado en Ingeniería Informática. Universidad de Granada.
  Este código tiene un objetivo puramente didáctico por lo que se podría mejorar en múltiples aspectos. Pretende ejemplificar sobre la conexión con BBDD desde el lenguaje PHP para crear páginas web dinámicas usando un modelo básico MVC. Es posible que contenga errores y se distribuye para facilitar el estudio de la asignatura.
  No se permite el uso de este código sin autorización del autor.
*/

/*
  Funciones para crear distintas vistas de los datos del modelo ciudad. Estas vistas permiten presentar listados, mostrar formularios de edición de ciudades, etc.
*/

require_once('msg.php');

// Mostrar tabla con listado de ciudades
// $datos: array asociativo
//         cada elemento es un registro (name,district,population)
function VCiudad_listadoCiudades($datos) {
echo <<< HTML
<div class='listado'>
  <table>
    <tr>
      <th>Ciudad</th>
      <th>Comunidad</th>
      <th>Población</th>
    </tr>
HTML;

foreach ($datos as $v) {
  echo '<tr>';
  echo '<td class="ciu_nombre">'.htmlentities($v['name']).'</td>';
  echo '<td class="ciu_comunidad">'.htmlentities($v['district']).'</td>';
  echo '<td class="ciu_poblacion">'.htmlentities($v['population']).'</td>';
  echo '</tr>';
}

echo <<< HTML
  </table>
</div>
HTML;
}

// Mostrar tabla con listado de ciudades
// $datos: array asociativo
//         cada elemento es un registo (name,district,population)
function VCiudad_listadoCiudadesBotones($datos,$accion) {
echo <<< HTML
<div class='listado'>
  <table>
    <tr>
      <th>Ciudad</th>
      <th>Comunidad</th>
      <th>Población</th>
      <th>Acción</th>
    </tr>
HTML;

foreach ($datos as $v) {
  echo '<tr>';
  echo '<td class="ciu_nombre">'.htmlentities($v['name']).'</td>';
  echo '<td class="ciu_comunidad">'.htmlentities($v['district']).'</td>';
  echo '<td class="ciu_poblacion">'.htmlentities($v['population']).'</td>';
  echo "<td class='ciu_botones'><form action='$accion' method='POST'>
              <input type='hidden' name='id' value='{$v['id']}' />
              <input type='submit' name='accion' value='Editar' />
              <input type='submit' name='accion' value='Borrar' />
            </form></td>";
  echo '</tr>';
}

echo <<< HTML
  </table>
</div>
HTML;
}

function VCiudad_editCiudad($titulo,$datos,$accion) {
  if (isset($datos['editable']) && $datos['editable']==false)
    $disabled='disabled="disabled"';  // De esta forma no se envían los datos
    //$disabled='readonly="readonly"';  // Así sí se envían los datos
  else
    $disabled='';

  $datos['id'] = $datos['id'] ?? '';
  $datos['name'] = $datos['name'] ?? '';
  $datos['district'] = $datos['district'] ?? '';
  $datos['population'] = $datos['population'] ?? '';
/*
  if ($datos=='' || $datos==false) {
    $datos['id']='';
    $datos['name']='';
    $datos['district']='';
    $datos['population']='';
  }
*/
echo <<< HTML
<div class='frm_ciudad'>
  <form action='{$_SERVER["SCRIPT_NAME"]}' method='POST'>
    <h3>$titulo</h3>
    <input type='hidden' name='id' value='{$datos["id"]}'/>
    <div class='frm_ciudad_input'>
      <label for='name'>Nombre:</label>
      <input type='text' name='name' value='{$datos["name"]}' $disabled/>
    </div>
    <div class='frm_ciudad_input'>
      <label for='district'>Comunidad:</label>
      <input type='text' name='district' value='{$datos["district"]}' $disabled/>
    </div>
    <div class='frm_ciudad_input'>
      <label for='population'>Población:</label>
      <input type='text' name='population' value='{$datos["population"]}' $disabled/>
    </div>
    <div class='frm_ciudad_submit'>
      <input type='submit' name='accion' value='$accion' />
      <input type='submit' name='accion' value='Cancelar' />
    </div>
  </form>
</div>
HTML;
}

function VCiudad_buscarCiudad($titulo,$datos=false) {
  $bnombre = isset($datos['bnombre']) ? " value='{$datos['bnombre']}' " : '';
  $bcomunidad = isset($datos['bcomunidad']) ? " value='{$datos['bcomunidad']}' " : '';
  $bpobmin = isset($datos['bpobmin']) ? " value='{$datos['bpobmin']}' " : '';
  $bpobmax = isset($datos['bpobmax']) ? " value='{$datos['bpobmax']}' " : '';

echo <<< HTML
<div class='frm_ciudad'>
  <form action='{$_SERVER["SCRIPT_NAME"]}' method='POST'>
    <h3>$titulo</h3>
    <div class='frm_ciudad_input'>
      <label for='name'>Nombre:</label>
      <input type='text' name='name' $bnombre/>
    </div>
    <div class='frm_ciudad_input'>
      <label for='district'>Comunidad:</label>
      <input type='text' name='district' $bcomunidad/>
    </div>
    <div class='frm_ciudad_input'>
      <label for='population_min'>Población mínima:</label>
      <input type='text' name='population_min' $bpobmin/>
    </div>
    <div class='frm_ciudad_input'>
      <label for='population_max'>Población máxima:</label>
      <input type='text' name='population_max' $bpobmax/>
    </div>
    <div class='frm_ciudad_submit'>
      <input type='submit' name='accion' value='Buscar' />
    </div>
  </form>
</div>
HTML;
}

?>
