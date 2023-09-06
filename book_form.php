<?php
// book_form.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai user
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Pesan kesalahan tambah buku (jika ada)
$addBookError = "";

// Proses tambah buku jika pengguna mengirimkan form tambah buku
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // Validasi input
    if (empty($title) || empty($category_id) || empty($description) || empty($quantity)) {
        $addBookError = "Semua kolom harus diisi.";
    } else {
        // Upload file buku (PDF)
        $pdf_file_path = uploadFile('pdf_file', 'pdf');

        // Upload file cover buku (jpeg/jpg/png)
        $cover_image_path = uploadFile('cover_image', 'image');

        if (!$pdf_file_path || !$cover_image_path) {
            $addBookError = "Terjadi kesalahan saat mengunggah file.";
        } else {
            // Lakukan tambah buku jika tidak ada kesalahan
            $user_id = $_SESSION['user_id'];
            addBook($title, $category_id, $description, $quantity, $pdf_file_path, $cover_image_path, $user_id);

            // Redirect kembali ke halaman daftar buku setelah berhasil tambah buku
            header("Location: book_list.php");
            exit();
        }
    }
}

// Fungsi untuk mengupload file
function uploadFile($inputName, $fileType)
{
    // Periksa apakah file berhasil diupload dan tidak ada kesalahan
    if ($_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ($fileType === 'pdf') ? ['pdf'] : ['jpeg', 'jpg', 'png'];
        $uploadedFileName = $_FILES[$inputName]['name'];
        $uploadedFileExtension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);

        // Periksa apakah ekstensi file sesuai dengan yang diizinkan
        if (in_array($uploadedFileExtension, $allowedExtensions)) {
            $uploadDir = 'uploads/';
            $newFileName = uniqid() . '.' . $uploadedFileExtension;
            $destination = $uploadDir . $newFileName;

            // Pindahkan file dari temporary folder ke folder tujuan
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $destination)) {
                return $destination;
            }
        }
    }

    return false; // Kembalikan false jika terjadi kesalahan
}

// Fungsi untuk menambah buku ke database
function addBook($title, $category_id, $description, $quantity, $pdf_file_path, $cover_image_path, $user_id)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO books (title, category_id, description, quantity, pdf_file_path, cover_image_path, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category_id, $description, $quantity, $pdf_file_path, $cover_image_path, $user_id]);
}

// Dapatkan daftar kategori buku dari database
function getCategories()
{
    global $conn;

    $stmt = $conn->query("SELECT id, category_name FROM categories");
    return $stmt->fetchAll();
}

$categories = getCategories();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Tambah Buku</h1>
    <?php if (!empty($addBookError)) { ?>
        <p><?php echo $addBookError; ?></p>
    <?php } ?>
    <!-- Form untuk tambah buku -->
    
    <div class="container">
    <form method="post" action="" enctype="multipart/form-data">
        <label for="title">Judul Buku:</label>
        <input type="text" name="title" required><br>
        <label for="category_id">Kategori Buku:</label>
        <select name="category_id" required>
            <!-- Ambil data kategori buku dari database dan tampilkan sebagai opsi -->
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php } ?>
        </select><br>
        
        <label for="description">Deskripsi:</label>
        <textarea name="description" required></textarea><br>
        
        <label for="quantity">Jumlah:</label>
        <input type="number" name="quantity" required><br>
        
        <label for="pdf_file">File Buku (PDF):</label>
        <input type="file" name="pdf_file" accept=".pdf" required><br>
        
        <label for="cover_image">Cover Buku (jpeg/jpg/png):</label>
        <input type="file" name="cover_image" accept="image/jpeg, image/jpg, image/png" required><br>
        
        <input type="submit" name="submit" value="Tambah">
    </form>
     </div>
    <!-- Tambahkan tautan kembali ke halaman daftar buku -->
    <p><a href="manage_book.php" class="back-link">Kembali</a></p>

</body>
</html>
