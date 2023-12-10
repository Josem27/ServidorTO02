<?php
session_start();

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["txtnombre"];
    $correo = $_POST["txtcorreo"];
    $password = password_hash($_POST["txtpassword"], PASSWORD_DEFAULT);

    // Realiza el registro de usuarios
    try {
        $stmt = $dbh->prepare("INSERT INTO Usuarios (nombreUsuario, correoUsuario, contrasenaUsuario) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $password]);

        $_SESSION["idUsuario"] = $dbh->lastInsertId(); // Obtiene el ID del usuario recién registrado
        header("Location: index.php");
        exit();
    } catch (PDOException $ex) {
        $error = "Error al registrar el usuario: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registro de Usuario</h2>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="txtnombre">Nombre:</label>
                        <input type="text" id="txtnombre" name="txtnombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtcorreo">Correo Electrónico:</label>
                        <input type="text" id="txtcorreo" name="txtcorreo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtpassword">Contraseña:</label>
                        <input type="password" id="txtpassword" name="txtpassword" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                </form>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <!-- Botón de vuelta a index.php -->
                <a href="index.php" class="btn btn-secondary btn-block mt-3">Inicio</a>
            </div>
        </div>
    </div>

    <!-- Agregar enlaces a Bootstrap JS y jQuery al final del cuerpo -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
