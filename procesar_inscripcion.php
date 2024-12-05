<?php
require_once('db.php'); 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Debes iniciar sesión para inscribirte."]);
    exit;
}

// Obtener el ID del usuario logueado
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['curso_id'])) {
        $curso_id = intval($_POST['curso_id']);
        // Procesar la inscripción usando $curso_id
    } else {
        echo "Error: No se seleccionó ningún curso.";
    }
}

// Validar si el usuario tiene sus datos completos
$sql_validar = "SELECT primer_nombre, primer_apellido, correo FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_validar);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Verificar campos obligatorios
    if (empty($usuario['primer_nombre']) || empty($usuario['primer_apellido']) || empty($usuario['correo'])) {
        echo json_encode(["error" => "Completa tu perfil antes de inscribirte en un curso."]);
        header("Location: formulario_Act_Datos.php");
        exit;
    }
} else {
    echo json_encode(["error" => "Usuario no encontrado."]);
    exit;
}
//echo 'Validacion correcta de perfil completo de usuario';

// Aquí va el código para procesar la inscripción
if (isset($_POST['curso_id'])) {
    $curso_id = intval($_POST['curso_id']);

    // Validar que el curso existe y tiene cupo
    $sql_curso = "SELECT cupo FROM cursos WHERE curso_id = ?";
    $stmt_curso = $conn->prepare($sql_curso);
    $stmt_curso->bind_param("i", $curso_id);
    $stmt_curso->execute();
    $result_curso = $stmt_curso->get_result();

    //echo 'Validacion correcta de cupos disponibles';
    
    if ($result_curso->num_rows > 0) {
        $curso = $result_curso->fetch_assoc();

        if ($curso['cupo'] > 0) {
            
            // Insertar inscripción
            
            $sql_inscripcion = "INSERT INTO inscritos (nombre, primer_apellido, email, telefono, curso_id, fecha_inscripcion) VALUES ( ?, ?, ?, ?, ?, NOW())";
            $stmt_inscripcion = $conn->prepare($sql_inscripcion); 
            $stmt_inscripcion->bind_param("ssssi", $nombre, $primer_apellido, $email, $telefono, $curso_id);

            //echo 'incripcion ok';

            if ($stmt->execute()) {  
                
                $query = "UPDATE cursos SET cupo = cupo - 1 WHERE id = $curso_id";
                echo "cupo  actualizado";
                header('Location:dashboard.php');

            } else {
                echo "Error al registrar la inscripción: " . $conn->error;
            }
            
            

                      
        } else {
            echo json_encode(["error" => "No hay cupos disponibles para este curso."]);
        }
    } else {
        echo json_encode(["error" => "Curso no encontrado."]);
    }
} else {
    echo json_encode(["error" => "ID de curso no válido."]);
}
?>
