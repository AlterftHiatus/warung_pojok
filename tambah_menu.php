<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $tersedia = isset($_POST['tersedia']) ? 1 : 0;
    $stok = $_POST['stok'];

    $stmt = $conn->prepare("INSERT INTO menu (nama_menu, harga, kategori, deskripsi, tersedia, stok) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssii", $nama_menu, $harga, $kategori, $deskripsi, $tersedia, $stok);
    $stmt->execute();

    header("Location: kelola_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tambah Menu Baru</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nama_menu" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" name="nama_menu" id="nama_menu" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga" id="harga" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-control" name="kategori" id="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" name="stok" id="stok" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="tersedia" id="tersedia" checked>
                <label class="form-check-label" for="tersedia">Tersedia</label>
            </div>
            <button type="submit" class="btn btn-success">Tambah Menu</button>
            <a href="kelola_menu.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>