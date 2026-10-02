<?php
require "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_documento = $_POST["id_documento"] ?? "";
    $titulo_documento = trim($_POST["titulo_documento"] ?? "");
    $fecha_publi_documento = $_POST["fecha_publi_documento"] ?? "";
    $id_funcionario = $_POST["id_funcionario"] ?? "";

    if ($id_documento !== "" && $titulo_documento !== "" && $fecha_publi_documento !== "" && $id_funcionario !== "") {
        try {
            $sql = "UPDATE documento 
                    SET titulo_documento = :titulo_documento, 
                        fecha_publi_documento = :fecha_publi_documento, 
                        id_funcionario = :id_funcionario 
                    WHERE id_documento = :id_documento";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "titulo_documento" => $titulo_documento,
                "fecha_publi_documento" => $fecha_publi_documento,
                "id_funcionario" => $id_funcionario,
                "id_documento" => $id_documento
            ]);
            
            $mensaje = "Documento actualizado correctamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar el documento.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos.";
    }
}

$stmtDocs = $pdo->query("SELECT id_documento, titulo_documento, fecha_publi_documento, id_funcionario FROM documento");
$documentos = $stmtDocs->fetchAll();

$stmtFunc = $pdo->query("SELECT id_funcionario, nombre FROM funcionario WHERE activo = 1");
$funcionarios = $stmtFunc->fetchAll();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Editar Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Editar Documento</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Seleccione el documento a editar:</label>
            <select class="form-select" name="id_documento" required>
                <option value="">-- Seleccione un documento --</option>
                <?php foreach ($documentos as $doc): ?>
                    <option value="<?= $doc["id_documento"] ?>">
                        <?= $doc["titulo_documento"] ?> (<?= $doc["fecha_publi_documento"] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nuevo Título:</label>
            <input type="text" class="form-control" name="titulo_documento" placeholder="Nuevo título del documento" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva Fecha de Publicación:</label>
            <input type="date" class="form-control" name="fecha_publi_documento" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Funcionario Responsable:</label>
            <select class="form-select mb-3" name="id_funcionario" required>
                <option value="">-- Seleccione un funcionario --</option>
                <?php foreach ($funcionarios as $func): ?>
                    <option value="<?= $func["id_funcionario"] ?>">
                        <?= $func["nombre"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Guardar Cambios</button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>