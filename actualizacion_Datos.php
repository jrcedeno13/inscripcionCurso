<?php
require_once('db.php');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

    // Datos del nuevo usuario
    $user_id = $_SESSION['user_id']; // Obtiene el ID del usuario actual
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $fecha_nac = $_POST['fecha_nac'];
    $num_celular = $_POST['numero_celular'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $correo = $_POST['correo'];

    // Procesa el formulario si se envió
    // Actualiza los datos en la base de datos
        
    $query = "UPDATE users 
        SET primer_nombre = '$primer_nombre', 
            segundo_nombre = '$segundo_nombre', 
            primer_apellido = '$primer_apellido', 
            segundo_apellido = '$segundo_apellido', 
            fecha_nacimiento = '$fecha_nac', 
            numero_celular = '$num_celular', 
            pais = '$pais', 
            ciudad = '$ciudad', 
            correo = '$correo' 
        WHERE id = '$user_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = '¡Datos actualizados correctamente! ahora puedes registrarte en un curso.';
        $_SESSION['message_type'] = 'success';
        $_SESSION['message_title'] = 'Actualización exitosa.';
        header('Location:dashboard.php');
        exit();
    } else {
        $_SESSION['message'] = 'Error al actualizar los datos:' . mysqli_error($conn);
        $_SESSION['message_type'] = 'error';
        $_SESSION['message_title'] = 'Error en actualización.';
        header('Location:dashboard.php');
        exit();
    }

    // Cierra la conexión
    $conn->close();
?>