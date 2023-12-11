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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Obtener el ID de la entrada a eliminar
    $idEntrada = $_GET['id'];

    // Realizar la eliminación en la base de datos
    $stmtEliminar = $dbh->prepare("DELETE FROM entradas WHERE ID = ?");
    $stmtEliminar->execute([$idEntrada]);
    echo "La entrada se eliminó correctamente.";
    header("Location: listado_entradas.php");
    exit();
}
?>
