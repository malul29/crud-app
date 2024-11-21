<?php
include 'db.php';

// Ambil semua data dari tabel users
$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRUD App</title>
</head>
<body>
    <h1>CRUD Sederhana</h1>
    <a href="create.php">Tambah User</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
                <a href="update.php?id=<?= $user['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
