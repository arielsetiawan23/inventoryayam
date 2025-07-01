<?php
// init.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start session hanya jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
require __DIR__ . '/includes/db_connection.php';

// Fungsi helper dasar
function redirectIfNotLoggedIn(): void
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function isAdmin(): bool
{
    return ($_SESSION['role'] ?? '') === 'admin';
}
