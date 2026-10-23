<?php
require "../conexionbd/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_funcionario = $_POST["id_funcionario"] ?? "";

    if ($id_funcionario === "" ||
        !ctype_digit($id_funcionario) ||
        (int)$id_funcionario < 1) {

        $mensaje = "Seleccione un funcionario válido";

    } else {
        try {
            $sql = "UPDATE funcionario
                    SET activo = 0
                    WHERE id_funcionario = :id_funcionario
                    AND activo = 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "id_funcionario" => $id_funcionario
            ]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "Funcionario dado de baja correctamente";
            } else {
                $mensaje = "El funcionario no existe o ya está dado de baja";
            }

        } catch (PDOException $e) {
            $mensaje = "Error al dar de baja al funcionario";
        }
    }
}

$sql = "SELECT id_funcionario, nombre, email
        FROM funcionario
        WHERE activo = 1
        ORDER BY nombre";

$funcionarios = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Baja de Funcionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="container py-4" style="max-width:600px;">

    <h1 class="h4 mb-3">Eliminar un funcionario</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Funcionario:</label>

            <select class="form-select" name="id_funcionario" required>
                <option value="">-- Seleccione un funcionario --</option>

                <?php foreach ($funcionarios as $func): ?>
                    <option value="<?= $func["id_funcionario"] ?>">
                        <?= htmlspecialchars($func["nombre"], ENT_QUOTES, "UTF-8") ?>
                        (<?= htmlspecialchars($func["email"], ENT_QUOTES, "UTF-8") ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-danger w-100"
                onclick="return confirm('¿Está seguro de eliminar a este funcionario?')">
            Eliminar funcionario
        </button>
    </form>

    <a href="/proyecto/login/panel.php" class="btn btn-success w-100 mt-2">
        Volver al Inicio
    </a>

</body>
</html>
