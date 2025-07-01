<?php
include 'includes/db_connection.php';

$action = $_GET['action'];

if ($action == 'add') {
    $data = [
        'kode_produk' => $_POST['kode_produk'],
        'nama_produk' => $_POST['nama_produk'],
        'kategori' => $_POST['kategori'],
        'stok' => $_POST['stok'],
        'satuan' => $_POST['satuan'],
        'harga_beli' => $_POST['harga_beli'],
        'harga_jual' => $_POST['harga_jual'],
        'supplier' => $_POST['supplier'],
        'deskripsi' => $_POST['deskripsi']
    ];

    $query = "INSERT INTO produk (kode_produk, nama_produk, kategori, stok, satuan, harga_beli, harga_jual, supplier, deskripsi) 
              VALUES (:kode_produk, :nama_produk, :kategori, :stok, :satuan, :harga_beli, :harga_jual, :supplier, :deskripsi)";
    $stmt = $conn->prepare($query);
    $stmt->execute($data);

    header("Location: produk.php?success=1");
} elseif ($action == 'update') {
    $data = [
        'id' => $_POST['id'],
        'kode_produk' => $_POST['kode_produk'],
        'nama_produk' => $_POST['nama_produk'],
        'kategori' => $_POST['kategori'],
        'stok' => $_POST['stok'],
        'satuan' => $_POST['satuan'],
        'harga_beli' => $_POST['harga_beli'],
        'harga_jual' => $_POST['harga_jual'],
        'supplier' => $_POST['supplier'],
        'deskripsi' => $_POST['deskripsi']
    ];

    $query = "UPDATE produk SET 
              kode_produk = :kode_produk, 
              nama_produk = :nama_produk, 
              kategori = :kategori, 
              stok = :stok, 
              satuan = :satuan, 
              harga_beli = :harga_beli, 
              harga_jual = :harga_jual, 
              supplier = :supplier, 
              deskripsi = :deskripsi 
              WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute($data);

    header("Location: produk.php?success=1");
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);

    header("Location: produk.php?success=1");
}
?>