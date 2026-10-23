<?php
require "../conexionbd/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre      = trim($_POST["nombre"] ?? "");
    $contrasena  = $_POST["contrasena"] ?? "";
    $id_rol      = $_POST["id_rol"] ?? "";
    $email       = trim($_POST["email"] ?? "");

    if ($nombre === "") {
        $mensaje = "Falta el nombre";
    } elseif ($contrasena === "") {
        $mensaje = "Falta la contraseña";
    } elseif ($id_rol === "") {
        $mensaje = "Falta seleccionar el rol";
    } else {
        // Todo válido: guardamos
        try {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $sql = "INSERT INTO funcionario (nombre, contrasena, id_rol, email, activo)
                    VALUES (:nombre, :contrasena, :id_rol, :email, 1)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "nombre"     => $nombre,
                "contrasena" => $hash,
                "id_rol"     => $id_rol,
                "email"      => $email !== "" ? $email : null
            ]);

            $mensaje = "Usuario registrado correctamente";
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Registrar funcionario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 520px;">

    <h3 class="h4 mb-3"> Registrar funcionario</h3>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <p> Usuario </p>
            <input class="form-control" name="nombre" required>
        </div>

        <div class="mb-2">
            <p> Contraseña </p>
            <input class="form-control" name="contrasena" type="password" required>
        </div>

        <div class="mb-2">
            <p> Email </p>
            <input class="form-control" name="email" type="email">
        </div>

        <div class="mb-3">
            <p> Cargo </p>
            <select class="form-select" name="id_rol" required>
                <option value="">Seleccione un cargo</option>
                <option value="1">Administrativo</option>
                <option value="2">Médico</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>

    <a href="/proyecto/login/panel.php" class="btn btn-success mt-2">
        Volver al Inicio
    </a>

</body>
</html>
