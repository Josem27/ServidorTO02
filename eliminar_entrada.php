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

    // Obtener el nombre de usuario
    $stmtUsuario = $dbh->prepare("SELECT NICK FROM usuarios WHERE ID = ?");
    $stmtUsuario->execute([$_SESSION["ID"]]);
    $nombreUsuario = $stmtUsuario->fetchColumn();

    // Realizar la eliminación en la base de datos
    $stmtEliminar = $dbh->prepare("DELETE FROM entradas WHERE ID = ?");
    $stmtEliminar->execute([$idEntrada]);

    // Registrar el log con el ID de la entrada
    $stmtLog = $dbh->prepare("INSERT INTO logs (fecha, hora, usuario, tipo_operacion) VALUES (CURDATE(), CURTIME(), ?, 'Eliminación de entrada (ID: $idEntrada)')");
    $stmtLog->execute([$nombreUsuario]);

    echo "La entrada se eliminó correctamente.";
    header("Location: listado_entradas.php");
    exit();
}
?>
