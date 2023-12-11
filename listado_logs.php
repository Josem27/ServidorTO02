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

// Agregar el código para generar el PDF con TCPDF
if (isset($_GET['generar_pdf'])) {
    // Incluir los archivos de TCPDF localmente
    require_once 'tcpdf/tcpdf.php';

    // Crear una instancia de TCPDF
    $pdf = new TCPDF();

    // Establecer la ubicación de las fuentes de TCPDF
    $fontPath = 'ruta/a/la/carpeta/fonts/';
    TCPDF_FONTS::addTTFfont($fontPath . 'arial.ttf', 'TrueTypeUnicode', '', 32);

    // Generar PDF con TCPDF
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 12);
    $pdf->Cell(0, 10, 'Listado de Logs', 0, 1, 'C');

    $pdf->SetFont('times', '', 10);
    $pdf->Cell(30, 10, 'Fecha', 1);
    $pdf->Cell(30, 10, 'Hora', 1);
    $pdf->Cell(40, 10, 'Usuario', 1);
    $pdf->Cell(40, 10, 'Tipo de Operación', 1);
    $pdf->Ln();

    // Obtener todos los registros de la tabla 'logs'
    $stmtRegistros = $dbh->prepare("SELECT * FROM logs ORDER BY fecha DESC");
    $stmtRegistros->execute();
    $registros = $stmtRegistros->fetchAll(PDO::FETCH_ASSOC);

    foreach ($registros as $registro) {
        $pdf->Cell(30, 10, $registro['fecha'], 1);
        $pdf->Cell(30, 10, $registro['hora'], 1);
        $pdf->Cell(40, 10, $registro['usuario'], 1);
        $pdf->Cell(40, 10, $registro['tipo_operacion'], 1);
        $pdf->Ln();
    }

    // Descargar el PDF
    $pdf->Output('Listado_Logs.pdf', 'D');
    exit(); // Agregar esta línea para detener la ejecución del resto del script
}

// Configuración de la paginación
$registrosPorPagina = 5; // Número de registros por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Obtener el total de registros en la tabla 'logs'
$stmtTotalRegistros = $dbh->query("SELECT COUNT(*) FROM logs");
$totalRegistros = $stmtTotalRegistros->fetchColumn();

// Obtener la lista de registros paginada
$stmtRegistros = $dbh->prepare("SELECT * FROM logs ORDER BY fecha DESC LIMIT $offset, $registrosPorPagina");
$stmtRegistros->execute();
$registros = $stmtRegistros->fetchAll(PDO::FETCH_ASSOC);

// Calcular el número total de páginas
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Logs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Listado de Logs</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Tipo de Operación</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro) { ?>
                    <tr>
                        <td><?php echo $registro['fecha']; ?></td>
                        <td><?php echo $registro['hora']; ?></td>
                        <td><?php echo $registro['usuario']; ?></td>
                        <td><?php echo $registro['tipo_operacion']; ?></td>
                        <td>
                            <a href="eliminar_log.php?id=<?php echo $registro['id']; ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            <a href="?generar_pdf=1" target="_blank">Generar PDF</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

        <!-- Ir a página específica -->
        <form class="form-inline float-right" action="" method="get">
            <label class="mr-2" for="irAPagina">Ir a página:</label>
            <input type="number" class="form-control mr-2" id="irAPagina" name="pagina" min="1" max="<?php echo $totalPaginas; ?>" value="<?php echo $paginaActual; ?>">
            <button type="submit" class="btn btn-primary">Ir</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Inicio</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
