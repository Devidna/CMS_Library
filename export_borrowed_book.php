<?php
// export_borrowed_books.php

// Sertakan file auth.php dan db_connection.php
require_once 'auth.php';
require_once 'db_connection.php';
require_once 'vendor/autoload.php'; // Sertakan file autoload dari Composer

// Dapatkan daftar buku yang dipinjam dari database
function getBorrowedBooks()
{
    global $conn;

    $stmt = $conn->query("SELECT u.username AS borrower_name, u_uploader.username AS uploader_name, b.id, b.title, c.category_name, b.description, b.quantity, b.pdf_file_path, b.cover_image_path, bb.borrow_date, bb.return_date
                       FROM borrowings bb
                       JOIN books b ON bb.book_id = b.id
                       JOIN categories c ON b.category_id = c.id
                       JOIN users u ON bb.user_id = u.id
                       JOIN users u_uploader ON b.user_id = u_uploader.id");
    return $stmt->fetchAll();
}

// Fungsi untuk mengekspor daftar buku yang dipinjam menjadi PDF
function exportToPDF()
{
    // Dapatkan semua buku yang dipinjam dari database
    $borrowedBooks = getBorrowedBooks();

    // Buat dokumen PDF baru
    $mpdf = new \Mpdf\Mpdf();

    // Sisipkan CSS untuk mengatur tampilan tabel
    $stylesheet = file_get_contents('style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Tambahkan konten ke dalam dokumen PDF
    $mpdf->WriteHTML('<h1>Daftar Buku yang Dipinjam</h1>');
    $mpdf->WriteHTML('<table>');
    $mpdf->WriteHTML('<tr><th>No</th><th>Pengunggah</th><th>Peminjam</th><th>Judul Buku</th><th>Kategori</th><th>Deskripsi</th><th>Jumlah</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th></tr>');
    foreach ($borrowedBooks as $index => $book) {
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<td>' . ($index + 1) . '</td>');
        $mpdf->WriteHTML('<td>' . $book['uploader_name'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['borrower_name'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['title'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['category_name'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['description'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['quantity'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['borrow_date'] . '</td>');
        $mpdf->WriteHTML('<td>' . $book['return_date'] . '</td>');
        $mpdf->WriteHTML('</tr>');
    }
    $mpdf->WriteHTML('</table>');

    // Keluarkan dokumen PDF
    $mpdf->Output('daftar_buku_dipinjam.pdf', 'D');
}

// Panggil fungsi exportToPDF
exportToPDF();
?>
