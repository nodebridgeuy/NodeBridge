<?php
require "conexion.php"; // o conexiones.php

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre      = trim($_POST["nombre"] ?? "");
    $contrasena  = $_POST["contrasena"] ?? "";
    $id_rol      = $_POST["id_rol"] ?? "";

    // Patrón guardián: cortamos ante cada dato inválido
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
            
            $sql = "INSERT INTO funcionario (nombre, contrasena, id_rol) 
                    VALUES (:nombre, :contrasena, :id_rol)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                "nombre"     => $nombre,
                "contrasena" => $hash,
                "id_rol"     => $id_rol
            ]);
            
            $mensaje = "Funcionario registrado correctamente";
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $mensaje = "El funcionari ya existe en la base de datos";
            } else {
                $mensaje = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Registrar Funcionario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 520px;">

    <h1 class="h4 mb-3">Registrar Funcionario</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-2">
            <input class="form-control" name="nombre" placeholder="Nombre completo" required>
        </div>
        
        <div class="mb-2">
            <input class="form-control" name="contrasena" type="password" placeholder="Contraseña" required>
        </div>

        <div class="mb-3">
            <select class="form-select" name="id_rol" required>
                <option value="">-- Selecciona un Rol --</option>
                <!-- Ajusta los valores (value="1", etc.) a los IDs que tengas registrados en tu tabla 'rol' -->
                <option value="1">Administrativo</option>
                <option value="2">Médico</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>

</body>
</html>
<?php
require "conexion.php";

$nombre = $_POST["nombre"];
$id_rol = $_POST["id_rol"];

// La contraseña se convierte en hash antes de guardarla
$hash = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

$sql = "INSERT INTO funcionario (nombre, contrasena, id_rol) 
        VALUES (:nombre, :contrasena, :id_rol)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "nombre" => $nombre,
    "contrasena" => $hash,
    "id_rol" => $id_rol
]);

echo "Funcionario registrado con id " . $pdo->lastInsertId();