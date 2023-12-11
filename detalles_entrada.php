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

// Verificar si se proporcionó un ID de entrada válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_entradas.php");
    exit();
}

// Obtener los detalles de la entrada específica
$idEntrada = $_GET['id'];
$stmt = $dbh->prepare("SELECT * FROM entradas WHERE ID = ?");
$stmt->execute([$idEntrada]);
$entrada = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener el nick del autor
$stmtUsuario = $dbh->prepare("SELECT NICK FROM usuarios WHERE ID = ?");
$stmtUsuario->execute([$entrada['USUARIO_ID']]);
$autor = $stmtUsuario->fetchColumn();

// Obtener el nombre de la categoría
$stmtCategoria = $dbh->prepare("SELECT NOMBRE FROM categorias WHERE ID = ?");
$stmtCategoria->execute([$entrada['CATEGORIA_ID']]);
$categoria = $stmtCategoria->fetchColumn();

// Verificar si la entrada existe
if (!$entrada) {
    header("Location: listado_entradas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Entrada</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Detalles de Entrada</h2>

        <div class="card">
            <?php if ($entrada['IMAGEN'] != null) { ?>
                <img src="<?php echo $entrada['IMAGEN']; ?>" class="card-img-top img-fluid" alt="Imagen de la entrada">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $entrada['TITULO']; ?></h5>
                <p class="card-text"><?php echo $entrada['DESCRIPCION']; ?></p>
                <p class="card-text"><strong>Autor:</strong> <?php echo $autor; ?></p>
                <p class="card-text"><strong>Categoría:</strong> <?php echo $categoria; ?></p>
                <p class="card-text"><strong>Fecha de Creación:</strong> <?php echo $entrada['FECHA']; ?></p>
            </div>
        </div>

        <a href="listado_entradas.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
