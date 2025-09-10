<?php
session_start();
include 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


// Ambil semua pesanan dengan status diproses
$sql = "SELECT * FROM orders WHERE status = 'diproses' ORDER BY order_date DESC";
$orders = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Warung Pojok</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --text: #34495e;
            --border: #bdc3c7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--light);
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .order-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .order-header {
            background: var(--light);
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-weight: 600;
            color: var(--primary);
        }

        .customer-name {
            font-weight: 500;
        }

        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .status-diproses {
            background: rgba(52, 152, 219, 0.1);
            color: var(--accent);
        }

        .status-selesai {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }

        .status-dibatalkan {
            background: rgba(231, 76, 60, 0.1);
            color: var(--secondary);
        }

        .order-body {
            padding: 20px;
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 13px;
            color: var(--text);
            opacity: 0.8;
            margin-bottom: 3px;
        }

        .meta-value {
            font-weight: 500;
        }

        .price {
            color: var(--success);
            font-weight: 600;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: var(--light);
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
        }

        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .status-form {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .form-select {
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            min-width: 180px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 50px;
            color: var(--border);
            margin-bottom: 15px;
        }

        .empty-text {
            color: var(--text);
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-meta {
                grid-template-columns: 1fr;
            }

            .items-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list"></i> Kelola Pesanan
            </h1>
            <a href="admin_dashboard.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <?php if (mysqli_num_rows($orders) > 0): ?>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <?php
                $order_id = $order['id'];
                $status_class = 'status-' . $order['status'];
                ?>

                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id"><?= $order['kode_pesanan'] ?></span>
                            <span> • </span>
                            <span class="customer-name"><?= $order['nama_pelanggan'] ?></span>
                        </div>
                        <span class="order-status <?= $status_class ?>"><?= $order['status'] ?></span>
                    </div>

                    <div class="order-body">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Nomor Meja</span>
                                <span class="meta-value"><?= htmlspecialchars($order['nomor_meja'] ?? '-') ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total Pesanan</span>
                                <span class="meta-value price">Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Metode Pembayaran</span>
                                <span class="meta-value"><?= ucfirst($order['metode_pembayaran']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Pesanan</span>
                                <span class="meta-value"><?= date('d M Y H:i', strtotime($order['order_date'])) ?></span>
                            </div>
                        </div>

                        <h5 style="margin-bottom: 15px; font-size: 16px;">Rincian Pesanan:</h5>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $order_id");
                                while ($item = mysqli_fetch_assoc($items)):
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <form method="post" action="update_status.php" class="status-form">
                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                            <select name="status" class="form-select">
                                <?php foreach (['diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $value == $order['status'] ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--text);">Belum ada pesanan</h3>
                <p class="empty-text">Tidak ada pesanan yang ditemukan</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>