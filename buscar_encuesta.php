<?php
require "conexion.php";
$resultado = "";
$encuestas = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $busquedaTitulo = trim($_POST["busqueda_titulo"] ?? "");
    $busquedaFecha = trim($_POST["busqueda_fecha"] ?? "");

    if ($busquedaTitulo !== "" || $busquedaFecha !== "") {
        try {
            $sql = "SELECT 
                        e.id_encuesta,
                        e.titulo_encuesta,
                        e.fecha_publi_encuesta,
                        s.nombre_sector,
                        f.nombre as nombre_funcionario
                    FROM encuesta e
                    LEFT JOIN sector s ON e.id_sector = s.id_sector
                    LEFT JOIN funcionario f ON e.id_funcionario = f.id_funcionario
                    WHERE 1=1";

            $parametros = [];

            if ($busquedaTitulo !== "") {
                $sql .= " AND e.titulo_encuesta LIKE :titulo";
                $parametros["titulo"] = "%" . $busquedaTitulo . "%";
            }

            if ($busquedaFecha !== "") {
                $sql .= " AND e.fecha_publi_encuesta = :fecha";
                $parametros["fecha"] = $busquedaFecha;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($parametros);
            $encuestas = $stmt->fetchAll();

            if (empty($encuestas)) {
                $resultado = "No se encontraron encuestas con los criterios especificados.";
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
    <title>Buscar Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Buscar Encuesta</h1>

    <form method="POST" class="mb-3">
        <div class="mb-3">
            <label class="form-label">Título de la encuesta (opcional):</label>
            <input type="text" class="form-control" name="busqueda_titulo" placeholder="Ej. Satisfacción...">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de publicación (opcional):</label>
            <input type="date" class="form-control" name="busqueda_fecha">
        </div>

        <button type="submit" class="btn btn-primary w-100">Buscar</button>
    </form>

    <?php if ($resultado !== ""): ?>
        <div class="alert alert-warning"><?= $resultado ?></div>
    <?php endif; ?>

    <?php if (!empty($encuestas)): ?>
        <h5 class="text-info mb-2">Resultados Encontrados:</h5>
        <?php foreach ($encuestas as $enc): ?>
            <div class="card bg-dark border-light p-3 mb-3">
                <p class="card-text mb-1"><strong>Título:</strong> <?= $enc["titulo_encuesta"] ?></p>
                <p class="card-text mb-1"><strong>Fecha:</strong> <?= $enc["fecha_publi_encuesta"] ?></p>
                <p class="card-text mb-1"><strong>Sector:</strong> <?= $enc["nombre_sector"] ?? "Desconocido" ?></p>
                <p class="card-text mb-0"><strong>Funcionario:</strong> <?= $enc["nombre_funcionario"] ?? "Desconocido" ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>