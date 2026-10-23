<?php
require "../conexionbd/conexion.php";

$busquedaTitulo = trim($_GET["busqueda_titulo"] ?? "");
$busquedaSector = trim($_GET["busqueda_sector"] ?? "");

// Búsqueda de Documentos Públicos (Sin mostrar datos de funcionarios)
$documentos = [];
try {
    $sqlDocs = "SELECT d.id_documento, d.titulo_documento, d.fecha_publi_documento 
                FROM documento d 
                WHERE 1=1";
    $paramsDocs = [];

    if ($busquedaTitulo !== "") {
        $sqlDocs .= " AND d.titulo_documento LIKE :titulo";
        $paramsDocs["titulo"] = "%" . $busquedaTitulo . "%";
    }

    $sqlDocs .= " ORDER BY d.fecha_publi_documento DESC LIMIT 10";
    $stmtDocs = $pdo->prepare($sqlDocs);
    $stmtDocs->execute($paramsDocs);
    $documentos = $stmtDocs->fetchAll();
} catch (PDOException $e) {
    $documentos = [];
}

// Búsqueda de Encuestas Públicas (Filtrado opcional por sector)
$encuestas = [];
try {
    $sqlEnc = "SELECT e.id_encuesta, e.titulo_encuesta, e.fecha_publi_encuesta, s.nombre_sector 
               FROM encuesta e 
               LEFT JOIN sector s ON e.id_sector = s.id_sector 
               WHERE 1=1";
    $paramsEnc = [];

    if ($busquedaSector !== "") {
        $sqlEnc .= " AND s.id_sector = :sector";
        $paramsEnc["sector"] = $busquedaSector;
    }

    $sqlEnc .= " ORDER BY e.fecha_publi_encuesta DESC LIMIT 10";
    $stmtEnc = $pdo->prepare($sqlEnc);
    $stmtEnc->execute($paramsEnc);
    $encuestas = $stmtEnc->fetchAll();
} catch (PDOException $e) {
    $encuestas = [];
}

// Obtener sectores para el filtro del portal
$sectores = [];
try {
    $stmtSec = $pdo->query("SELECT id_sector, nombre_sector FROM sector ORDER BY nombre_sector ASC");
    $sectores = $stmtSec->fetchAll();
} catch (PDOException $e) {
    $sectores = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hospital de Clínicas - Portal Informativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #0f766e;
            --primary-hover: #115e59;
            --bg-general: #eef2f6;
            --text-dark: #1f2937;
            --border-color: #cbd5e1;
        }

        body {
            background-color: var(--bg-general);
            color: var(--text-dark);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: var(--primary-teal);
        }

        .btn-teal {
            background-color: var(--primary-teal);
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            transition: background-color 0.2s ease;
        }

        .btn-teal:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
        }

        .card-custom {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .footer-login {
            background-color: #0f172a;
            color: #ffffff;
            margin-top: auto;
            padding: 24px 0;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }
    </style>
</head>
<body>

    <!-- Encabezado Institucional -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container py-1">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="index.php">
                🏥 Hospital de Clínicas
            </a>
            <span class="navbar-text text-light opacity-75 d-none d-md-inline">
                Portal de Información para Usuarios y Pacientes
            </span>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container py-5">
        
        <!-- Banner de Bienvenida -->
        <div class="card-custom p-4 p-md-5 mb-5 text-center bg-white">
            <h1 class="h2 fw-bold mb-3" style="color: var(--primary-teal);">Bienvenido al Portal de Salud</h1>
            <p class="lead text-secondary mx-auto mb-0" style="max-width: 750px;">
                Acceda fácilmente a guías de salud, instructivos de preparación de estudios y encuestas de satisfacción institucional.
            </p>
        </div>

        <div class="row g-4">
            
            <!-- Sección: Documentos e Instructivos -->
            <div class="col-lg-6">
                <div class="card-custom p-4 h-100">
                    <h2 class="h4 fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--primary-teal);">
                        📄 Instructivos e Información
                    </h2>
                    <p class="text-muted small mb-4">Consulte folletos informativos e indicaciones para consultas médicas y estudios.</p>

                    <!-- Formulario de Búsqueda -->
                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="busqueda_titulo" 
                                   placeholder="Ej. Preparación imagenología, Indicaciones..." 
                                   value="<?= htmlspecialchars($busquedaTitulo) ?>">
                            <button class="btn btn-teal" type="submit">Buscar</button>
                        </div>
                    </form>

                    <!-- Lista de Documentos Público -->
                    <?php if (!empty($documentos)): ?>
                        <div class="list-group list-group-flush border-top">
                            <?php foreach ($documentos as $doc): ?>
                                <div class="list-group-item bg-transparent py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($doc["titulo_documento"]) ?></h6>
                                        <small class="badge bg-light text-dark border"><?= htmlspecialchars($doc["fecha_publi_documento"]) ?></small>
                                    </div>
                                    <small class="text-muted">Documento informativo oficial del servicio asistencial.</small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-light border text-center py-4 text-muted">
                            No se encontraron instructivos o información pública disponible.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sección: Encuestas de Satisfacción -->
            <div class="col-lg-6">
                <div class="card-custom p-4 h-100">
                    <h2 class="h4 fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--primary-teal);">
                        📊 Encuestas de Atención
                    </h2>
                    <p class="text-muted small mb-4">Conozca y participe en las encuestas para mejorar la calidad asistencial del hospital.</p>

                    <!-- Filtro por Servicio/Sector -->
                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <select class="form-select" name="busqueda_sector">
                                <option value="">-- Todos los servicios del hospital --</option>
                                <?php foreach ($sectores as $sec): ?>
                                    <option value="<?= $sec["id_sector"] ?>" <?= $busquedaSector == $sec["id_sector"] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sec["nombre_sector"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-teal" type="submit">Filtrar</button>
                        </div>
                    </form>

                    <!-- Lista de Encuestas Pública -->
                    <?php if (!empty($encuestas)): ?>
                        <div class="list-group list-group-flush border-top">
                            <?php foreach ($encuestas as $enc): ?>
                                <div class="list-group-item bg-transparent py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($enc["titulo_encuesta"]) ?></h6>
                                        <small class="badge bg-light text-dark border"><?= htmlspecialchars($enc["fecha_publi_encuesta"]) ?></small>
                                    </div>
                                    <small class="text-muted">
                                        Servicio: <strong><?= htmlspecialchars($enc["nombre_sector"] ?? "General") ?></strong>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-light border text-center py-4 text-muted">
                            No hay encuestas disponibles en este momento.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <!-- Pie de página con Acceso para Funcionarios/Administración -->
    <footer class="footer-login">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <p class="fw-semibold mb-0">Hospital de Clínicas — Sistema de Gestión</p>
                <small class="text-secondary">Uso exclusivo para la comunidad asistencial e informativa.</small>
            </div>
            <div>
                <a href="/proyecto/login/login.php" class="btn btn-outline-light px-4 py-2" style="border-radius: 8px;">
                    🔒 Acceso Funcionarios
                </a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
