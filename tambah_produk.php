<?php include 'includes/header.php'; ?>

<h2>Tambah Produk Pakan Ternak</h2>

<form action="proses_produk.php?action=add" method="post" class="mt-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Produk</label>
            <input type="text" class="form-control" name="kode_produk" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" name="nama_produk" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori</label>
            <select class="form-select" name="kategori" required>
                <option value="Pakan Ayam">Pakan Ayam</option>
                <option value="Pakan Sapi">Pakan Sapi</option>
                <option value="Pakan Ikan">Pakan Ikan</option>
                <option value="Vitamin">Vitamin</option>
                <option value="Suplemen">Suplemen</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" class="form-control" name="satuan" value="Karung" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Stok Awal</label>
            <input type="number" class="form-control" name="stok" value="0" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Harga Beli</label>
            <input type="number" class="form-control" name="harga_beli" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Harga Jual</label>
            <input type="number" class="form-control" name="harga_jual" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Supplier</label>
        <input type="text" class="form-control" name="supplier">
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="produk.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include 'includes/footer.php'; ?>