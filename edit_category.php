<!-- edit_category.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Edit Kategori Buku</h1>
    
    <div class="container">
    <?php
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
            // Display the category form with pre-filled data for editing
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="category_id" value="' . $category['id'] . '">';
            echo '<label for="category_name">Nama Kategori Buku:</label>';
            echo '<input type="text" name="category_name" value="' . $category['category_name'] . '" required><br>';
            echo '<input type="submit" name="update_category" value="Update">';
            echo '</form>';

            // Process form submission to update category data
            if (isset($_POST['update_category'])) {
                $new_category_name = $_POST['category_name'];

                $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE id = ?");
                $stmt->execute([$new_category_name, $category_id]);

                // Redirect back to category_list.php after updating
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
    </div>

    <!-- Tambahkan tautan kembali ke halaman daftar kategori buku -->
    <p><a href="category_list.php" class="back-link">Kembali</a></p>
</body>
</html>
