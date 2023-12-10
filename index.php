<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Panel de Control</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Entradas</h5>
                        <p class="card-text">Añadir, editar, eliminar y ver detalles de las entradas.</p>
                        <a href="formulario_entradas.php" class="btn btn-primary">Añadir Entrada</a>
                        <a href="listado_entradas.php" class="btn btn-secondary">Listado de Entradas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Añadir, editar, eliminar y ver detalles de los usuarios.</p>
                        <a href="formulario_usuarios.php" class="btn btn-primary">Añadir Usuario</a>
                        <a href="listado_usuarios.php" class="btn btn-secondary">Listado de Usuarios</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Categorías</h5>
                        <p class="card-text">Añadir, editar, eliminar y ver detalles de las categorías.</p>
                        <a href="formulario_categorias.php" class="btn btn-primary">Añadir Categoría</a>
                        <a href="listado_categorias.php" class="btn btn-secondary">Listado de Categorías</a>
                    </div>
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-danger mt-3">Cerrar sesión</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
