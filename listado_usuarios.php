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

// Obtener la lista de todos los usuarios
$stmt = $dbh->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Listado de Usuarios</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Nick</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) { ?>
                    <tr>
                        <td><?php echo $usuario['NICK']; ?></td>
                        <td><?php echo $usuario['NOMBRE']; ?></td>
                        <td><?php echo $usuario['APELLIDOS']; ?></td>
                        <td><?php echo $usuario['EMAIL']; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['ID']; ?>">Editar</a> |
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['ID']; ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a> |
                            <a href="detalles_usuario.php?id=<?php echo $usuario['ID']; ?>">Detalles</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary mt-3">Inicio</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
