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
    $idEntrada = $_POST['idEntrada'];
    $nuevoTitulo = $_POST['nuevoTitulo'];
    $nuevoContenido = $_POST['nuevoContenido'];
    $nuevaCategoria = $_POST['nuevaCategoria'];

    // Procesar la actualización de la entrada en la base de datos
    $stmt = $dbh->prepare("UPDATE entradas SET TITULO = ?, DESCRIPCION = ?, CATEGORIA_ID = ? WHERE ID = ?");
    $stmt->execute([$nuevoTitulo, $nuevoContenido, $nuevaCategoria, $idEntrada]);

    // Actualizar la imagen si se proporciona una nueva
    if ($_FILES["nuevaImagen"]["size"] > 0) {
        $imagen = $_FILES["nuevaImagen"]["name"];
        $imagen_temporal = $_FILES["nuevaImagen"]["tmp_name"];
        $ruta = "images/" . $imagen;
        move_uploaded_file($imagen_temporal, $ruta);

        // Actualizar la ruta de la imagen en la base de datos
        $stmtImagen = $dbh->prepare("UPDATE entradas SET IMAGEN = ? WHERE ID = ?");
        $stmtImagen->execute([$ruta, $idEntrada]);
    }

    // Obtener el nombre de usuario
    $stmtUsuario = $dbh->prepare("SELECT NICK FROM usuarios WHERE ID = ?");
    $stmtUsuario->execute([$_SESSION["ID"]]);
    $nombreUsuario = $stmtUsuario->fetchColumn();

    // Registrar el log con el ID de la entrada
    $stmtLog = $dbh->prepare("INSERT INTO logs (fecha, hora, usuario, tipo_operacion) VALUES (CURDATE(), CURTIME(), ?, 'Actualización de entrada (ID: $idEntrada)')");
    $stmtLog->execute([$nombreUsuario]);

    header("Location: listado_entradas.php");
} else {
    $idEntrada = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM entradas WHERE ID = ?");
    $stmt->execute([$idEntrada]);
    $entrada = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmtCategorias = $dbh->query("SELECT * FROM categorias");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
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
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="idEntrada" value="<?php echo $entrada['ID']; ?>">
            <div class="form-group">
                <label for="nuevoTitulo">Título:</label>
                <input type="text" id="nuevoTitulo" name="nuevoTitulo" class="form-control" value="<?php echo $entrada['TITULO']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nuevoContenido">Descripción:</label>
                <textarea id="nuevoContenido" name="nuevoContenido" class="form-control ckeditor" required><?php echo $entrada['DESCRIPCION']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nuevaCategoria">Categoría:</label>
                <select id="nuevaCategoria" name="nuevaCategoria" class="form-control" required>
                    <?php foreach ($categorias as $categoria) { ?>
                        <option value="<?php echo $categoria['ID']; ?>" <?php if ($categoria['ID'] == $entrada['CATEGORIA_ID']) echo 'selected'; ?>><?php echo $categoria['NOMBRE']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nuevaImagen">Nueva Imagen:</label>
                <input type="file" id="nuevaImagen" name="nuevaImagen" class="form-control-file" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>

        <a href="listado_entradas.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('nuevoContenido');
    </script>
</body>

</html>
