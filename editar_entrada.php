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
    $nuevoTitulo = $_POST['nuevoTitulo'];
    $nuevoContenido = $_POST['nuevoContenido'];

    // Realizar la actualización en la base de datos
    $stmt = $dbh->prepare("UPDATE entradas SET TITULO = ?, DESCRIPCION = ? WHERE ID = ?");
    $stmt->execute([$nuevoTitulo, $nuevoContenido, $idEntrada]);

    header("Location: listado_entradas.php");
    // No es necesario exit() aquí
} else {
    // Obtener la entrada a editar
    $idEntrada = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM entradas WHERE ID = ?");
    $stmt->execute([$idEntrada]);
    $entrada = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <h2 class="text-center mb-4">Editar Entrada</h2>
        <form method="post" action="">
            <input type="hidden" name="idEntrada" value="<?php echo $entrada['ID']; ?>">
            <div class="form-group">
                <label for="nuevoTitulo">Título:</label>
                <input type="text" id="nuevoTitulo" name="nuevoTitulo" class="form-control" value="<?php echo $entrada['TITULO']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nuevoContenido">Descripción:</label>
                <textarea id="nuevoContenido" name="nuevoContenido" class="form-control" required><?php echo $entrada['DESCRIPCION']; ?></textarea>
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
