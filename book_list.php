<?php
// book_list.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Dapatkan daftar buku dari database
function getBooks(){
    global $conn;

    // Tampilkan semua buku dengan nama user didepannya
    $stmt = $conn->query("SELECT CONCAT(u.username, ' - ', b.title) AS book_title, u.username, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                         FROM books b
                         JOIN categories c ON b.category_id = c.id
                         JOIN users u ON b.user_id = u.id");

    return $stmt->fetchAll();
}

$booksPerPage = 5; // Number of books to display per page
$totalBooks = count(getBooks());
$totalPages = ceil($totalBooks / $booksPerPage);

// Get the current page number from the URL query parameter
$currentpage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentpage < 1) {
    $currentpage = 1;
} elseif ($currentpage > $totalPages) {
    $currentpage = $totalPages;
}

// Calculate the starting and ending index of books for the current page
$startIndex = ($currentpage - 1) * $booksPerPage;
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
    <!-- Tambahkan filter berdasarkan kategori buku -->
    <form method="get" action="">
        <label for="filter_category">Filter berdasarkan Kategori:</label>
        <select name="filter_category">
            <option value="">Semua Kategori</option>
            <?php
            $stmt = $conn->query("SELECT id, category_name FROM categories");
            $categories = $stmt->fetchAll();

            foreach ($categories as $category) {
                $selected = (isset($_GET['filter_category']) && $_GET['filter_category'] == $category['category_name']) ? 'selected' : '';
                echo '<option value="' . $category['category_name'] . '" ' . $selected . '>' . $category['category_name'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Filter">
    </form>

    <div class="container">
        <!-- Tombol print -->
        <?php if ($_SESSION['role'] === 'admin') { ?>
            <div class="button-group">
                <a href="export_book.php" class="print-btn">Print</a>
            </div>    
        <?php } ?>
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
                <th>Pengunggah</th>
            </tr>

        <!--  Ambil data buku dari database sesuai filter dan halaman -->
        <?php if (isset($_GET['filter_category']) && !empty($_GET['filter_category'])) {
            // Jika filter berdasarkan kategori dipilih, ambil buku sesuai kategori
            $filter_category_name = $_GET['filter_category'];
            $stmt = $conn->prepare("SELECT CONCAT(u.username, ' - ', b.title) AS book_title, u.username, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                                    FROM books b 
                                    JOIN categories c ON b.category_id = c.id
                                    JOIN users u ON b.user_id = u.id
                                    WHERE c.category_name = ?");
            $stmt->execute([$filter_category_name]);
        } else {
            // Jika tidak ada filter, ambil semua buku berdasarkan halaman
            $stmt = $conn->prepare("SELECT CONCAT(u.username, ' - ', b.title) AS book_title, u.username, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                                    FROM books b
                                    JOIN categories c ON b.category_id = c.id
                                    JOIN users u ON b.user_id = u.id
                                    LIMIT ?, ?");
            $stmt->bindValue(1, $startIndex, PDO::PARAM_INT);
            $stmt->bindValue(2, $booksPerPage, PDO::PARAM_INT);
            $stmt->execute();
        }

        $books = $stmt->fetchAll();

        // Ambil data buku dari database sesuai filter
        foreach ($books as $index => $book) {
            echo '<tr>';
            echo '<td>' . ($startIndex + $index + 1) . '</td>';
            echo '<td>' . $book['title'] . '</td>';
            echo '<td>' . $book['category_name'] . '</td>';
            echo '<td>' . $book['description'] . '</td>';
            echo '<td>' . $book['quantity'] . '</td>';
            echo '<td><a href="' . $book['pdf_file_path'] . '" target="_blank">Download</a></td>';
            echo '<td><img src="' . $book['cover_image_path'] . '" alt="' . $book['title'] . ' Cover" width="100"></td>';
            echo '<td>' . $book['username'] . '</td>';
            echo '</tr>';
        }
        ?>
        </table>

        <!-- Pagination links -->
        <?php if (!isset($_GET['filter_category']) || $_GET['filter_category'] === "") { ?>
            <div class="pagination">
                <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
                    <a href="?page=<?php echo $page; ?>" <?php if ($page === $currentpage) echo 'class="active"'; ?>><?php echo $page; ?></a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>


    <!-- Tambahkan tautan kembali ke halaman utama -->
    <p><a href="index.php" class="back-link">Kembali</a></p>

</
