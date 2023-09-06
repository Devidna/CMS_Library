<?php
// borrow_list.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Check if the user is logged in and is an user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: book_list.php");
    exit();
}

// Fungsi untuk memeriksa apakah buku sudah dipinjam oleh user tertentu
function isBookBorrowed($bookId)
{
    global $conn;
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM borrowings WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$userId, $bookId]);
    $result = $stmt->fetchColumn();

    return $result > 0;
}

// Dapatkan daftar buku dari database
function getBooks(){
    global $conn;

        //Tampilkan hanya buku yang diunggah oleh orang lain
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT CONCAT(u.username, ' - ', b.title) AS book_title, u.username, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                               FROM books b
                               JOIN categories c ON b.category_id = c.id
                               JOIN users u ON b.user_id = u.id
                               WHERE b.user_id != ?");
        $stmt->execute([$userId]);

    return $stmt->fetchAll();
}

$booksPerPage = 5; // Number of books to display per page
$totalBooks = count(getBooks());
$totalPages = ceil($totalBooks / $booksPerPage);

// Get the current page number from the URL query parameter
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Calculate the starting and ending index of books for the current page
$startIndex = ($currentPage - 1) * $booksPerPage;
$endIndex = min($startIndex + $booksPerPage - 1, $totalBooks - 1);

// Get books for the current page
$books = array_slice(getBooks(), $startIndex, $booksPerPage);
?>

<!-- book_list.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Daftar Buku</h1>
    <div class="container">
        <!-- Tombol buku yang dipinjam -->
            <div class="button-group">
                <a href="borrowed_book.php" class="book-btn">Buku yang Dipinjam</a>
            </div>
        <!-- Tabel daftar buku -->
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
        
        <?php
        // Inisialisasi nomor urut buku
        $bookNumber = 1;

        foreach ($books as $index => $book) {
            // Jika buku belum dipinjam, tampilkan di list
            if (!isBookBorrowed($book['id'])) {
                echo '<tr>';
                echo '<td>' . ($startIndex + $bookNumber) . '</td>';
                echo '<td>' . $book['title'] . '</td>';
                echo '<td>' . $book['category_name'] . '</td>';
                echo '<td>' . $book['description'] . '</td>';
                echo '<td>' . $book['quantity'] . '</td>';
                echo '<td><a href="' . $book['pdf_file_path'] . '" target="_blank">Download</a></td>';
                echo '<td><img src="' . $book['cover_image_path'] . '" alt="' . $book['title'] . ' Cover" width="100"></td>';
                echo '<td>';
                echo '<p><a href="borrow_book.php?id=' . $book['id'] . '" class="borrow-btn">Pinjam</a></p>';
                echo '</td>';
                echo '</tr>';
        
                // Increment nomor urut buku
                $bookNumber++;
            }
        }
        ?>
        </table>

        <!-- Pagination links -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
                <a href="?page=<?php echo $page; ?>" <?php if ($page === $currentPage) echo 'class="active"'; ?>><?php echo $page; ?></a>
            <?php } ?>
        </div>
    </div>

    <!-- Tambahkan tautan kembali ke halaman utama -->
    <p><a href="index.php" class="back-link">Kembali</a></p>

</
