<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NICK = $_POST["Nick"];
    $PASSWORD = $_POST["PASSWORD"];

    require_once 'config.php';

    $dbh = include 'config.php';

    if ($dbh === null) {
        die("Error: La conexión a la base de datos es nula.");
    }

    $stmt = $dbh->prepare("SELECT * FROM usuarios WHERE NICK = ?");
    $stmt->execute([$NICK]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $PASSWORD === $user['PASSWORD']) {
        $_SESSION["ID"] = $user['ID'];
        header("Location: index.php");
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
                        <label for="Nick">Usuario:</label>
                        <input type="text" id="Nick" name="Nick" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="PASSWORD">Contraseña:</label> <!-- Corregir el nombre del campo de contraseña -->
                        <input type="password" id="PASSWORD" name="PASSWORD" class="form-control" required>
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
