<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int) $_POST['order_id'];
    $status = $_POST['status'];

    // Update status pesanan
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    // Jika status berubah menjadi 'selesai', kurangi stok
    if ($status === 'selesai') {
        $items = $conn->query("SELECT menu_id, quantity FROM order_items WHERE order_id = $order_id");
        while ($item = $items->fetch_assoc()) {
            $menu_id = (int)$item['menu_id'];
            $jumlah = (int)$item['quantity'];

            // $conn->query("UPDATE menu SET tersedia = GREATEST(tersedia - $jumlah, 0) WHERE id = $menu_id");
            // $conn->query("UPDATE menu SET stok = GREATEST(stok - $jumlah, 0) WHERE id = $menu_id");

            // $stmt_update = $conn->prepare("UPDATE menu SET stok = GREATEST(stok - ?, 0) WHERE id = ?");
            // $stmt_update->bind_param("ii", $jumlah, $menu_id);
            // $stmt_update->execute();
        }
    }
}

header("Location: kelola_pesanan_admin.php");
exit;
