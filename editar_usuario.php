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
    // Procesar la actualización de la entrada en la base de datos
    $idEntrada = $_POST['idEntrada'];
    $nuevoNick = $_POST['nuevoNick'];
    $nuevoNombre = $_POST['nuevoNombre'];

    // Realizar la actualización en la base de datos
    $stmt = $dbh->prepare("UPDATE entradas SET TITULO = ?, DESCRIPCION = ? WHERE ID = ?");
    $stmt->execute([$nuevoNick, $nuevoNombre, $idEntrada]);

    header("Location: listado_usuarios.php");
    // No es necesario exit() aquí
} else {
    // Obtener la entrada a editar
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
    <title>Editar Entrada</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Usuario</h2>
        <form method="post" action="">
            <input type="hidden" name="idEntrada" value="<?php echo $usuario['ID']; ?>">
            <div class="form-group">
                <label for="nuevoNick">Nick:</label>
                <input type="text" id="nuevoNick" name="nuevoNick" class="form-control" value="<?php echo $usuario['NICK']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nuevoNombre">Nombre:</label>
                <textarea id="nuevoNombre" name="nuevoNombre" class="form-control" required><?php echo $usuario['NOMBRE']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nuevoApellido">Apellido:</label>
                <textarea id="nuevoApellido" name="nuevoApellido" class="form-control" required><?php echo $usuario['APELLIDO']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nuevoMail">Email:</label>
                <textarea id="nuevoMail" name="nuevoMail" class="form-control" required><?php echo $usuario['EMAIL']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nuevoPassword">Contraseña:</label>
                <textarea id="nuevoPassword" name="nuevoPassword" class="form-control" required><?php echo $usuario['PASSWORD']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nuevoTipo">Rol:</label>
                <textarea id="nuevoTipo" name="nuevoTipo" class="form-control" required><?php echo $usuario['tipo']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>

        <a href="listado_entradas.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
