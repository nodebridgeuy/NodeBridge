<?php
require "conexion.php";

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo_documento = trim($_POST["titulo_documento"] ?? "");
    $fecha_publi_documento = $_POST["fecha_publi_documento"] ?? "";
    $id_funcionario = $_POST["id_funcionario"] ?? "";

    if ($titulo_documento === "") {
        $mensaje = "Ingrese el título del documento";
    } elseif ($fecha_publi_documento === "") {
        $mensaje = "Seleccione la fecha de publicación";
    } elseif ($id_funcionario === "") {
        $mensaje = "Seleccione un funcionario";
    } else {
        try {
            $sql = "INSERT INTO documento (titulo_documento, fecha_publi_documento, id_funcionario)
                    VALUES (:titulo_documento, :fecha_publi_documento, :id_funcionario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "titulo_documento" => $titulo_documento,
                "fecha_publi_documento" => $fecha_publi_documento,
                "id_funcionario" => $id_funcionario
            ]);
            $mensaje = "Documento registrado correctamente";

        } catch (PDOException $e) {
            $mensaje = "Error al registrar el documento";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <title>Registrar Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4" style="max-width:520px;">
    <h1 class="h4 mb-3">Registrar Documento</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <label class="form-label">Título del documento:</label>
            <input class="form-control" name="titulo_documento" placeholder="Título" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Fecha de publicación:</label>
            <input class="form-control" name="fecha_publi_documento" type="date" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Funcionario responsable:</label>
            <select class="form-select" name="id_funcionario" required>
                <option value="">-- Seleccione un funcionario --</option>
                <?php
                $stmtFunc = $pdo->query("SELECT id_funcionario, nombre FROM funcionario WHERE activo = 1");
                $funcionarios = $stmtFunc->fetchAll();
                ?>
                <?php foreach ($funcionarios as $func): ?>
                    <option value="<?= $func["id_funcionario"] ?>">
                        <?= $func["nombre"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">
            Registrar Documento
        </button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">
        Volver al Inicio
    </a>
</body>

</html>