<?php
// export.php

// Sertakan file auth.php dan db_connection.php
require_once 'auth.php';
require_once 'db_connection.php';
require_once 'vendor/autoload.php'; // Sertakan file autoload dari Composer

// Dapatkan daftar buku dari database
function getAllBooks(){
    
    global $conn;

    // Tampilkan semua buku dengan nama user didepannya
    $stmt = $conn->query("SELECT CONCAT(u.username, ' - ', b.title) AS book_title, u.username, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path
                         FROM books b
                         JOIN categories c ON b.category_id = c.id
                         JOIN users u ON b.user_id = u.id");

    return $stmt->fetchAll();
}

// Fungsi untuk mengekspor daftar buku menjadi PDF
function exportToPDF()
{
    // Dapatkan semua buku dari database
    $books = getAllBooks();

    // Buat dokumen PDF baru
    $mpdf = new \Mpdf\Mpdf();

    // Sisipkan CSS untuk mengatur tampilan tabel
    $stylesheet = file_get_contents('style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Tambahkan konten ke dalam dokumen PDF
    $mpdf->WriteHTML('<h1>Daftar Buku</h1>');
    $mpdf->WriteHTML('<table>');
    $mpdf->WriteHTML('<tr><th>No</th><th>Judul Buku</th><th>Kategori</th><th>Deskripsi</th><th>Jumlah</th><th>File Buku (PDF)</th><th>Cover Buku</th><th>Pengunggah</th></tr>');
    foreach ($books as $index => $book) {
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<td>' . ($index + 1) . '</td>');
        $mpdf->WriteHTML('<td>' . $book['title'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['category_name'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['description'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['quantity'] . '</td>');
         // Check if pdf_file_path exists in the book data
         if (!empty($book['pdf_file_path'])) {
            $mpdf->WriteHTML('<td><a href="' . $book['pdf_file_path'] . '">Download</a></td>');
        } else {
            $mpdf->WriteHTML('<td></td>');
        }

        // Check if cover_image_path exists in the book data
        if (!empty($book['cover_image_path'])) {
            $mpdf->WriteHTML('<td><img src="' . $book['cover_image_path'] . '" alt="' . $book['title'] . ' Cover" width="100"></td>');
        } else {
            $mpdf->WriteHTML('<td></td>');
        }

        $mpdf->WriteHTML('<td>' . $book['username'] . '</td>');
        $mpdf->WriteHTML('</tr>');
    }
    $mpdf->WriteHTML('</table>');

    // Keluarkan dokumen PDF
    $mpdf->Output('daftar_buku.pdf', 'D');
}

// Panggil fungsi exportToPDF
exportToPDF();
?>
