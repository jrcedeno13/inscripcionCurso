<?php
require_once('db.php');

// Conexión a la base de datos
// Verificar la conexión

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
    //echo 'no hay coneccion desde registrarseCurso.php hacia la Base de datos';
}

//echo 'EXITO en coneccion desde registrarseCurso.php hacia la Base de datos';
// Consultar los cursos disponibles
$sql = "SELECT curso_id, nombre, descripcion, cupo FROM cursos";
$result = $conn->query($sql);
?> 
