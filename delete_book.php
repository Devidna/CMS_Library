<!-- delete_book.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Hapus Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Hapus Buku</h1>
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Perform the delete query
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);

        // Redirect back to book_list.php
        header("Location: book_list.php");
        exit();
    }
    ?>

    <!-- Form konfirmasi untuk menghapus buku -->
    <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku?');">
        <p>Apakah Anda yakin ingin menghapus buku "<?php echo $book['title']; ?>"?</p>
        <input type="submit" name="confirm_delete" value="Hapus">
        <a href="manage_book.php" class="back-link">Batal</a>
    </form>
</body>
</html>
