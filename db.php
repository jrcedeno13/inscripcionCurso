<?php
session_start();

//coneccion a base u guardado en variable
$conn = mysqli_connect(
  'localhost',
  'root',
  '',
  'curso_inscripciones'
);
/*if(isset($conn)){
  echo 'Base de datos conectada';
}*/

?>