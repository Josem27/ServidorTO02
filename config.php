<?php
  $dbHost = 'localhost';
  $dbName = 'bdblog';
  $dbUser = 'root';
  $dbPass= '';

try {
    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUser,$dbPass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexión exitosa a la base de datos";
    return $conexion;
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    return null;
}
?>