<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
    $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST">
        <label>Nama:</label><br>
        <input type="text" name="name" value="<?= $user['name'] ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
        <button type="submit">Simpan</button>
    </form>
    <a href="index.php">Kembali</a>
</body>
</html>
