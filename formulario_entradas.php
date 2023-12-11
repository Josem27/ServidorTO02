<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del usuario actualmente logueado
    $idUsuario = $_SESSION["ID"];
    $titulo = $_POST["txttitulo"];
    $contenido = $_POST["txtcontenido"];
    $categoriaId = $_POST["categoria"];
    
        // Obtener la imagen
    $imagen = $_FILES["imagen"]["name"];
    $imagen_temporal = $_FILES["imagen"]["tmp_name"];
    $ruta = "uploads/" . $imagen;

    // Mover la imagen a la carpeta de uploads
    move_uploaded_file($imagen_temporal, $ruta);

    // Realiza el registro de la entrada
    try {
        $stmt = $dbh->prepare("INSERT INTO entradas (CATEGORIA_ID, TITULO, IMAGEN, DESCRIPCION, USUARIO_ID, FECHA) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$categoriaId, $titulo, $ruta, $contenido, $idUsuario]);

        header("Location: index.php");
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
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" class="form-control" required>
                            <!-- Aquí puedes cargar dinámicamente las categorías desde tu base de datos si es necesario -->
                            <option value="1">Categoría 1</option>
                            <option value="2">Categoría 2</option>
                            <!-- Agrega más opciones según sea necesario -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txttitulo">Título:</label>
                        <input type="text" id="txttitulo" name="txttitulo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="txtcontenido">Descripción:</label>
                        <textarea id="txtcontenido" name="txtcontenido" class="form-control" required></textarea>
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
