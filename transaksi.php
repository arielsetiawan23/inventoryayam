<?php
require __DIR__ . '\init.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/includes/header.php';

// Fungsi untuk mendapatkan data transaksi
function getTransaksi($conn) {
    $query = "SELECT t.*, p.nama_produk, p.kode_produk, u.username 
              FROM transaksi t
              JOIN produk p ON t.produk_id = p.id
              JOIN users u ON t.user_id = u.id
              ORDER BY t.tanggal DESC, t.created_at DESC";
    return $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan produk
function getProduk($conn) {
    return $conn->query("SELECT id, kode_produk, nama_produk, stok FROM produk ORDER BY nama_produk")->fetchAll(PDO::FETCH_ASSOC);
}

// Proses tambah transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = $_POST['produk_id'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $user_id = $_SESSION['user_id'];

    try {
        $conn->beginTransaction();

        // Insert transaksi
        $stmt = $conn->prepare("INSERT INTO transaksi 
                               (produk_id, jenis, jumlah, tanggal, keterangan, user_id) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$produk_id, $jenis, $jumlah, $tanggal, $keterangan, $user_id]);

        // Update stok produk
        if ($jenis == 'masuk') {
            $updateStok = "UPDATE produk SET stok = stok + ? WHERE id = ?";
        } else {
            $updateStok = "UPDATE produk SET stok = stok - ? WHERE id = ?";
        }
        $stmt = $conn->prepare($updateStok);
        $stmt->execute([$jumlah, $produk_id]);

        $conn->commit();
        $_SESSION['success'] = "Transaksi berhasil dicatat";
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: transaksi.php");
    exit;
}

$transaksi = getTransaksi($conn);
$produk = getProduk($conn);
?>

<div class="container">
    <h2 class="my-4">Manajemen Transaksi</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Tambah Transaksi Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Produk</label>
                        <select class="form-select" name="produk_id" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($produk as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['kode_produk']) ?> - <?= htmlspecialchars($p['nama_produk']) ?>
                                (Stok: <?= $p['stok'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jenis Transaksi</label>
                        <select class="form-select" name="jenis" required>
                            <option value="masuk">Stok Masuk</option>
                            <option value="keluar">Stok Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" min="1" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan (Optional)</label>
                    <textarea class="form-control" name="keterangan" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Riwayat Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($t['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($t['kode_produk']) ?> - <?= htmlspecialchars($t['nama_produk']) ?></td>
                            <td>
                                <span class="badge bg-<?= $t['jenis'] == 'masuk' ? 'success' : 'danger' ?>">
                                    <?= $t['jenis'] == 'masuk' ? 'Masuk' : 'Keluar' ?>
                                </span>
                            </td>
                            <td><?= $t['jumlah'] ?></td>
                            <td><?= htmlspecialchars($t['keterangan']) ?></td>
                            <td><?= htmlspecialchars($t['username']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>