<?php
// manage_user.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Mendapatkan daftar user dari database
function getUsers()
{
    global $conn;

    $stmt = $conn->query("SELECT id, username, role FROM users");
    return $stmt->fetchAll();
}

// Mendapatkan data user berdasarkan ID
function getUserById($userId)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Proses edit user jika form disubmit
if (isset($_POST['update_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];
    updateUserRole($userId, $newRole);
}

$users = getUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola User</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Kelola User</h1>
    <!-- Tabel daftar user -->
    <div class="container">
        <!-- Tombol print dan tambah buku -->
        <div class="button-group">
                <a href="user_form.php" class="book-btn">Tambah User</a>
        </div>
    <table>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($users as $index => $user) { ?>
            <tr>
                <td><?php echo ($index + 1); ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <?php if ($user['id'] !== $_SESSION['user_id']) { ?>
                        <!-- Hanya admin bisa mengedit dan menghapus admin lain -->
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="delete-btn">Hapus</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    </div>

    <!-- Tambahkan tautan kembali ke halaman utama -->
    <p><a href="index.php" class="back-link">Kembali</a></p>

</body>
</html>
