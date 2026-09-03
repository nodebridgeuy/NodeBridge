<?php
$host = "localhost";
$db_name = "sigsm_db";
$username = "root";
$password = ""; // En XAMPP la contraseña de root por defecto está vacía

try {
    $conexion = new PDO("mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Descomentar solo para probar
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
