<?php
session_start();

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCategoria = $_POST["txtnombreCategoria"];

    // Realiza el registro de la categoría
    try {
        $stmt = $dbh->prepare("INSERT INTO Categorias (nombreCategoria) VALUES (?)");
        $stmt->execute([$nombreCategoria]);

        header("Location: index.php");
        exit();
    } catch (PDOException $ex) {
        $error = "Error al registrar la categoría: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Categoría</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registro de Categoría</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="txtnombreCategoria">Nombre de la Categoría:</label>
                        <input type="text" id="txtnombreCategoria" name="txtnombreCategoria" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Registrar Categoría</button>
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
