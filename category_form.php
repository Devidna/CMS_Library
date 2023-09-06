<?php
// category_form.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Pesan kesalahan tambah kategori (jika ada)
$addCategoryError = "";

// Proses tambah kategori jika pengguna mengirimkan form tambah kategori
if (isset($_POST['submit'])) {
    $category_name = $_POST['category_name'];

    // Validasi input
    if (empty($category_name)) {
        $addCategoryError = "Kolom kategori harus diisi.";
    } else {
        // Lakukan tambah kategori jika tidak ada kesalahan
        addCategory($category_name);

        // Redirect kembali ke halaman daftar kategori buku setelah berhasil tambah kategori
        header("Location: category_list.php");
        exit();
    }
}

// Fungsi untuk menambah kategori buku ke database
function addCategory($category_name)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->execute([$category_name]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori Buku</title>
   <!-- Sisipkan file eksternal CSS -->
   <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Tambah Kategori Buku</h1>
    <?php if (!empty($addCategoryError)) { ?>
        <p><?php echo $addCategoryError; ?></p>
    <?php } ?>
    <!-- Form untuk tambah kategori buku -->
    
    <div class="container">
    <form method="post" action="">
        <label for="category_name">Nama Kategori Buku:</label>
        <input type="text" name="category_name" required><br>
        <input type="submit" name="submit" value="Tambah">
    </form>
    </div>
    <!-- Tambahkan tautan kembali ke halaman daftar kategori buku -->
    <p><a href="category_list.php" class="back-link">Kembali</a></p>
</body>
</html>
