<?php
// BARIS PALING ATAS - INCLUDE KONEKSI DULU
include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/header.php';

// Debug: Cek koneksi
if (!isset($conn)) {
    die("Koneksi database tidak tersedia. Periksa file db_connection.php");
}

// Notifikasi
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<h2>Daftar Produk Pakan Ternak</h2>

<div class="mb-3">
    <a href="tambah_produk.php" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Produk</a>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $query = "SELECT * FROM produk ORDER BY nama_produk ASC";
            $stmt = $conn->query($query);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode_produk']) ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['supplier'] ?? '-') ?></td>
                    <td>
                        <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
        <?php
            endwhile;
        } catch (PDOException $e) {
            echo "<tr><td colspan='8' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>