<?php
include('db.php');

// Verificar que el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['curso_id'])) {
    $curso_id = intval($_POST['curso_id']);

    // Eliminar la inscripción del usuario en el curso
    $sql_delete = "DELETE FROM inscritos WHERE curso_id = ? AND nombre = (SELECT primer_nombre FROM users WHERE id = ?) AND primer_apellido = (SELECT primer_apellido FROM users WHERE id = ?)";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("iii", $curso_id, $user_id, $user_id);

    if ($stmt_delete->execute()) {
        // Actualizar el cupo del curso
        $sql_update_cupo = "UPDATE cursos SET cupo = cupo + 1 WHERE curso_id = ?";
        $stmt_update = $conn->prepare($sql_update_cupo);
        $stmt_update->bind_param("i", $curso_id);
        $stmt_update->execute();

        $_SESSION['message'] = "Te has dado de baja del curso con éxito.";
        $_SESSION['message_type'] = "success";
        header("Location: dashboard.php");
    } else {
        $_SESSION['message'] = "Hubo un error al eliminar la inscripción.";
        $_SESSION['message_type'] = "error";
        header("Location: dashboard.php");
    }
} else {
    $_SESSION['message'] = "Curso no válido.";
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php");
}
?>
