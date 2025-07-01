<?php
require __DIR__ . '/init.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID produk dari URL
$id = $_GET['id'] ?? 0;

// Ambil data produk dari database
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

// Jika produk tidak ditemukan
if (!$produk) {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header("Location: produk.php");
    exit;
}

// Proses form edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'nama_produk' => $_POST['nama_produk'],
        'kategori' => $_POST['kategori'],
        'stok' => $_POST['stok'],
        'harga_beli' => $_POST['harga_beli'],
        'harga_jual' => $_POST['harga_jual'],
        'supplier' => $_POST['supplier']
    ];

    try {
        $stmt = $conn->prepare("UPDATE produk SET 
                              nama_produk = :nama_produk,
                              kategori = :kategori,
                              stok = :stok,
                              harga_beli = :harga_beli,
                              harga_jual = :harga_jual,
                              supplier = :supplier
                              WHERE id = :id");
        $stmt->execute($data);

        $_SESSION['success'] = "Produk berhasil diupdate";
        header("Location: produk.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal update produk: " . $e->getMessage();
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square"></i> Edit Produk</h2>
        <a href="produk.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Produk</label>
                        <input type="text" class="form-control" value="<?= $produk['kode_produk'] ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk"
                            value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="kategori" required>
                            <option value="Pakan Ayam" <?= $produk['kategori'] == 'Pakan Ayam' ? 'selected' : '' ?>>Pakan Ayam</option>
                            <option value="Pakan Sapi" <?= $produk['kategori'] == 'Pakan Sapi' ? 'selected' : '' ?>>Pakan Sapi</option>
                            <option value="Vitamin" <?= $produk['kategori'] == 'Vitamin' ? 'selected' : '' ?>>Vitamin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok"
                            value="<?= $produk['stok'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" name="harga_beli"
                            value="<?= $produk['harga_beli'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" name="harga_jual"
                            value="<?= $produk['harga_jual'] ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" name="supplier"
                            value="<?= htmlspecialchars($produk['supplier'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>