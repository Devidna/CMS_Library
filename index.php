<?php
// index.php
include 'auth.php';

// Set session
session_start();

// Cek apakah pengguna sudah login, jika belum redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil informasi pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>CMS Digital Perpustakaan berbasis Website</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <div class="container">
        <h1>CMS Digital Perpustakaan berbasis Website</h1>
        <div class="welcome-message">
            Selamat datang, <?php echo $username; ?>!
    </div>
    <ul class="menu-list">
            <?php if ($role === 'admin') { ?>
                <!-- Tampilkan konten khusus untuk admin -->
                <li class="menu-item"><a href="book_list.php" class="menu-link">Daftar Buku</a></li>
                <li class="menu-item"><a href="category_list.php" class="menu-link">Daftar Kategori Buku</a></li>
                <li class="menu-item"><a href="borrowed_book.php" class="menu-link">Buku yang Dipinjam</a></li>
                <li class="menu-item"><a href="manage_user.php" class="menu-link">Kelola User</a></li>
                <li class="menu-item"><a href="logout.php" class="menu-link logout-link">Logout</a></li>
            <?php } else { ?>
                <!-- Tampilkan konten khusus untuk user -->
                <li class="menu-item"><a href="book_list.php" class="menu-link">Daftar Semua Buku</a></li>
                <li class="menu-item"><a href="category_list.php" class="menu-link">Daftar Kategori Buku</a></li>
                <li class="menu-item"><a href="manage_book.php" class="menu-link">Kelola Buku</a></li>
                <li class="menu-item"><a href="borrow_list.php" class="menu-link">Pinjam Buku</a></li>
                <li class="menu-item"><a href="logout.php" class="menu-link logout-link">Logout</a></li>
            <?php } ?>
    </ul>
    </div>
</body>
</html>

