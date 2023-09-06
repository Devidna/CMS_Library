<?php
// manage_book.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai user
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Mendapatkan daftar buku dari database yang diunggah oleh user yang sedang login
function getBooks()
{
    global $conn;

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                           FROM books b
                           INNER JOIN categories c ON b.category_id = c.id
                           WHERE b.user_id = ?");
    $stmt->execute([$userId]);

    return $stmt->fetchAll();
}

$books = getBooks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Kelola Buku</h1>
        <div class="container">
            <!-- Tombol print dan tambah buku -->
            <div class="button-group">
                <a href="book_form.php" class="book-btn">Tambah Buku</a>
            </div>
        <table>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>File Buku (PDF)</th>
                <th>Cover Buku</th>
                <th>Aksi</th>
            </tr>

            <?php foreach ($books as $index => $book) { ?>
                <tr>
                    <td><?php echo ($index + 1); ?></td>
                    <td><?php echo $book['title']; ?></td>
                    <td><?php echo $book['category_name']; ?></td>
                    <td><?php echo $book['description']; ?></td>
                    <td><?php echo $book['quantity']; ?></td>
                    <td><a href="<?php echo $book['pdf_file_path']; ?>" target="_blank">Download</a></td>
                    <td><img src="<?php echo $book['cover_image_path']; ?>" alt="<?php echo $book['title']; ?> Cover" width="100"></td>
                    <td>
                        <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="edit-btn">Edit</a>
                        | 
                        <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="delete-btn">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Tambahkan tautan kembali ke halaman utama -->
    <p><a href="index.php" class="back-link">Kembali</a></p>

</body>
</html>
