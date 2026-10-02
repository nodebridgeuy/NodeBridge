<?php
require "conexion.php";

$resultado = "";
$documentos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $busquedaTitulo = trim($_POST["busqueda_titulo"] ?? "");
    $busquedaFecha = trim($_POST["busqueda_fecha"] ?? "");

    if ($busquedaTitulo !== "" || $busquedaFecha !== "") {
        try {
            $sql = "SELECT 
                        d.id_documento,
                        d.titulo_documento,
                        d.fecha_publi_documento,
                        f.nombre as nombre_funcionario
                    FROM documento d
                    LEFT JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                    WHERE 1=1"; 

            $parametros = [];

            if ($busquedaTitulo !== "") {
                $sql .= " AND d.titulo_documento LIKE :titulo";
                $parametros["titulo"] = "%" . $busquedaTitulo . "%";
            }

            if ($busquedaFecha !== "") {
                $sql .= " AND d.fecha_publi_documento = :fecha";
                $parametros["fecha"] = $busquedaFecha;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($parametros);
            $documentos = $stmt->fetchAll();

            if (empty($documentos)) {
                $resultado = "No se encontraron documentos con los criterios especificados.";
            }
        } catch (PDOException $e) {
            $resultado = "Error al realizar la búsqueda.";
        }
    } else {
        $resultado = "Por favor, ingrese un título o seleccione una fecha para buscar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Buscar Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Buscar Documento</h1>

    <form action="" method="POST" class="mb-3">
        <div class="mb-3">
            <label class="form-label">Título del documento (opcional):</label>
            <input type="text" class="form-control" name="busqueda_titulo" placeholder="Ej. Informe, Acta...">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de publicación (opcional):</label>
            <input type="date" class="form-control" name="busqueda_fecha">
        </div>

        <button type="submit" class="btn btn-primary w-100">Buscar</button>
    </form>

    <?php if ($resultado !== ""): ?>
        <div class="alert alert-warning">
            <?= $resultado ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($documentos)): ?>
        <h5 class="text-info mb-2">Resultados Encontrados:</h5>
        <?php foreach ($documentos as $doc): ?>
            <div class="card bg-dark border-light p-3 mb-3">
                <p class="card-text mb-1"><strong>Título:</strong> <?= $doc["titulo_documento"] ?></p>
                <p class="card-text mb-1"><strong>Fecha de Publicación:</strong> <?= $doc["fecha_publi_documento"] ?></p>
                <p class="card-text mb-0"><strong>Funcionario Responsable:</strong> <?= $doc["nombre_funcionario"] ?? "Desconocido" ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>