<!-- borrowed_books.php -->
<?php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Redirect user to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user id from the session
$user_id = $_SESSION['user_id'];

// Get the role and username of the user
$stmt = $conn->prepare("SELECT role, username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get the list of books borrowed by the user or all users (for admin)
if ($user['role'] === 'admin') {
    // For admin, get all books borrowed by all users and the uploader information
    $stmt = $conn->query("SELECT u.username AS borrower_name, u_uploader.username AS uploader_name, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path, bb.borrow_date, bb.return_date
                       FROM borrowings bb
                       JOIN books b ON bb.book_id = b.id
                       JOIN categories c ON b.category_id = c.id
                       JOIN users u ON bb.user_id = u.id
                       JOIN users u_uploader ON b.user_id = u_uploader.id");
} else {
    // For user, get only books borrowed by the current user
    $stmt = $conn->prepare("SELECT u.username AS borrower_name, u_uploader.username AS uploader_name, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path, bb.borrow_date, bb.return_date
                       FROM books b
                       JOIN borrowings bb ON b.id = bb.book_id
                       JOIN categories c ON b.category_id = c.id
                       JOIN users u ON bb.user_id = u.id
                       JOIN users u_uploader ON b.user_id = u_uploader.id
                       WHERE bb.user_id = ?");
    $stmt->execute([$user_id]);
}

$books = $stmt->fetchAll();

$booksPerPage = 5; // Number of books to display per page
$totalBooks = count($books);
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
$booksToShow = array_slice($books, $startIndex, $booksPerPage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buku yang Dipinjam</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Buku yang Dipinjam</h1>

    <!-- Tabel daftar buku yang dipinjam -->
    <div class="container">
        <!-- Tombol print -->
        <div class="button-group">
            <?php if ($user['role'] === 'admin') { ?>
                <p><a href="export_borrowed_book.php" class="print-btn">Print</a></p>
            <?php } ?>  
        </div>
        <table>
            <tr>
                <th>No</th>
                <th>Pengunggah</th>
                <?php if ($user['role'] === 'admin') {
                    echo '<th>Peminjam</th>';
                } ?>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>File Buku (PDF)</th>
                <th>Cover Buku</th>
                <?php if ($user['role'] === 'user') {
                    echo '<th>Aksi</th>';
                } ?>
            </tr>

            <?php
            foreach ($booksToShow as $index => $book) {
                echo '<tr>';
                echo '<td>' . ($startIndex + $index + 1) . '</td>';
                echo '<td>' . $book['uploader_name'] . '</td>';
                if ($user['role'] === 'admin') {
                    echo '<td>' . $book['borrower_name'] . '</td>';
                }
                echo '<td>' . $book['title'] . '</td>';
                echo '<td>' . $book['category_name'] . '</td>';
                echo '<td>' . $book['description'] . '</td>';
                echo '<td>' . $book['quantity'] . '</td>';
                echo '<td>' . $book['borrow_date'] . '</td>';
                echo '<td>' . $book['return_date'] . '</td>';
                echo '<td><a href="' . $book['pdf_file_path'] . '" target="_blank">Download</a></td>';
                echo '<td><img src="' . $book['cover_image_path'] . '" alt="' . $book['title'] . ' Cover" width="100"></td>';
                if ($user['role'] === 'user') {
                    echo '<td><a href="return_book.php?id=' . $book['id'] . '" class="return-btn">Kembalikan</a></td>';
                }
                echo '</tr>';
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
    <?php if ($user['role'] === 'user') { ?>
        <p><a href="borrow_list.php" class="back-link">Kembali</a></p>
    <?php }?>  
    <?php if ($user['role'] === 'admin') { ?>
        <p><a href="index.php" class="back-link">Kembali</a></p>
    <?php }?>  
</body>
</html>
