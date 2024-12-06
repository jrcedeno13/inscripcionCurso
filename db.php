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

//definicion de zona horaria, formato de fecha y hora para el servidor
date_default_timezone_set('America/Guayaquil');
//echo "La fecha y hora actual es: " . date('d-m-Y H:i:s');


?>