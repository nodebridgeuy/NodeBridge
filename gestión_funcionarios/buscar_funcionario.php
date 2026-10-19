
<?php
require "conexion.php";

$email = "";
$funcionarios = [];
$busqueda_realizada = false;
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $busqueda_realizada = true;

    if ($email === "") {
        $mensaje = "Ingrese un correo electrónico para buscar";
    } else {
        $sql = "SELECT f.id_funcionario, f.nombre, f.email,
                       r.nombre_rol
                FROM funcionario f
                INNER JOIN rol r ON f.id_rol = r.id_rol
                WHERE f.email LIKE :email
                AND f.activo = 1
                ORDER BY f.nombre";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => "%" . $email . "%"]);

        $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($funcionarios) === 0) {
            $mensaje = "No se encontraron funcionarios activos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Buscar Funcionario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="container py-4">

    <h1 class="h4 mb-3">Buscar Funcionario</h1>

    <form method="POST" class="mb-4">
        <div class="mb-2">
            <label class="form-label">Correo electrónico:</label>
            <input class="form-control" type="text" name="email"
                   value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Buscar
        </button>
    </form>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <?php if ($busqueda_realizada && count($funcionarios) > 0): ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($funcionarios as $func): ?>
                        <tr>
                            <td><?= $func["id_funcionario"] ?></td>
                            <td><?= htmlspecialchars($func["nombre"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($func["email"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($func["nombre_rol"], ENT_QUOTES, "UTF-8") ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="/sigsm/" class="btn btn-secondary mt-2">
        Volver al Inicio
    </a>

</body>
</html>
