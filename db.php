<?php
$host = "localhost";
$dbname = "crud_app";
$username = "root"; // Ganti sesuai username database Anda
$password = "";     // Ganti sesuai password database Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
