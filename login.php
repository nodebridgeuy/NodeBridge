<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Si ya inició sesión, lo mandamos directo al panel
if (isset($_SESSION["usuario"])) {
    header("Location: panel.php");
    exit;
}

require "../conexionbd/conexion.php";

$error = "";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"] ?? "";
    $clave   = $_POST["clave"] ?? "";

$stmt = $pdo->prepare("SELECT nombre, contrasena, id_rol FROM funcionario WHERE nombre = ?");
$stmt->execute([$usuario]);
$fila = $stmt->fetch();

if ($fila && password_verify($clave, $fila["contrasena"])) {
    session_regenerate_id(true);
    $_SESSION["usuario"] = $fila["nombre"];
    $_SESSION["id_rol"]  = $fila["id_rol"];
    $_SESSION["hora"]    = date("H:i:s");
    $_SESSION["visitas"] = 0;

        header("Location: panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> Login </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 520px;">

    <h3 class="h4 mb-3"> Login </h3>

   <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
    <div class="mb-2">
        <p> Usuario </p>
        <input class="form-control" name="usuario" required>
    </div>

    <div class="mb-2">
        <p> Contraseña </p>
        <input class="form-control" name="clave" type="password" required>
    </div>

<a href="/proyecto/app/index.php" class="btn btn-warning w-100 mt-2">Atrás</a>
             <p> </p>       

    <button type="submit" class="btn btn-primary w-100"> Iniciar sesión </button>
</form>

</body>
</html>
