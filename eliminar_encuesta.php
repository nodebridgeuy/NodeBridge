<?php
require "conexion.php";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_encuesta = $_POST["id_encuesta"] ?? "";

    if ($id_encuesta !== "") {
        try {
            $sql = "DELETE FROM encuesta WHERE id_encuesta = :id_encuesta";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["id_encuesta" => $id_encuesta]);
            $mensaje = "Encuesta eliminada correctamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar la encuesta.";
        }
    } else {
        $mensaje = "No se seleccionó ninguna encuesta.";
    }
}

$stmtEnc = $pdo->query("SELECT e.id_encuesta, e.titulo_encuesta, s.nombre_sector, f.nombre as nombre_funcionario 
                          FROM encuesta e 
                          LEFT JOIN sector s ON e.id_sector = s.id_sector
                          LEFT JOIN funcionario f ON e.id_funcionario = f.id_funcionario");
$encuestas = $stmtEnc->fetchAll();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Eliminar Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Eliminar Encuesta</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <label class="form-label">Seleccione la encuesta a eliminar:</label>
        <select class="form-select mb-3" name="id_encuesta" required>
            <option value="">-- Seleccione una encuesta --</option>
            <?php foreach ($encuestas as $enc): ?>
                <option value="<?= $enc["id_encuesta"] ?>">
                    <?= $enc["titulo_encuesta"] ?> (Sector: <?= $enc["nombre_sector"] ?? "N/D" ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-danger w-100 mb-2" onclick="return confirm('¿Estás seguro de eliminar esta encuesta permanentemente?');">
            Eliminar Encuesta
        </button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">Volver al Inicio</a>
</body>
</html>