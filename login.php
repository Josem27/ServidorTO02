<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Realiza la autenticación (en un entorno real, utiliza funciones de hash y verifica la contraseña correctamente)
    require_once 'conexion.php';
    $stmt = $dbh->prepare("SELECT * FROM Usuarios WHERE nombreUsuario = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['contrasenaUsuario'])) {
        $_SESSION["idUsuario"] = $user['idUsuario'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Iniciar sesión</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
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

