<?php
// delete_user.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Mendapatkan data user berdasarkan ID yang diterima melalui parameter URL (GET)
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $user = getUserById($userId);
    if (!$user) {
        // Redirect jika user tidak ditemukan
        header("Location: manage_user.php");
        exit();
    }
}

// Fungsi untuk mendapatkan data user berdasarkan ID
function getUserById($userId)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Proses delete user jika form disubmit
if (isset($_POST['delete_user'])) {
    deleteUserById($userId);
    // Redirect kembali ke halaman manage_user setelah berhasil delete
    header("Location: manage_user.php");
    exit();
}

// Fungsi untuk menghapus user berdasarkan ID
function deleteUserById($userId)
{
    global $conn;

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Delete User</h1>
    <div class="container">
        <p>Anda yakin ingin menghapus user dengan username: <?php echo $user['username']; ?>?</p>
        <form method="post" action="">
            <input type="submit" name="delete_user" value="Hapus">
            <a href="manage_user.php" class="back-link">Batal</a>
        </form>
    </div>

</body>
</html>
