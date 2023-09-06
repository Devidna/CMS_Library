<?php
// db_connection.php

$host = 'localhost';
$db_name = 'detik_cms';
$db_user = 'root';
$db_pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
