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

    // Realizar la eliminación en la base de datos
    $stmtEliminar = $dbh->prepare("DELETE FROM usuarios WHERE ID = ?");
    $stmtEliminar->execute([$IDUSUARIO]);
    echo "El usuario se eliminó correctamente.";
    header("Location: listado_usuarios.php");
    exit();
}
