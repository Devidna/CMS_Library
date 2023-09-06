<?php
// add_user.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Proses tambah user jika form disubmit
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi data
    if (empty($username) || empty($password) || empty($role)) {
        $error_message = "Username, password, dan role harus diisi.";
    } else {
        // Hash password sebelum menyimpan ke database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data user baru ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $role]);

        // Redirect ke halaman manage_user.php setelah berhasil menambahkan user baru
        header("Location: manage_user.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Tambah User</h1>
    <!-- Form tambah user -->
    <div class="container">
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="" disabled selected>Pilih Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <input type="submit" name="add_user" value="Tambah User">
        </form>
        <?php if (isset($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>

    <!-- Tambahkan tautan kembali ke halaman manage_user.php -->
    <p><a href="manage_user.php" class="back-link">Kembali</a></p>
</body>
</html>
