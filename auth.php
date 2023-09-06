<?php
// auth.php
include 'db_connection.php';

function registerUser($username, $password, $role)
{
    global $conn;

    // Lakukan hashing password sebelum disimpan di database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, $role]);
}

function loginUser($username, $password)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return null;
    }
}

function checkExistingUser($username)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    return $user ? true : false;
}

function updateRole($user_id, $new_role)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);
}

