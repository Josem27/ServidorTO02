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
    // Procesar la actualización del usuario en la base de datos
    $idUsuario = $_POST['idUsuario'];
    $nuevoNick = $_POST['nuevoNick'];
    $nuevoNombre = $_POST['nuevoNombre'];
    $nuevoApellido = $_POST['nuevoApellido'];
    $nuevoEmail = $_POST['nuevoEmail'];
    $nuevoPassword = $_POST['nuevoPassword'];

    // Obtener la ruta de la foto existente
    $stmtFoto = $dbh->prepare("SELECT IMAGEN_AVATAR FROM usuarios WHERE ID = ?");
    $stmtFoto->execute([$idUsuario]);
    $fotoExistente = $stmtFoto->fetchColumn();

    // Verificar si se proporcionó una nueva foto
    if (!empty($_FILES['nuevaFoto']['name'])) {
        $nuevaFoto = $_FILES['nuevaFoto']['name'];
        $fotoTemporal = $_FILES['nuevaFoto']['tmp_name'];
        $rutaFoto = "images/" . $nuevaFoto;

        // Mover la nueva foto a la carpeta de images
        move_uploaded_file($fotoTemporal, $rutaFoto);
    } else {
        // Conservar la foto existente si no se proporcionó una nueva
        $rutaFoto = $fotoExistente;
    }

    // Realizar la actualización en la base de datos
    $stmt = $dbh->prepare("UPDATE usuarios SET NICK = ?, NOMBRE = ?, APELLIDOS = ?, EMAIL = ?, PASSWORD = ?, IMAGEN_AVATAR = ? WHERE ID = ?");
    $stmt->execute([$nuevoNick, $nuevoNombre, $nuevoApellido, $nuevoEmail, $nuevoPassword, $rutaFoto, $idUsuario]);

    header("Location: listado_usuarios.php");
} else {
    // Obtener el usuario a editar
    $idUsuario = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM usuarios WHERE ID = ?");
    $stmt->execute([$idUsuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Usuario</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="idUsuario" value="<?php echo $usuario['ID']; ?>">
            
            <div class="form-group">
                <label for="nuevoNick">Nick:</label>
                <input type="text" id="nuevoNick" name="nuevoNick" class="form-control" value="<?php echo $usuario['NICK']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoNombre">Nombre:</label>
                <input type="text" id="nuevoNombre" name="nuevoNombre" class="form-control" value="<?php echo $usuario['NOMBRE']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoApellido">Apellidos:</label>
                <input type="text" id="nuevoApellido" name="nuevoApellido" class="form-control" value="<?php echo $usuario['APELLIDOS']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoEmail">Email:</label>
                <input type="email" id="nuevoEmail" name="nuevoEmail" class="form-control" value="<?php echo $usuario['EMAIL']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoPassword">Contraseña:</label>
                <input type="password" id="nuevoPassword" name="nuevoPassword" class="form-control" value="<?php echo $usuario['PASSWORD']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevaFoto">Nueva Foto:</label>
                <input type="file" id="nuevaFoto" name="nuevaFoto" class="form-control-file" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>

        <a href="listado_usuarios.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
