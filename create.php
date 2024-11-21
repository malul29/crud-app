<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
    $stmt->execute(['name' => $name, 'email' => $email]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
</head>
<body>
    <h1>Tambah User</h1>
    <form method="POST">
        <label>Nama:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <button type="submit">Simpan</button>
    </form>
    <a href="index.php">Kembali</a>
</body>
</html>
