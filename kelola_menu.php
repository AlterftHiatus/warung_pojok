<?php
session_start();
include 'koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM menu WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: kelola_menu.php");
        exit;
    }
}

// Ambil semua menu
$menus = mysqli_query($conn, "SELECT * FROM menu ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Warung Pojok</title>
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
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #219653;
            transform: translateY(-2px);
        }
        
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .menu-table th {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }
        
        .menu-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .menu-table tr:last-child td {
            border-bottom: none;
        }
        
        .menu-table tr:hover {
            background: rgba(0, 0, 0, 0.02);
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
        }
        
        .badge-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--secondary);
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 8px 12px;
            font-size: 13px;
        }
        
        .btn-danger {
            background: var(--secondary);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .price {
            font-weight: 500;
            color: var(--success);
        }
        
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
        }
        
        .search-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .menu-table {
                display: block;
                overflow-x: auto;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-btns {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-utensils"></i> Kelola Menu
            </h1>
            <div>
                <a href="admin_dashboard.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <a href="tambah_menu.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
            </div>
        </div>
        
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Cari menu...">
            <button class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <table class="menu-table">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <!-- <th>Stok</th> -->
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($menu = mysqli_fetch_assoc($menus)): ?>
                <tr>
                    <td><?= htmlspecialchars($menu['nama_menu']) ?></td>
                    <td class="price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($menu['kategori']) ?></td>
                    <!-- <td><?= $menu['stok'] ?></td> -->
                    <td>
                        <span class="badge <?= $menu['tersedia'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $menu['tersedia'] ? 'Tersedia' : 'Habis' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="edit_menu.php?id=<?= $menu['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="kelola_menu.php?hapus=<?= $menu['id'] ?>" 
                            onclick="return confirm('Yakin ingin menghapus menu ini?')" 
                            class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Simple search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.menu-table tbody tr');
            
            rows.forEach(row => {
                const menuName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (menuName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>