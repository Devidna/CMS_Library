<!-- delete_category.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Hapus Kategori Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Hapus Kategori Buku</h1>

<?php

// Start the session
session_start();

// Check if category ID is provided in the URL
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Query the category data from the database
    require_once 'auth.php';
    require_once 'db_connection.php';

    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();

    if ($category) {
        // Display confirmation message for category deletion
        echo "<p>Apakah Anda yakin ingin menghapus kategori '{$category['category_name']}'?</p>";
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="category_id" value="' . $category['id'] . '">';
        echo '<input type="submit" name="confirm_delete" value="Hapus">';
        echo '<a href="category_list.php" class="back-link">Batal</a>';
        echo '</form>';

        // Process form submission to delete category
        if (isset($_POST['confirm_delete'])) {
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);

            // Redirect back to category_list.php after deleting
            header("Location: category_list.php");
            exit();
        }
    } else {
        echo "Kategori tidak ditemukan.";
    }
} else {
    echo "ID Kategori tidak diberikan.";
}
?>
