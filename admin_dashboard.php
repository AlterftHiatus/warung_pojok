<?php
session_start();

//  Cek role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2a4365;
            --secondary: #f6ad55;
            --dark: #1a202c;
            --light: #f7fafc;
        }
        .sidebar {
            background-color: var(--primary);
            min-height: 100vh;
            width: 280px;
        }
        .sidebar .nav-link {
            color: white;
            margin-bottom: 5px;
            border-radius: 5px;
            padding: 10px 15px;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background-color: var(--secondary);
            color: var(--dark);
            font-weight: 500;
        }
        main {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar position-fixed">
        <div class="p-3">
            <h4 class="text-white mb-4">Warung Pojok</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola_pesanan_admin.php">
                        <i class="fas fa-list-alt me-2"></i>Kelola Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola_menu.php">
                        <i class="fas fa-utensils me-2"></i>Kelola Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout_admin.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <main class="flex-grow-1">
        <div class="container-fluid py-4">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></h2>
            <hr>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Pesanan</h5>
                            <?php
                            $query = "SELECT COUNT(*) as total FROM orders";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h2><?= $row['total'] ?></h2>
                            <p class="mb-0">Total semua pesanan</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pendapatan Hari Ini</h5>
                            <?php
                            $today = date('Y-m-d');
                            $query = "SELECT SUM(total) as total FROM orders WHERE DATE(order_date) = '$today'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h2>Rp <?= number_format($row['total'] ?? 0, 0, ',', '.') ?></h2>
                            <p class="mb-0">Pendapatan tanggal <?= date('d/m/Y') ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pesanan Pending</h5>
                            <?php
                            $query = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h2><?= $row['total'] ?></h2>
                            <p class="mb-0">Pesanan belum diproses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
