<?php
// edit_user.php
require_once 'auth.php';
require_once 'db_connection.php';

// Set session
session_start();

// Cek apakah pengguna sudah login sebagai admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Mendapatkan data user berdasarkan ID yang diterima melalui parameter URL (GET)
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $user = getUserById($userId);
    if (!$user) {
        // Redirect jika user tidak ditemukan
        header("Location: manage_user.php");
        exit();
    }
}

// Fungsi untuk mendapatkan data user berdasarkan ID
function getUserById($userId)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Fungsi untuk mengupdate data user
function updateUser($userId, $username, $role)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $role, $userId]);
    return true;
}

// Proses update user jika form disubmit
if (isset($_POST['update_user'])) {
    $newUsername = $_POST['new_username'];
    $newRole = $_POST['new_role'];
    updateUser($userId, $newUsername, $newRole);
    // Redirect kembali ke halaman manage_user setelah berhasil update
    header("Location: manage_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <!-- Sisipkan file eksternal CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Edit User</h1>
    <div class="container">
        <form method="post" action="">
            <label for="new_username">Nama:</label>
            <input type="text" name="new_username" value="<?php echo $user['username']; ?>" required><br>
            <label for="new_role">Role:</label>
            <select name="new_role" required>
                <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select><br>
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <input type="submit" name="update_user" value="Update User">
        </form>
    </div>

    <!-- Tambahkan tautan kembali ke halaman manage_user -->
    <p><a href="manage_user.php" class="back-link">Kembali</a></p>

</body>
</html>
