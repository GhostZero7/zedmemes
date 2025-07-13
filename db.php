<?php
$host = '127.0.0.1';
$db   = 'zedmemes';
$user = 'root';
$pass = ''; // Default for XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=3307;dbname=$db;charset=$charset"; // port=3307 for XAMPP

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
   // echo " Connected to database successfully!";
} catch (\PDOException $e) {
    exit(" Database connection failed: " . $e->getMessage());
}
?>
