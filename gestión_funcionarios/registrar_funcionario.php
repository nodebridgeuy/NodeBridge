
<?php
require "conexion.php";

$mensaje = "";

$nombre = "";
$email = "";
$id_rol = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $id_rol = $_POST["id_rol"] ?? "";

    if ($nombre === "") {
        $mensaje = "Ingrese el nombre del funcionario";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingrese un correo electrónico válido";
    } elseif ($id_rol === "" || !ctype_digit($id_rol)
        || (int)$id_rol < 1) {
        $mensaje = "Seleccione un rol";
    } else {
        try {
            $sql = "INSERT INTO funcionario
                    (nombre, email, id_rol, activo)
                    VALUES (:nombre, :email, :id_rol, 1)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "nombre" => $nombre,
                "email" => $email,
                "id_rol" => $id_rol
            ]);

            $mensaje = "Funcionario registrado correctamente";
            $nombre = "";
            $email = "";
            $id_rol = "";

        } catch (PDOException $e) {
            $mensaje = "Error al registrar. Verifique los datos e intente nuevamente.";
        }
    }
}

$roles = $pdo->query(
    "SELECT id_rol, nombre_rol FROM rol ORDER BY nombre_rol"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Registrar Funcionario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="container py-4" style="max-width:520px;">

    <h1 class="h4 mb-3">Registrar Funcionario</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <label class="form-label">Nombre:</label>
            <input class="form-control" name="nombre"
                   value="<?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>"
                   required>
        </div>

        <div class="mb-2">
            <label class="form-label">Correo electrónico:</label>
            <input class="form-control" type="email" name="email"
                   value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol:</label>
            <select class="form-select" name="id_rol" required>
                <option value="">-- Seleccione un rol --</option>

                <?php foreach ($roles as $rol): ?>
                    <option value="<?= $rol["id_rol"] ?>"
                        <?= (string)$id_rol === (string)$rol["id_rol"]
                            ? "selected" : "" ?>>
                        <?= htmlspecialchars($rol["nombre_rol"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Registrar Funcionario
        </button>
    </form>

    <a href="/sigsm/" class="btn btn-secondary w-100 mt-2">
        Volver al Inicio
    </a>

</body>
</html>
