<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: kelola_menu.php");
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id");
$menu = mysqli_fetch_assoc($result);

if (!$menu) {
    echo "Data tidak ditemukan.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $tersedia = isset($_POST['tersedia']) ? 1 : 0;
    $stok = $_POST['stok'];

    $stmt = $conn->prepare("UPDATE menu SET nama_menu=?, harga=?, kategori=?, deskripsi=?, tersedia=?, stok=? WHERE id=?");
    $stmt->bind_param("sdssiii", $nama_menu, $harga, $kategori, $deskripsi, $tersedia, $stok, $id);
    $stmt->execute();

    header("Location: kelola_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Menu</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" name="nama_menu" id="nama_menu" value="<?= htmlspecialchars($menu['nama_menu']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="harga" value="<?= $menu['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" class="form-control" name="kategori" id="kategori" value="<?= htmlspecialchars($menu['kategori']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi"><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" name="stok" id="stok" value="<?= $menu['stok'] ?>" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="tersedia" id="tersedia" <?= $menu['tersedia'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="tersedia">Tersedia</label>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_menu.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
