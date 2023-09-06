<?php
// category_list.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Dapatkan daftar kategori buku dari database
function getCategories()
{
    global $conn;

    $stmt = $conn->query("SELECT * FROM categories");
    return $stmt->fetchAll();
}

$categories = getCategories();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kategori Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Daftar Kategori Buku</h1>
    <div class="container">
    <!-- Tombol print dan tambah buku -->
    <?php if ($_SESSION['role'] === 'admin') { ?>
        <div class="button-group">
            <a href="export_category.php" class="print-btn">Print</a>
            <a href="category_form.php" class="book-btn">Tambah Kategori</a>
        </div>     
    <?php } ?>
    <!-- Tabel daftar kategori buku -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama Kategori Buku</th>
            <?php if ($_SESSION['role'] === 'admin') {
                    echo '<th>Aksi</th>';
            } ?>
    </tr>
    
    <?php
    foreach ($categories as $index => $category) {
        echo '<tr>';
        echo '<td>' . ($index + 1) . '</td>';
        echo '<td>' . $category['category_name'] . '</td>';
    
        // Cek apakah pengguna adalah admin
        if ($_SESSION['role'] === 'admin') {
            echo '<td>';
            echo '<a href="edit_category.php?id=' . $category['id'] . '" class="edit-btn">Edit</a>';
            echo ' | ';
            echo '<a href="delete_category.php?id=' . $category['id'] . '" class="delete-btn">Hapus</a>';
            echo '</td>';
        }
    
        echo '</tr>';
    }
    ?>
    </table>
    </div>

    <!-- Tambahkan tautan kembali ke halaman utama -->
    <p><a href="index.php" class="back-link">Kembali</a></p>
</body>
</html>
