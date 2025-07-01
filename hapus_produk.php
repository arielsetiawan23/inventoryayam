<?php
include 'includes/db_connection.php';

$id = $_GET['id'];

try {
    // First, delete related transactions
    $delete_transactions = $conn->prepare("DELETE FROM transaksi WHERE produk_id = ?");
    $delete_transactions->execute([$id]);

    // Then delete the product
    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);

    header("Location: produk.php?success=1");
    exit();
} catch (PDOException $e) {
    // If there's an error, redirect with error message
    header("Location: produk.php?error=Gagal menghapus produk: " . $e->getMessage());
    exit();
}
