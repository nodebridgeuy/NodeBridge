<?php
require "conexion.php";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_encuesta = $_POST["id_encuesta"] ?? "";
    $titulo_encuesta = trim($_POST["titulo_encuesta"] ?? "");
    $fecha_publi_encuesta = $_POST["fecha_publi_encuesta"] ?? "";
    $id_sector = $_POST["id_sector"] ?? "";
    $id_funcionario = $_POST["id_funcionario"] ?? "";

    if ($id_encuesta !== "" && $titulo_encuesta !== "" && $fecha_publi_encuesta !== "" && $id_sector !== "" && $id_funcionario !== "") {
        try {
            $sql = "UPDATE encuesta 
                    SET titulo_encuesta = :titulo_encuesta, 
                        fecha_publi_encuesta = :fecha_publi_encuesta, 
                        id_sector = :id_sector, 
                        id_funcionario = :id_funcionario 
                    WHERE id_encuesta = :id_encuesta";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "titulo_encuesta" => $titulo_encuesta,
                "fecha_publi_encuesta" => $fecha_publi_encuesta,
                "id_sector" => $id_sector,
                "id_funcionario" => $id_funcionario,
                "id_encuesta" => $id_encuesta
            ]);
            
            $mensaje = "Encuesta actualizada correctamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar la encuesta.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos.";
    }
}

$encuestas = $pdo->query("SELECT id_encuesta, titulo_encuesta FROM encuesta")->fetchAll();
$sectores = $pdo->query("SELECT id_sector, nombre_sector FROM sector")->fetchAll();
$funcionarios = $pdo->query("SELECT id_funcionario, nombre FROM funcionario WHERE activo = 1")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Editar Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Editar Encuesta</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Seleccione la encuesta a editar:</label>
            <select class="form-select" name="id_encuesta" required>
                <option value="">-- Seleccione --</option>
                <?php foreach ($encuestas as $enc): ?>
                    <option value="<?= $enc["id_encuesta"] ?>"><?= $enc["titulo_encuesta"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nuevo Título:</label>
            <input type="text" class="form-control" name="titulo_encuesta" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva Fecha de Publicación:</label>
            <input type="date" class="form-control" name="fecha_publi_encuesta" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Sector:</label>
            <select class="form-select" name="id_sector" required>
                <option value="">-- Seleccione sector --</option>
                <?php foreach ($sectores as $sec): ?>
                    <option value="<?= $sec["id_sector"] ?>"><?= $sec["nombre_sector"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Funcionario Responsable:</label>
            <select class="form-select mb-3" name="id_funcionario" required>
                <option value="">-- Seleccione funcionario --</option>
                <?php foreach ($funcionarios as $func): ?>
                    <option value="<?= $func["id_funcionario"] ?>"><?= $func["nombre"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Guardar Cambios</button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>