<?php
session_start();

// Configuración de la base de datos
$host = 'localhost'; // Dirección del servidor (usualmente 'localhost' en entornos locales)
$usuario = 'root';   // Usuario de la base de datos
$contraseña = '';    // Contraseña del usuario
$base_datos = 'curso_inscripciones'; // Nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Conexión exitosa a la base de datos.";
?>
