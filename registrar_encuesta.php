<?php
require "conexion.php";

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo_encuesta = trim($_POST["titulo_encuesta"] ?? "");
    $fecha_publi_encuesta = $_POST["fecha_publi_encuesta"] ?? "";
    $id_sector = $_POST["id_sector"] ?? "";
    $id_funcionario = $_POST["id_funcionario"] ?? "";

    if ($titulo_encuesta === "") {
        $mensaje = "Ingrese el título de la encuesta";
    } elseif ($fecha_publi_encuesta === "") {
        $mensaje = "Seleccione la fecha de publicación";
    } elseif ($id_sector === "") {
        $mensaje = "Seleccione un sector";
    } elseif ($id_funcionario === "") {
        $mensaje = "Seleccione un funcionario";
    } else {
        try {
            $sql = "INSERT INTO encuesta (titulo_encuesta, fecha_publi_encuesta, id_sector, id_funcionario)
                    VALUES (:titulo_encuesta, :fecha_publi_encuesta, :id_sector, :id_funcionario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "titulo_encuesta" => $titulo_encuesta,
                "fecha_publi_encuesta" => $fecha_publi_encuesta,
                "id_sector" => $id_sector,
                "id_funcionario" => $id_funcionario
            ]);
            $mensaje = "Encuesta registrada correctamente";

        } catch (PDOException $e) {
            $mensaje = "Error al registrar la encuesta";
        }
    }
}

$sectores = $pdo->query("SELECT id_sector, nombre_sector FROM sector")->fetchAll();
$funcionarios = $pdo->query("SELECT id_funcionario, nombre FROM funcionario WHERE activo = 1")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Registrar Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Registrar Encuesta</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <label class="form-label">Título de la encuesta:</label>
            <input class="form-control" name="titulo_encuesta" placeholder="Título" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Fecha de publicación:</label>
            <input class="form-control" name="fecha_publi_encuesta" type="date" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Sector:</label>
            <select class="form-select" name="id_sector" required>
                <option value="">-- Seleccione un sector --</option>
                <?php foreach ($sectores as $sec): ?>
                    <option value="<?= $sec["id_sector"] ?>"><?= $sec["nombre_sector"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Funcionario responsable:</label>
            <select class="form-select" name="id_funcionario" required>
                <option value="">-- Seleccione un funcionario --</option>
                <?php foreach ($funcionarios as $func): ?>
                    <option value="<?= $func["id_funcionario"] ?>"><?= $func["nombre"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Registrar Encuesta</button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>