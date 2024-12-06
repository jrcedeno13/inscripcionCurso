<?php
include ('db.php');
include ('cursosDisponibles.php');
//session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Obtener el ID del usuario logueado
$user_id = $_SESSION['user_id'];

// Obtener los cursos en los que el usuario está inscrito
$sql_cursos_usuario = "SELECT c.curso_id, c.nombre, c.descripcion, c.cupo FROM cursos c
                       JOIN inscritos i ON c.curso_id = i.curso_id
                       WHERE i.nombre = (SELECT primer_nombre FROM users WHERE id = ?) 
                       AND i.primer_apellido = (SELECT primer_apellido FROM users WHERE id = ?)";
$stmt_cursos = $conn->prepare($sql_cursos_usuario);
$stmt_cursos->bind_param("ii", $user_id, $user_id);
$stmt_cursos->execute();
$result_cursos_usuario = $stmt_cursos->get_result();

?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de Inscripciones</title>

        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
        
        
        <!-- JavaScript -->
        <script>
            function confirmarCierre(event) {
                event.preventDefault(); // Evita el envío del formulario por defecto
                const confirmacion = confirm("¿Está seguro de que desea cerrar sesión?");
                if (confirmacion) {
                    // Si el usuario confirma, envía el formulario
                    document.getElementById("logoutForm").submit();
                }
            }
        </script>

        <script>
            function confirmarCurso(event) {
                event.preventDefault(); // Evita el envío del formulario por defecto
                const confirmacion = confirm("¿Deseas registrarte en el curso?");
                if (confirmacion) {
                    // Si el usuario confirma, envía el formulario
                    document.getElementById("registerCurso").submit();
                }
            }
        </script>

    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Mi panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menú
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="formulario_Act_Datos.php">Completar perfil</a></li>
                                <li><a class="dropdown-item" href="actualizacionUserPsw.php">Actualizar datos de ingreso</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="confirmarEliminarCuenta()">Eliminar cuenta</a></li>
                                <li><a class="dropdown-item" href="#" onclick="confirmarCierre(event)">Cerrar sesión</a><li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenido -->
        
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h1 class="display-4">Bienvenido al Sistema de Inscripciones, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                            <p class="lead">¡Has iniciado sesión exitosamente!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h3 class="display-5">Registro de curso.</h3>  
                            <form id="registerCurso" action="procesar_inscripcion.php" method="POST">
                                <!-- Selección del curso -->
                                <div class="mb-3">
                                    <label for="curso" class="form-label">Seleccione un Curso:</label>
                                    <select class="form-select" id="curso" name="curso_id" aria-label="Seleccione un curso" required>
                                        <option selected disabled>Seleccione un curso</option>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            // Generar las opciones dinámicamente
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['curso_id'] . '">'
                                                    . htmlspecialchars($row['nombre']) . ' 
                                                    (Descripción: ' . htmlspecialchars($row['descripcion']) . ') - 
                                                    (Cupos disponibles: ' . htmlspecialchars($row['cupo']) . ')'
                                                    . '</option>';
                                            }
                                        } else {
                                            echo '<option disabled>No hay cursos disponibles</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- Botón para enviar el formulario -->
                                <div class="card-body">
                                    <button type="submit" class="btn btn-success mt-3">Tomar curso</button>
                                    <!-- <button type="button" class="btn btn-success mt-3" onclick="confirmarCurso(event)">Tomar curso</button> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar los cursos registrados -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h3 class="display-6">Tus cursos registrados</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Cupos disponibles</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_cursos_usuario->num_rows > 0) {
                                    $counter = 1;
                                    while ($row = $result_cursos_usuario->fetch_assoc()) {
                                        echo "<tr>
                                                <th scope='row'>{$counter}</th>
                                                <td>" . htmlspecialchars($row['nombre']) . "</td>
                                                <td>" . htmlspecialchars($row['descripcion']) . "</td>
                                                <td>" . htmlspecialchars($row['cupo']) . "</td>
                                                <td>
                                                    <form action='eliminar_inscripcion.php' method='POST'>
                                                        <input type='hidden' name='curso_id' value='{$row['curso_id']}'>
                                                        <button type='submit' class='btn btn-danger'>Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No tienes cursos registrados.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logoutForm" action="logout.php" method="POST">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <button type="button" class="btn btn-danger mt-3" onclick="confirmarCierre(event)">Cerrar sesión</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <?php
        if (isset($_SESSION['message'])) {
            echo "
            <script>
                Swal.fire({
                    icon: '{$_SESSION['message_type']}',
                    title: '{$_SESSION['message_title']}',
                    text: '{$_SESSION['message']}',
                    confirmButtonText: 'Aceptar'
                });
            </script>";
            // Limpiar el mensaje después de mostrarlo
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            unset($_SESSION['message_title']);
        }
    ?>

    <!-- Formulario de logout oculto -->
    <form id="logoutForm" action="logout.php" method="POST" style="display: none;"></form>
    <form id="registerCurso" action="procesar_inscripcion.php" method="POST" style="display: none;"></form>
    <form id="deleteAccountForm" action="delete_account.php" method="POST" style="display: none;"></form>

    <!-- Script para confirmar cierre -->
    <script>
        function confirmarCierre() {
            Swal.fire({
                title: '¿Está seguro de que desea cerrar sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envía el formulario de logout
                    document.getElementById("logoutForm").submit();
                }
            });
        }

        // Confirmar eliminación de cuenta
        function confirmarEliminarCuenta() {
            Swal.fire({
                title: '¿Está seguro de que desea eliminar su cuenta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("deleteAccountForm").submit();
                }
            });
        }
    </script>
    
    <?php
    // Cerrar la conexión
    $conn->close();
    ?>
    
<!-- Bootstrap JS (opcional para funcionalidad avanzada) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>






