<?php
// File: users.php
require __DIR__ . '/init.php';

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek role admin
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Akses ditolak. Hanya admin yang bisa mengakses halaman ini.";
    header("Location: dashboard.php");
    exit;
}

// Fungsi database
function getUsers($conn) {
    $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Proses tambah user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $role = $_POST['role'];

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $nama_lengkap, $role]);
        $_SESSION['success'] = "User berhasil ditambahkan";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menambahkan user: " . $e->getMessage();
        error_log("Error tambah user: " . $e->getMessage());
    }
    
    header("Location: users.php");
    exit;
}

// Proses hapus user
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    
    if ($id != $_SESSION['user_id']) {
        try {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "User berhasil dihapus";
            } else {
                $_SESSION['error'] = "User tidak ditemukan";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Gagal menghapus user: " . $e->getMessage();
            error_log("Error hapus user: " . $e->getMessage());
        }
    } else {
        $_SESSION['error'] = "Tidak dapat menghapus akun sendiri";
    }
    
    header("Location: users.php");
    exit;
}

$users = getUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .navbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <!-- Menu Navigasi -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inventory System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php"><i class="bi bi-box-seam"></i> Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="users.php"><i class="bi bi-people"></i> User</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link"><i class="bi bi-person-circle"></i> <?= $_SESSION['username'] ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Manajemen User</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Tambah User Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_user" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Tambah User
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Daftar User</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                                <td><?= ucfirst($user['role']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="users.php?hapus=<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus user ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>