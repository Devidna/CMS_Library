<!-- borrow_book.php -->
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

// Check if book id is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect user to the borrow_list page if book id is not provided
    header("Location: borrow_list.php");
    exit();
}

// Get the book id from the URL
$book_id = $_GET['id'];

// Get the user id from the session
$user_id = $_SESSION['user_id'];

// Check if the user has already borrowed the same book
$stmt = $conn->prepare("SELECT COUNT(*) AS total_borrowed FROM borrowings WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);
$row = $stmt->fetch();

if ($row['total_borrowed'] > 0) {
    // The user has already borrowed the same book, redirect to borrow_list.php
    header("Location: borrow_list.php");
    exit();
}

// Check if the book is available for borrowing (quantity > 0)
$stmt = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$row = $stmt->fetch();

if ($row['quantity'] <= 0) {
    // The book is not available for borrowing, redirect to borrow_list.php
    header("Location: borrow_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected borrowing date and duration from the form
    $borrow_date = $_POST['borrow_date'];
    $borrow_duration = $_POST['borrow_duration'];

    // Check if the selected duration is within the maximum limit (7 days)
    if ($borrow_duration > 7) {
        // Redirect back to the borrow list with an error message
        header("Location: borrow_list.php?id=$book_id&error=invalid_duration");
        exit();
    }

    // Insert the borrowing record into the borrowings table
    $stmt = $conn->prepare("INSERT INTO borrowings (user_id, book_id, borrow_date, return_date) VALUES (?, ?, ?, DATE_ADD(?, INTERVAL ? DAY))");
    $stmt->execute([$user_id, $book_id, $borrow_date, $borrow_date, $borrow_duration]);

    // Decrease the quantity of the book in the books table
    $stmt = $conn->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?");
    $stmt->execute([$book_id]);

    // Redirect user to the borrow list page with a success message
    header("Location: borrow_list.php?borrow_success=true");
    exit();
}
?>

<!-- Add link to borrow_form.php for users to choose borrowing date and duration -->
<!DOCTYPE html>
<html>
<head>
    <title>Pinjam Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Pinjam Buku</h1>
    <div class="container">
    <!-- Form untuk memilih tanggal pinjam dan durasi -->
    <form method="post" action="borrow_book.php?id=<?php echo $_GET['id']; ?>">
        <label for="borrow_date">Tanggal Pinjam:</label>
        <input type="date" name="borrow_date" required>
        <label for="borrow_duration">Durasi Peminjaman (maksimal 7 hari):</label>
        <input type="number" name="borrow_duration" min="1" max="7" required>
        <input type="submit" value="Pinjam">
    </form>
    </div>
    <!-- Tambahkan tautan kembali ke halaman daftar buku -->
    <p><a href="borrow_list.php" class="back-link">Kembali</a></p>
</body>
</html>
