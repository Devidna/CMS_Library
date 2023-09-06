<?php
// login.php
include 'auth.php';

// Set session
session_start();

// Cek apakah pengguna sudah login, jika sudah redirect ke halaman utama CMS
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Pesan kesalahan login (jika ada)
$loginError = "";

// Proses login jika pengguna mengirimkan form login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = loginUser($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $loginError = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($loginError)) { ?>
        <p><?php echo $loginError; ?></p>
    <?php } ?>
    <div class="container">
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>
    <!-- Tambahkan tautan untuk menuju ke halaman registrasi -->
    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
    </div>
</body>
</html>
