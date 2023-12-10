<?php
session_start();

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["txttitulo"];
    $contenido = $_POST["txtcontenido"];
    $autor_id = $_POST["txtautor_id"];
    $categoria_id = $_POST["txtcategoria_id"];

    // Realiza el registro de la entrada
    try {
        $stmt = $dbh->prepare("INSERT INTO Entradas (tituloEntrada, contenidoEntrada, idUsuario, idCategoria) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titulo, $contenido, $autor_id, $categoria_id]);

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $ex) {
        $error = "Error al registrar la entrada: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Entrada</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registro de Entrada</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="txttitulo">Título:</label>
                        <input type="text" id="txttitulo" name="txttitulo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtcontenido">Contenido:</label>
                        <textarea id="txtcontenido" name="txtcontenido" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="txtautor_id">ID del Autor:</label>
                        <input type="number" id="txtautor_id" name="txtautor_id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtcategoria_id">ID de Categoría:</label>
                        <input type="number" id="txtcategoria_id" name="txtcategoria_id" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Registrar Entrada</button>
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
