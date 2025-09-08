<?php
session_start();
include 'koneksi.php';

// Proses tambahkan ke troli
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $jumlah = $_POST['quantity'];
    
    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $menu_id");
    $menu = mysqli_fetch_assoc($query);
    
    if ($menu) {
        $item = [
            'menu_id' => $menu['id'],
            'nama_menu' => $menu['nama_menu'],
            'harga' => $menu['harga'],
            'jumlah' => $jumlah,
            'subtotal' => $menu['harga'] * $jumlah,
            'gambar' => $menu['gambar']
        ];
        
        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }
        
        // Periksa apakah barang sudah ada di keranjang
        $item_exists = false;
        foreach ($_SESSION['keranjang'] as &$cart_item) {
            if ($cart_item['menu_id'] == $menu_id) {
                $cart_item['jumlah'] += $jumlah;
                $cart_item['subtotal'] = $cart_item['harga'] * $cart_item['jumlah'];
                $item_exists = true;
                break;
            }
        }
        
        // ambahkan item baru jika belum ada
        if (!$item_exists) {
            $_SESSION['keranjang'][] = $item;
        }
        
        // Diarahkan ke halaman keranjang
        header("Location: keranjang.php");
        exit;
    }
}

// Dapatkan item menu yang tersedia dikelompokkan berdasarkan kategori
$query = "SELECT * FROM menu WHERE tersedia = 1 ORDER BY kategori, nama_menu";
$result = mysqli_query($conn, $query);

// Atur menu berdasarkan kategori
$menusByCategory = [];
while ($menu = mysqli_fetch_assoc($result)) {
    $menusByCategory[$menu['kategori']][] = $menu;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Warung Pojok - Daftar Menu</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary: #2a4365;
        --secondary: #f6ad55;
        --dark: #1a202c;
        --light: #f7fafc;
        --success: #38a169;
        --danger: #e53e3e;
        --gray: #e2e8f0;
    }
    
    body {
        background-color: var(--light);
        color: var(--dark);
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .navbar {
        background: linear-gradient(135deg, var(--primary) 0%, #1e365a 100%);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .navbar-brand span {
        color: var(--secondary);
    }
    
    .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    .jumbotron {
        background: linear-gradient(135deg, var(--primary) 0%, #1e365a 100%);
        color: white;
        padding: 4rem 1rem;
        margin-bottom: 2rem;
        border-radius: 0;
        position: relative;
        overflow: hidden;
    }
    
    .jumbotron::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover;
        opacity: 0.15;
        z-index: 0;
    }
    
    .jumbotron .container {
        position: relative;
        z-index: 1;
    }
    
    .display-4 {
        font-weight: 700;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
    }
    
    .display-4 span {
        color: var(--secondary);
    }
    
    .lead {
        font-weight: 400;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: white;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-img-top {
        object-fit: cover;
        height: 180px;
        width: 100%;
    }
    
    .card-title {
        font-weight: 600;
        color: var(--primary);
    }
    
    .harga {
        color: var(--primary);
        font-weight: 600;
    }
    
    .btn-success {
        background-color: var(--success);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        background-color: #2f855a;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(56, 161, 105, 0.3);
    }
    
    .btn-primary {
        background-color: var(--secondary);
        border: none;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #f6a029;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(246, 173, 85, 0.3);
    }
    
    .section-title {
        color: var(--primary);
        font-weight: 700;
        margin: 2rem 0 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: var(--secondary);
    }
    
    footer {
        background-color: var(--primary);
        color: white;
        padding: 2rem 0;
        margin-top: auto;
    }
    
    .footer-links a {
        color: var(--gray);
        margin: 0 1rem;
        transition: color 0.3s ease;
    }
    
    .footer-links a:hover {
        color: var(--secondary);
        text-decoration: none;
    }
    
    .social-icons a {
        color: white;
        font-size: 1.5rem;
        margin: 0 0.5rem;
        transition: color 0.3s ease;
    }
    
    .social-icons a:hover {
        color: var(--secondary);
    }
    
    hr.divider {
        border-top: 2px solid var(--secondary);
        width: 80px;
        margin: 1.5rem auto;
        opacity: 0.7;
    }
    
    .form-control {
        border-radius: 0.375rem;
        border: 1px solid var(--gray);
    }
    
    .form-control:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 0.2rem rgba(246, 173, 85, 0.25);
    }
    </style>
</head>
<body>


    <div class="jumbotron jumbotron-fluid text-center mb-4">
    <div class="container">
        <h1 class="display-4 font-weight-bold">WARUNG <span>POJOK</span></h1>
        <hr class="my-4 bg-light" />
        <p class="lead font-weight-bold">
        Silahkan Pesan Menu Sesuai Keinginan Anda<br />
        Enjoy Your Meal
        </p>
    </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="index.php">Warung <span>Pojok</span></a>
        <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
        >
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
            <a class="nav-link" href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" href="daftar_menu.php"><i class="fas fa-utensils mr-1"></i> Menu</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="keranjang.php"><i class="fas fa-shopping-cart mr-1"></i> Keranjang</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="logout_admin.php"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>

    <main class="container my-5">
    <h3 class="text-center font-weight-bold mb-4">DAFTAR MENU</h3>

    <?php foreach ($menusByCategory as $category => $menus): ?>
        <h4 class="section-title mt-5"><?= strtoupper($category) ?></h4>
        <div class="row">
            <?php foreach ($menus as $menu): ?>
            <div class="col-md-3 mb-4">
                <div class="card border-dark h-100">
                    <?php
                    $gambar = !empty($menu['gambar']) ? $menu['gambar'] : 'default.jpg';
                    ?>
                    <img
                    src="images/Background/<?= htmlspecialchars($gambar) ?>"
                    class="card-img-top"
                    alt="<?= htmlspecialchars($menu['nama_menu']) ?>"
                    />
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-weight-bold"><?= htmlspecialchars($menu['nama_menu']) ?></h5>
                        <p class="card-text harga">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                        <?php if (!empty($menu['deskripsi'])): ?>
                            <p class="card-text small text-muted"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                        <?php endif; ?>
                        <form method="POST" action="daftar_menu.php" class="mt-auto">
                            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>" />
                            <div class="form-group mb-2">
                                <label for="jumlah_<?= $menu['id'] ?>">Jumlah:</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    id="jumlah_<?= $menu['id'] ?>"
                                    class="form-control"
                                    value="1"
                                    min="1"
                                />
                            </div>
                            <button type="submit" name="add_to_cart" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-cart-plus mr-1"></i> Beli
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    </main>

    <footer>
    <div class="container text-center">
        <p>&copy; <?= date('Y') ?> Warung Pojok. All rights reserved.</p>
    </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>