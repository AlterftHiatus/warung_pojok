<?php
session_start();
include 'koneksi.php'; // pastikan ini membuat variabel $conn

// Validasi: cek apakah keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    die("Keranjang kosong, tidak bisa checkout.");
}

$nama_pelanggan = $_POST['nama_pelanggan'] ?? 'Pelanggan';
$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'tunai';
$kode_pesanan = 'POJOK' . time();
$catatan = $_POST['catatan'] ?? '';
$total = 0;

//Hitung total pesanan
foreach ($_SESSION['keranjang'] as $id_menu => $jumlah) {
    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id_menu");
    $menu = mysqli_fetch_assoc($query);
    if (!$menu) continue;

    $subtotal = $menu['harga'] * $jumlah;
    $total += $subtotal;
}

// Simpan ke tabel orders
mysqli_query($conn, "INSERT INTO orders (kode_pesanan, nama_pelanggan, total, metode_pembayaran, catatan) 
    VALUES ('$kode_pesanan', '$nama_pelanggan', $total, '$metode_pembayaran', '$catatan')");

// Ambil ID order terakhir
$order_id = mysqli_insert_id($conn);

// Proses detail pesanan
foreach ($_SESSION['keranjang'] as $id_menu => $jumlah) {
    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id_menu");
    $menu = mysqli_fetch_assoc($query);
    if (!$menu) continue;

    // Periksa stok cukup
    if ($menu['stok'] < $jumlah) {
        die("Stok tidak cukup untuk menu: " . htmlspecialchars($menu['nama_menu']));
    }

    // Kurangi stok
    $stok_baru = $menu['stok'] - $jumlah;
    $update_stok = mysqli_query($conn, "UPDATE menu SET stok = $stok_baru WHERE id = $id_menu");

    if (!$update_stok) {
        die("Gagal mengurangi stok: " . mysqli_error($conn));
    }

    // Simpan ke order_items
    $harga = $menu['harga'];
    $subtotal = $harga * $jumlah;

    mysqli_query($conn, "INSERT INTO order_items (order_id, menu_id, nama_menu, harga, quantity, subtotal)
        VALUES ($order_id, $id_menu, '{$menu['nama_menu']}', $harga, $jumlah, $subtotal)");
}

// Kosongkan keranjang setelah checkout
unset($_SESSION['keranjang']);

// Tampilkan konfirmasi
echo "✅ Pesanan berhasil!<br>";
echo "Kode Pesanan: <strong>$kode_pesanan</strong><br>";
echo "<a href='index.php'>Kembali ke menu</a>";
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - POJOK Restaurant</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff6b6b;
            --primary-dark: #ff5252;
            --secondary: #4ecdc4;
            --dark: #292f36;
            --light: #f7fff7;
            --gray: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .checkout-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .checkout-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .checkout-header h2 {
            font-weight: 600;
        }
        
        .checkout-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            width: 100%;
        }
        
        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .order-summary {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .order-summary h3 {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
            color: var(--primary);
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .order-total {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--primary);
            margin-top: 1rem;
            padding-top: 0.5rem;
            border-top: 1px solid #eee;
        }
        
        .success-message {
            text-align: center;
            padding: 2rem;
        }
        
        .success-icon {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .checkout-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkout-card">
            <div class="checkout-header">
                <h2>Checkout Pesanan</h2>
            </div>
            
            <div class="checkout-body">
                <?php if(isset($kode_pesanan)): ?>
                    <!-- Success Message -->
                    <div class="success-message">
                        <div class="success-icon">✓</div>
                        <h3>Pesanan Berhasil!</h3>
                        <p>Terima kasih telah memesan di POJOK Restaurant.</p>
                        <p><strong>Kode Pesanan:</strong> <?= $kode_pesanan ?></p>
                        <p><strong>Total:</strong> Rp <?= number_format($total, 0, ',', '.') ?></p>
                        <?php if(!empty($catatan)): ?>
                            <p><strong>Catatan:</strong><br><?= nl2br(htmlspecialchars($catatan)) ?></p>
                        <?php endif; ?>
                        <a href="index.php" class="back-link">Kembali ke Menu Utama</a>
                    </div>
                <?php else: ?>
                
                    <div class="order-summary">
                        <h3>Ringkasan Pesanan</h3>
                        <?php 
                        $total = 0;
                        foreach($_SESSION['keranjang'] as $id_menu => $jumlah): 
                            $query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id_menu");
                            $menu = mysqli_fetch_assoc($query);
                            if(!$menu) continue;
                            $subtotal = $menu['harga'] * $jumlah;
                            $total += $subtotal;
                        ?>
                            <div class="order-item">
                                <span><?= htmlspecialchars($menu['nama_menu']) ?> (<?= $jumlah ?>x)</span>
                                <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="order-total">
                            <span>Total</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    
                    
                    <form method="post" action="checkout.php">
                        <div class="form-group">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan (opsional)</label>
                            <textarea id="catatan" name="catatan" placeholder="Contoh: jangan pedas, antar ke meja 3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Konfirmasi Pesanan</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>