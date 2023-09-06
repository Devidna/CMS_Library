<!-- edit_book.php -->
<?php
// Start the session
session_start();

// Check if the user is logged in and is an user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: book_list.php");
    exit();
}

// Check if the book ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: book_list.php");
    exit();
}

require_once 'db_connection.php';

// Check if the book exists in the database
$book_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: book_list.php");
    exit();
}

// Handle form submission for updating book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // Perform the update query
    $stmt = $conn->prepare("UPDATE books SET title = ?, category_id = ?, description = ?, quantity = ? WHERE id = ?");
    $stmt->execute([$title, $category_id, $description, $quantity, $book_id]);

    // Redirect back to book_list.php
    header("Location: book_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Edit Buku</h1>
    
    <div class="container">
    <form method="post" action="">
        <label for="title">Judul Buku:</label>
        <input type="text" name="title" value="<?php echo $book['title']; ?>" required><br>

        <label for="category_id">Kategori Buku:</label>
        <select name="category_id" required>
            <!-- Ambil data kategori buku dari database dan tampilkan sebagai opsi -->
            <?php
            $stmt = $conn->query("SELECT id, category_name FROM categories");
            $categories = $stmt->fetchAll();

            foreach ($categories as $category) {
                $selected = ($category['id'] === $book['category_id']) ? 'selected' : '';
                echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['category_name'] . '</option>';
            }
            ?>
        </select><br>

        <label for="description">Deskripsi:</label>
        <textarea name="description" required><?php echo $book['description']; ?></textarea><br>

        <label for="quantity">Jumlah:</label>
        <input type="number" name="quantity" value="<?php echo $book['quantity']; ?>" required><br>

        <input type="submit" name="submit" value="Simpan">
    </form>
    </div>
    <!-- Tambahkan tautan kembali ke halaman daftar buku -->
    <p><a href="manage_book.php" class="back-link">Kembali</a></p>
</body>
</html>
