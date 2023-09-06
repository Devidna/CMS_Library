<?php
// register.php
require_once 'auth.php';

// Set session
session_start();

// Cek apakah pengguna sudah login, jika sudah redirect ke halaman utama CMS
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Pesan kesalahan registrasi (jika ada)
$registrationError = "";

// Proses registrasi jika pengguna mengirimkan form registrasi
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $registrationError = "Username, password, dan konfirmasi password harus diisi.";
    } elseif ($password !== $confirmPassword) {
        $registrationError = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada di database
        $existingUser = checkExistingUser($username);

        if ($existingUser) {
            $registrationError = "Username sudah digunakan, silakan coba dengan username lain.";
        } else {
            // Lakukan registrasi jika tidak ada kesalahan
            registerUser($username, $password, 'user'); // Otomatis set role menjadi 'user'

            // Redirect ke halaman login setelah berhasil registrasi
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Registrasi</h1>
    <?php if (!empty($registrationError)) { ?>
        <p><?php echo $registrationError; ?></p>
    <?php } ?>
    <div class="container">
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="confirm_password">Konfirmasi Password:</label>
        <input type="password" name="confirm_password" required><br>
        <input type="submit" name="register" value="Daftar">
    </form>
    <!-- Tambahkan tautan kembali ke halaman login -->
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>
</body>
</html>
