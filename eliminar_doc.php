<?php
require "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_documento = $_POST["id_documento"] ?? "";

    if ($id_documento !== "") {
        try {
            $sql = "DELETE FROM documento WHERE id_documento = :id_documento";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "id_documento" => $id_documento
            ]);
            $mensaje = "Documento eliminado correctamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el documento.";
        }
    } else {
        $mensaje = "No se seleccionó ningún documento.";
    }
}

$stmtDocs = $pdo->query("SELECT d.id_documento, d.titulo_documento, f.nombre as nombre_funcionario 
                          FROM documento d 
                          LEFT JOIN funcionario f ON d.id_funcionario = f.id_funcionario");
$documentos = $stmtDocs->fetchAll();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Eliminar Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Eliminar Documento</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label class="form-label">Seleccione el documento a eliminar:</label>
        <select class="form-select mb-3" name="id_documento" required>
            <option value="">-- Seleccione un documento --</option>
            <?php foreach ($documentos as $doc): ?>
                <option value="<?= $doc["id_documento"] ?>">
                    <?= $doc["titulo_documento"] ?> (Autor: <?= $doc["nombre_funcionario"] ?? "Desconocido" ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-danger w-100 mb-2" onclick="return confirm('¿Estás seguro de eliminar este documento permanentemente?');">
            Eliminar Documento
        </button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>