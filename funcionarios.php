<?php
require "conexion.php";

$sql = "SELECT 
            f.id_funcionario,
            f.nombre,
            f.email,
            r.nombre_rol
        FROM funcionario f
        INNER JOIN rol r ON f.id_rol = r.id_rol
        WHERE f.activo = 1";

$stmt = $pdo->query($sql);
$funcionarios = $stmt->fetchAll();

foreach ($funcionarios as $f) {
    echo $f["nombre"] . " (" . $f["nombre_rol"] . ") — " . $f["email"] . "<br>";
}
?>
<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($funcionarios as $f): ?>
            <tr>
                <td><?= $f["nombre"] ?></td>
                <td><?= $f["email"] ?></td>
                <td><?= $f["nombre_rol"] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>