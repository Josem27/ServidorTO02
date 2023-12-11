<?php
session_start();

if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

// Verificar si se proporcionó un ID de usuario válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_usuarios.php");
    exit();
}

// Obtener los detalles del usuario específico
$idUsuario = $_GET['id'];
$stmt = $dbh->prepare("SELECT * FROM usuarios WHERE ID = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario existe
if (!$usuario) {
    header("Location: listado_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Detalles de Usuario</h2>

        <div class="card">
            <div class="row no-gutters">
                <div class="col-md-8">
                    <div class="card-body">
                        <p class="card-text"><strong>Nick:</strong> <?php echo $usuario['NICK']; ?></p>
                        <p class="card-text"><strong>Nombre:</strong> <?php echo $usuario['NOMBRE']; ?></p>
                        <p class="card-text"><strong>Apellidos:</strong> <?php echo $usuario['APELLIDOS']; ?></p>
                        <p class="card-text"><strong>Email:</strong> <?php echo $usuario['EMAIL']; ?></p>
                        <p class="card-text"><strong>Tipo:</strong> <?php echo $usuario['tipo']; ?></p>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <?php if ($usuario['IMAGEN_AVATAR'] != null) { ?>
                        <img src="<?php echo $usuario['IMAGEN_AVATAR']; ?>" class="card-img" style="max-width: 50%;" alt="Foto de perfil">
                    <?php } ?>
                </div>
            </div>
        </div>

        <a href="listado_usuarios.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
