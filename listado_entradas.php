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

// Obtener el tipo de usuario actual
$idUsuario = $_SESSION["ID"];
$stmtTipoUsuario = $dbh->prepare("SELECT tipo FROM usuarios WHERE ID = ?");
$stmtTipoUsuario->execute([$idUsuario]);
$tipoUsuario = $stmtTipoUsuario->fetchColumn();

// Obtener la lista de entradas
$stmt = $dbh->query("SELECT entradas.*, categorias.NOMBRE AS NOMBRE_CATEGORIA, usuarios.NICK AS NICK_USUARIO
                    FROM entradas
                    LEFT JOIN categorias ON entradas.CATEGORIA_ID = categorias.ID
                    LEFT JOIN usuarios ON entradas.USUARIO_ID = usuarios.ID
                    ORDER BY entradas.FECHA DESC");
$entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Entradas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Listado de Entradas</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Contenido</th>
                    <th>Categoría</th>
                    <th>Autor</th>
                    <th>Fecha de Creación</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entradas as $entrada) { ?>
                    <tr>
                        <td><?php echo $entrada['TITULO']; ?></td>
                        <td><?php echo $entrada['DESCRIPCION']; ?></td>
                        <td><?php echo $entrada['NOMBRE_CATEGORIA']; ?></td>
                        <td><?php echo $entrada['NICK_USUARIO']; ?></td>
                        <td><?php echo $entrada['FECHA']; ?></td>
                        <td>
                            <?php if ($tipoUsuario == 1 || ($tipoUsuario == 0 && $idUsuario == $entrada['USUARIO_ID'])) { ?>
                                <a href="editar_entrada.php?id=<?php echo $entrada['ID']; ?>">Editar</a> |
                                <a href="eliminar_entrada.php?id=<?php echo $entrada['ID']; ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a> |
                            <?php } ?>
                            <a href="detalles_entrada.php?id=<?php echo $entrada['ID']; ?>">Detalles</a>
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
