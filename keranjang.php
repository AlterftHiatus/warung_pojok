<?php
session_start();
$conn = new mysqli("localhost", "root", "", "warung_pojok");

// Hapus salah satu item keranjang berdasarkan indeks
if (isset($_GET['hapus'])) {
    $hapus_index = $_GET['hapus'];
    if (isset($_SESSION['keranjang'][$hapus_index])) {
        unset($_SESSION['keranjang'][$hapus_index]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reset index
    }
    header("Location: keranjang.php");
    exit;
}

// Hapus semua isi keranjang
if (isset($_GET['hapus_semua'])) {
    unset($_SESSION['keranjang']);
    header("Location: keranjang.php");
    exit;
}

// Ambil isi keranjang
$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang - Warung Pojok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #003049;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: orange;
            font-size: 1.5rem;
        }
        .table th {
            background-color: #003049;
            color: white;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #003049;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background-color: #003049;
            border-color: #003049;
        }
        .btn-primary:hover {
            background-color: #00253a;
            border-color: #00253a;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .menu-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .empty-cart {
            padding: 40px 0;
            text-align: center;
            color: #6c757d;
        }
        .empty-cart i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #dee2e6;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        #qrisContainer {
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #003049;
            box-shadow: 0 0 0 0.2rem rgba(0, 48, 73, 0.25);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <div class="navbar-nav ml-auto flex-row">
            <a href="index.php" class="nav-item nav-link mr-3">Home</a>
            <a href="daftar_menu.php" class="nav-item nav-link mr-3">Menu</a>
            <a href="keranjang.php" class="nav-item nav-link mr-3">Keranjang</a>
            <a href="logout_admin.php" class="nav-item nav-link">Logout</a>
        </div>
    </div>
</nav>


<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>
        <div class="action-buttons">
            <a href="daftar_menu.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Menu</a>
            <?php if (!empty($keranjang)): ?>
                <a href="keranjang.php?hapus_semua=1" class="btn btn-warning" onclick="return confirm('Hapus semua isi keranjang?')">
                    <i class="fas fa-trash-alt"></i> Hapus Semua
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Menu</th>
                            <th style="width: 120px;">Harga</th>
                            <th style="width: 100px;">Jumlah</th>
                            <th style="width: 140px;">Subtotal</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($keranjang)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-cart">
                                        <i class="fas fa-shopping-cart"></i>
                                        <h5>Keranjang masih kosong</h5>
                                        <p class="text-muted">Silakan tambahkan menu dari daftar menu</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($keranjang as $key => $item): ?>
                                <?php
                                    $nama = isset($item['nama_menu']) ? $item['nama_menu'] : '(Tidak ada nama)';
                                    $gambar = isset($item['gambar']) ? $item['gambar'] : null;
                                    $harga = isset($item['harga']) ? $item['harga'] : 0;
                                    $jumlah = isset($item['jumlah']) ? (int)$item['jumlah'] : 1;
                                    $subtotal = $harga * $jumlah;
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($gambar): ?>
                                            <img src="images/<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($nama) ?>" class="menu-img">
                                        <?php else: ?>
                                            <div class="menu-img bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($nama) ?></td>
                                    <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
                                    <td><?= $jumlah ?></td>
                                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    <td>
                                        <a href="keranjang.php?hapus=<?= $key ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($keranjang)): ?>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="4" class="text-right"><strong>Total</strong></td>
                                <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>


    <?php if (!empty($keranjang)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cash-register"></i> Checkout</h5>
            </div>
            <div class="card-body">
                <form action="proses_checkout.php" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_pelanggan"><i class="fas fa-user"></i> Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomor_meja"><i class="fas fa-chair"></i> Nomor Meja</label>
                            <input type="text" name="nomor_meja" class="form-control" id="nomor_meja" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pembayaran"><i class="fas fa-money-bill-wave"></i> Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-control" id="pembayaran" onchange="toggleQRIS()" required>
                            <option value="tunai">Tunai</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div class="form-group text-center mt-3" id="qrisContainer" style="display: none;">
                        <p class="mb-3"><strong>Silakan scan QRIS berikut:</strong></p>
                        <div class="border p-3 d-inline-block rounded">
                            <img src="images/qris.png" alt="QRIS" width="200">
                        </div>
                        <p class="mt-3 text-muted">Scan QR code untuk melakukan pembayaran</p>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-check-circle"></i> Proses Pesanan
                        </button>
                    </div>
                </form>

                <script>
                    function toggleQRIS() {
                        var metode = document.getElementById("pembayaran").value;
                        var qrisContainer = document.getElementById("qrisContainer");
                        if (metode === "qris") {
                            qrisContainer.style.display = "block";
                        } else {
                            qrisContainer.style.display = "none";
                        }
                    }
                </script>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>