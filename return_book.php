<!-- return_book.php -->
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
    // Redirect user to the book list page if book id is not provided
    header("Location: book_list.php");
    exit();
}

// Get the book id from the URL
$book_id = $_GET['id'];

// Get the user id from the session
$user_id = $_SESSION['user_id'];

// Check if the user has borrowed the book
$stmt = $conn->prepare("SELECT COUNT(*) AS total_borrowed FROM borrowings WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);
$row = $stmt->fetch();

if ($row['total_borrowed'] === 0) {
    // The user has not borrowed the book, redirect to book_list.php
    header("Location: book_list.php");
    exit();
}

// Increase the quantity of the book in the books table
$stmt = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?");
$stmt->execute([$book_id]);

// Delete the borrowing record from the borrowings table
$stmt = $conn->prepare("DELETE FROM borrowings WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);

// Redirect user to the book list page with a success message
header("Location: book_list.php?return_success=true");
exit();
?>
