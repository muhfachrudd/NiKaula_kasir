<?php
include 'config.php';

echo "<h2>Testing Kasir System</h2>";

// Clear existing cart data for fresh test
mysqli_query($conn, "DELETE FROM keranjang");

// Add sample transaction to cart
$test_items = [
    ['CF001', 'Espresso', 18000, 2],
    ['CF002', 'Americano', 20000, 1],
    ['SN001', 'Croissant Plain', 15000, 2]
];

echo "<h3>Adding test items to cart...</h3>";
foreach ($test_items as $item) {
    $subtotal = $item[2] * $item[3];
    $tgl = date("j F Y");
    
    $sql = "INSERT INTO keranjang (kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdids', $item[0], $item[1], $item[2], $item[3], $subtotal, $tgl);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p>✓ Added: {$item[1]} (Qty: {$item[3]}, Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . ")</p>";
    } else {
        echo "<p>✗ Failed to add: {$item[1]}</p>";
    }
}

// Calculate total
$total_query = mysqli_query($conn, "SELECT SUM(subtotal) as total FROM keranjang");
$total_data = mysqli_fetch_assoc($total_query);
$total = $total_data['total'];

echo "<h3>Transaction Summary</h3>";
echo "<p><strong>Total Amount: Rp " . number_format($total, 0, ',', '.') . "</strong></p>";

// Simulate payment
$bayar = $total + 5000; // Customer pays 5000 more
$kembalian = $bayar - $total;
$no_transaksi = "NK" . date("dmYHis");

echo "<p>Customer pays: Rp " . number_format($bayar, 0, ',', '.') . "</p>";
echo "<p>Change: Rp " . number_format($kembalian, 0, ',', '.') . "</p>";

// Update cart with payment info
$update_sql = "UPDATE keranjang SET no_transaksi = ?, bayar = ?, kembalian = ?";
$update_stmt = mysqli_prepare($conn, $update_sql);
mysqli_stmt_bind_param($update_stmt, 'sdd', $no_transaksi, $bayar, $kembalian);

if (mysqli_stmt_execute($update_stmt)) {
    echo "<p>✓ Payment processed successfully!</p>";
    
    // Move to laporanku (complete transaction)
    $report_sql = "INSERT INTO laporanku (no_transaksi, bayar, kembalian, id_Cart, kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input)
                   SELECT no_transaksi, bayar, kembalian, id_cart, kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input
                   FROM keranjang";
    
    if (mysqli_query($conn, $report_sql)) {
        echo "<p>✓ Transaction moved to reports successfully!</p>";
        
        // Clear cart
        mysqli_query($conn, "DELETE FROM keranjang");
        echo "<p>✓ Cart cleared for next transaction!</p>";
    } else {
        echo "<p>✗ Failed to move transaction to reports: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✗ Failed to process payment: " . mysqli_error($conn) . "</p>";
}

echo "<hr>";
echo "<h3>Current Status:</h3>";

// Check cart
$cart_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM keranjang");
$cart_data = mysqli_fetch_assoc($cart_count);
echo "<p>Items in cart: " . $cart_data['total'] . "</p>";

// Check reports
$report_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporanku");
$report_data = mysqli_fetch_assoc($report_count);
echo "<p>Total transactions in reports: " . $report_data['total'] . "</p>";

// Check menu items
$menu_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang");
$menu_data = mysqli_fetch_assoc($menu_count);
echo "<p>Menu items available: " . $menu_data['total'] . "</p>";

mysqli_close($conn);
?>

<br><br>
<a href="index.php" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Back to Kasir</a>
<a href="laporan.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">View Reports →</a>
