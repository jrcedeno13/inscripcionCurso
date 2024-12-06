<?php
require_once('db.php'); 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Debes iniciar sesión para inscribirte."]);
    exit;
}

// Obtener el ID del usuario logueado
$user_id = $_SESSION['user_id'];

// Validar si el usuario tiene sus datos completos
$sql_validar = "SELECT primer_nombre, primer_apellido, correo, numero_celular FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_validar);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc(); // Obtener los datos de usuario como arreglo

if ($result->num_rows > 0) {
    // Verificar campos obligatorios
    if (empty($usuario['primer_nombre']) || empty($usuario['primer_apellido']) || empty($usuario['correo'])) {

        $_SESSION['message'] = "Completa tu perfil antes de inscribirte en un curso.";
        $_SESSION['message_type'] = "warning";
        $_SESSION['message_title'] = "Alerta";
        header("Location: formulario_Act_Datos.php");
    
        exit;
    }
} else {
    echo "<script>
        alert('Usuario no encontrado. Por favor, inicia sesión nuevamente.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Aquí va el código para procesar la inscripción
if (isset($_POST['curso_id'])) {
    $curso_id = intval($_POST['curso_id']);

    // Validar si el usuario ya está inscrito en este curso por nombre y apellido
    $sql_check = "SELECT * FROM inscritos WHERE nombre = ? AND primer_apellido = ? AND curso_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ssi", $usuario['primer_nombre'], $usuario['primer_apellido'], $curso_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // Si el usuario ya está inscrito, mostrar mensaje y evitar la inscripción
    if ($result_check->num_rows > 0) {
        $_SESSION['message'] = "Ya estás inscrito en el curso: " . htmlspecialchars($curso['nombre']);
        $_SESSION['message_type'] = "warning"; 
        $_SESSION['message_title'] = "Alerta";
        header("Location: dashboard.php");
        exit;
    }

    // Validar que el curso existe y tiene cupo
    $sql_curso = "SELECT nombre, descripcion, cupo FROM cursos WHERE curso_id = ?";
    $stmt_curso = $conn->prepare($sql_curso);
    $stmt_curso->bind_param("i", $curso_id);
    $stmt_curso->execute();
    $result_curso = $stmt_curso->get_result();
    $curso = $result_curso->fetch_assoc();// Obtener los datos del curso como arreglo

    // pruebas de toma de datos
    // echo "Nombre del curso: " . htmlspecialchars($curso['nombre']) . "<br>";
    // echo "Descripcion del curso: " . htmlspecialchars($curso['descripcion']) . "<br>";
    // echo "Validación correcta de cupos disponibles en el curso con ID: " . $curso_id . "<br>";
    // echo "Nombre: " . htmlspecialchars($usuario['primer_nombre']) . "<br>";
    // echo "Apellido: " . htmlspecialchars($usuario['primer_apellido']) . "<br>";
    // echo "Correo: " . htmlspecialchars($usuario['correo']) . "<br>";
    // echo "Celular: " . htmlspecialchars($usuario['numero_celular']) . "<br>";
    // echo date('d-m-Y H:i:s');
    
        if ($result_curso->num_rows > 0) {
            
            if ($curso['cupo'] > 0) {
                
                // Insertar inscripción            
                $sql_inscripcion = "INSERT INTO inscritos (nombre, primer_apellido, email, telefono, curso_id, fecha_inscripcion) VALUES ( ?, ?, ?, ?, ?, NOW())";
                $stmt_inscripcion = $conn->prepare($sql_inscripcion); 
                $stmt_inscripcion->bind_param("ssssi", $usuario['primer_nombre'], $usuario['primer_apellido'], $usuario['correo'], $usuario['numero_celular'], $curso_id);

                if ($stmt_inscripcion->execute()) {
                    // Actualizar cupo del curso
                    $sql_update_cupo = "UPDATE cursos SET cupo = cupo - 1 WHERE curso_id = ?";
                    $stmt_update = $conn->prepare($sql_update_cupo);
                    $stmt_update->bind_param("i", $curso_id);
    
                    if ($stmt_update->execute()) {

                        $_SESSION['message'] = "Inscripción exitosa. Te has registrado en el curso: " . htmlspecialchars($curso['nombre']);
                        $_SESSION['message_type'] = "success"; 
                        $_SESSION['message_title'] = "¡Éxito!";
                        header("Location: dashboard.php");

                    } else {

                        $_SESSION['message'] = "Hubo un error al registrar tu inscripción. Inténtalo de nuevo más tarde.";
                        $_SESSION['message_type'] = "error";
                        $_SESSION['message_title'] = "Error";
                        header("Location: dashboard.php");
                        
                    }
                }
            } else {

                $_SESSION['message'] = "No hay cupos disponibles para este curso. Inténtalo de nuevo más tarde.";
                $_SESSION['message_type'] = "error";
                $_SESSION['message_title'] = "Error";
                header("Location: dashboard.php");
                
            }
        } else {
            $_SESSION['message'] = "Curso no encontrado.";
            $_SESSION['message_type'] = "error";
            $_SESSION['message_title'] = "Error";
            header("Location: dashboard.php");
        
        }
    } else {
        $_SESSION['message'] = "No se recibió un curso válido.";
        $_SESSION['message_type'] = "error";
        $_SESSION['message_title'] = "Error";
        header("Location: dashboard.php");
        
    }
    
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
