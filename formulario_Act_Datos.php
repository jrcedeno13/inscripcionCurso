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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
                                <li><a class="dropdown-item" href="dashboard.php">Inicio</a></li>
                                <li><a class="dropdown-item" href="actualizacionUserPsw.php">Actualizar datos de ingreso</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="confirmarEliminarCuenta()">Eliminar cuenta</a></li>
                                <li><a class="dropdown-item" href="#" onclick="confirmarCierre(event)">Cerrar sesión</a><li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <form action="actualizacion_Datos.php" method="POST">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h3 class="text-center">Formulario de registro para curso.</h3>
                        <form action="save_user.php" method="POST">
                            <div class="mb-3">
                                <label for="primer_nombre" class="form-label">Primer Nombre:</label>
                                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" placeholder="Ingrese su primer nombre" autofocus required>
                            </div>
                            <div class="mb-3">
                                <label for="segundo_nombre" class="form-label">Segundo Nombre:</label>
                                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" placeholder="Ingrese su segundo nombre">
                            </div>
                            <div class="mb-3">
                                <label for="primer_apellido" class="form-label">Primer Apellido:</label>
                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" placeholder="Ingrese su primer apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="segundo_apellido" class="form-label">Segundo Apellido:</label>
                                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" placeholder="Ingrese su segundo apellido">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero_celular" class="form-label">Número de Celular:</label>
                                <input type="tel" class="form-control" id="numero_celular" name="numero_celular" 
                                    placeholder="Ingrese su número con código de país, ej. +593999876543" 
                                    pattern="^\+?[0-9]{10,15}$" required>
                                <small class="form-text text-muted">Incluya el código del país (ejemplo: +593) seguido de su número de celular.</small>
                            </div>
                            <div class="mb-3">
                                <label for="pais" class="form-label">País:</label>
                                <input type="text" class="form-control" id="pais" name="pais" placeholder="Ingrese su país" required>
                            </div>
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad:</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ingrese su ciudad" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo:</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese su correo" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100" name="registrase_curso">Guardar datos</button>
                        </form>
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
        
        <?php include("includes/footer.php"); ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        