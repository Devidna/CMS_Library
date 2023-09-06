<?php
// functions.php
include 'db_connection.php';

function addBook($title, $category_id, $description, $quantity, $pdf_file_path, $cover_image_path, $user_id)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO books (title, category_id, description, quantity, pdf_file_path, cover_image_path, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category_id, $description, $quantity, $pdf_file_path, $cover_image_path, $user_id]);
}

// Fungsi untuk mengambil data buku berdasarkan kategori
function getBooksByCategory($category_id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengambil semua kategori buku
function getAllCategories()
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
