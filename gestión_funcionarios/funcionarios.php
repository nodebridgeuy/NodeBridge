
<?php
require "conexion.php";

$sql = "SELECT f.id_funcionario, f.nombre, f.email,
               r.nombre_rol
        FROM funcionario f
        INNER JOIN rol r ON f.id_rol = r.id_rol
        WHERE f.activo = 1
        ORDER BY f.nombre";

$stmt = $pdo->query($sql);
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <title>Listar Funcionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="container py-4">

    <h1 class="h4 mb-3">Funcionarios Activos</h1>

    <div class="table-responsive">
        <table class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($funcionarios as $func): ?>
                    <tr>
                        <td><?= $func["id_funcionario"] ?></td>

                        <td>
                            <?= htmlspecialchars($func["nombre"], ENT_QUOTES, "UTF-8") ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($func["email"], ENT_QUOTES, "UTF-8") ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($func["nombre_rol"], ENT_QUOTES, "UTF-8") ?>
                        </td>

                        <td>
                            <a class="btn btn-sm btn-warning"
                               href="editar_funcionario.php?id_funcionario=<?= $func["id_funcionario"] ?>">
                                Editar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($funcionarios) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            No hay funcionarios activos registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="/sigsm/" class="btn btn-secondary">
        Volver al Inicio
    </a>

</body>
</html>
