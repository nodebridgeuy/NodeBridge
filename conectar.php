<?php
$host = "localhost";
$db = "sigsm";
$user = "root";  
$pass = "";  
$charset = "utf8mb4";
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "Conexión exitosa a la base sigsm";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}