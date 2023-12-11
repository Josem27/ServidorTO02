<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nick = $_POST["nick"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $tipo = 0;

    // Obtener la imagen
    $imagen = $_FILES["imagen"]["name"];
    $imagen_temporal = $_FILES["imagen"]["tmp_name"];
    $ruta = "images/" . $imagen;

    // Mover la imagen a la carpeta de uploads
    move_uploaded_file($imagen_temporal, $ruta);

    // Realizar el registro del usuario
    try {
        $stmt = $dbh->prepare("INSERT INTO usuarios (NICK, NOMBRE, APELLIDOS, EMAIL, PASSWORD, IMAGEN_AVATAR, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nick, $nombre, $apellidos, $email, $password, $ruta, $tipo]);

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
                        <label for="nick">Nick:</label>
                        <input type="text" id="nick" name="nick" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen Avatar:</label>
                        <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Registrar Usuario</button>
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
