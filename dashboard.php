<?php
// Tambahkan ini di baris paling atas
include 'includes/db_connection.php';
include 'includes/header.php';

// Sekarang $conn sudah tersedia
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="bg-light">
    <div class="container">
        <h2>Dashboard</h2>
<p>Selamat datang, <?= $_SESSION['nama_lengkap'] ?>!</p>

<div class="row mt-4">
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <?php
                $stmt = $conn->query("SELECT COUNT(*) FROM produk");
                $total_produk = $stmt->fetchColumn();
                ?>
                <h2 class="card-text"><?= $total_produk ?></h2>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card bg-success text-white">
        <div class="card-body">
            <h5 class="card-title">Stok Tersedia</h5>
            <?php
            $stmt = $conn->query("SELECT SUM(stok) FROM produk");
            $total_stok = $stmt->fetchColumn();
            ?>
            <h2 class="card-text"><?= $total_stok ?></h2>
        </div>
    </div>
</div>
<div class="col-md-4 mb-4">
    <div class="card bg-info text-white">
        <div class="card-body">
            <h5 class="card-title">Total Transaksi</h5>
            <?php
            $stmt = $conn->query("SELECT COUNT(*) FROM transaksi");
            $total_transaksi = $stmt->fetchColumn();
            ?>
            <h2 class="card-text"><?= $total_transaksi ?></h2>
        </div>
    </div>
</div>
</div>

<div class="card mt-4 bg-warning">
    <div class="card-header">
        <h5>Produk Stok Rendah</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM produk WHERE stok < 10 ORDER BY stok ASC LIMIT 5";
                $stmt = $conn->query($query);
                while ($row = $stmt->fetch()):
                ?>
                    <tr>
                        <td><?= $row['kode_produk'] ?></td>
                        <td><?= $row['nama_produk'] ?></td>
                        <td class="<?= $row['stok'] < 5 ? 'text-danger fw-bold' : 'text-warning' ?>">
                            <?= $row['stok'] ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>