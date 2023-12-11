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
    // Obtener el ID del usuario a eliminar
    $IDUSUARIO = $_GET['id'];

    // Obtener el nombre de usuario
    $stmtUsuario = $dbh->prepare("SELECT NICK FROM usuarios WHERE ID = ?");
    $stmtUsuario->execute([$_SESSION["ID"]]);
    $nombreUsuario = $stmtUsuario->fetchColumn();

    // Realizar la eliminación en la base de datos
    $stmtEliminar = $dbh->prepare("DELETE FROM usuarios WHERE ID = ?");
    $stmtEliminar->execute([$IDUSUARIO]);

    // Registrar el log con el ID del usuario eliminado
    $stmtLog = $dbh->prepare("INSERT INTO logs (fecha, hora, usuario, tipo_operacion) VALUES (CURDATE(), CURTIME(), ?, 'Eliminación de usuario (ID: $IDUSUARIO)')");
    $stmtLog->execute([$nombreUsuario]);

    echo "El usuario se eliminó correctamente.";
    header("Location: listado_usuarios.php");
    exit();
}
?>
