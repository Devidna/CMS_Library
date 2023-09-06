<?php
// export_category.php

// Sertakan file auth.php dan db_connection.php
require_once 'auth.php';
require_once 'db_connection.php';
require_once 'vendor/autoload.php'; // Sertakan file autoload dari Composer

// Tentukan fungsi getAllCategories
function getAllCategories()
{
    global $conn;

    $stmt = $conn->query("SELECT * FROM categories");
    return $stmt->fetchAll();
}

// Fungsi untuk mengekspor daftar kategori buku menjadi PDF
function exportCategoryToPDF()
{
    // Dapatkan semua kategori buku dari database
    $categories = getAllCategories();

    // Buat dokumen PDF baru
    $mpdf = new \Mpdf\Mpdf();

    // Sisipkan CSS untuk mengatur tampilan tabel
    $stylesheet = file_get_contents('style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Tambahkan konten ke dalam dokumen PDF
    $mpdf->WriteHTML('<h1>Daftar Kategori Buku</h1>');
    $mpdf->WriteHTML('<table>');
    $mpdf->WriteHTML('<tr><th>No</th><th>Nama Kategori Buku</th></tr>');
    foreach ($categories as $index => $category) {
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<td>' . ($index + 1) . '</td>');
        $mpdf->WriteHTML('<td>' . $category['category_name'] . '</td>');
        $mpdf->WriteHTML('</tr>');
    }
    $mpdf->WriteHTML('</table>');

    // Keluarkan dokumen PDF
    $mpdf->Output('daftar_kategori_buku.pdf', 'D');
}

// Panggil fungsi exportCategoryToPDF
exportCategoryToPDF();
?>
