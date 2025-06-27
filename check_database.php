<?php
include 'config.php';

echo "<h2>Checking Database Status</h2>";

// Check if tables exist
$tables = ['barang', 'keranjang', 'laporanku', 'login'];
foreach ($tables as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        
        // Count records
        $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM $table");
        $count_data = mysqli_fetch_assoc($count);
        echo "<p>&nbsp;&nbsp;→ Records: " . $count_data['total'] . "</p>";
        
        if ($table == 'barang' && $count_data['total'] == 0) {
            echo "<p style='color: orange;'>⚠ Adding sample menu data...</p>";
            
            // Insert sample data
            $sample_data = [
                ['CF001', 'Espresso', 18000, 100],
                ['CF002', 'Americano', 20000, 100],
                ['CF003', 'Cappuccino', 25000, 100],
                ['CF004', 'Latte', 28000, 100],
                ['CF005', 'Macchiato', 30000, 100],
                ['SN001', 'Croissant Plain', 15000, 50],
                ['SN002', 'Chocolate Croissant', 18000, 50],
                ['FD001', 'Chicken Sandwich', 35000, 30],
                ['FD002', 'Beef Burger', 42000, 30],
                ['DS001', 'Tiramisu', 32000, 25]
            ];
            
            foreach ($sample_data as $item) {
                $sql = "INSERT INTO barang (id_barang, nama_barang, harga_barang, stok) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ssdi', $item[0], $item[1], $item[2], $item[3]);
                mysqli_stmt_execute($stmt);
            }
            echo "<p style='color: green;'>✓ Sample menu data added successfully!</p>";
        }
        
        if ($table == 'login' && $count_data['total'] == 0) {
            echo "<p style='color: orange;'>⚠ Adding login data...</p>";
            
            $sql = "INSERT INTO login (user, pass, nama_toko, alamat, telp) VALUES 
                    ('admin', 'admin123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567'),
                    ('kasir', 'kasir123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567')";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color: green;'>✓ Login data added successfully!</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
        
        // Create table if it doesn't exist
        if ($table == 'barang') {
            $sql = "CREATE TABLE barang (
                id_barang VARCHAR(20) PRIMARY KEY,
                nama_barang VARCHAR(100) NOT NULL,
                harga_barang DECIMAL(10,2) NOT NULL,
                stok INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color: green;'>✓ Table '$table' created successfully!</p>";
            }
        }
        
        if ($table == 'keranjang') {
            $sql = "CREATE TABLE keranjang (
                id_cart INT AUTO_INCREMENT PRIMARY KEY,
                no_transaksi VARCHAR(50) DEFAULT NULL,
                kode_barang VARCHAR(20) NOT NULL,
                nama_barang VARCHAR(100) NOT NULL,
                harga_barang DECIMAL(10,2) NOT NULL,
                quantity INT NOT NULL,
                subtotal DECIMAL(15,2) NOT NULL,
                bayar DECIMAL(15,2) DEFAULT 0,
                kembalian DECIMAL(15,2) DEFAULT 0,
                tgl_input VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color: green;'>✓ Table '$table' created successfully!</p>";
            }
        }
        
        if ($table == 'laporanku') {
            $sql = "CREATE TABLE laporanku (
                id_laporan INT AUTO_INCREMENT PRIMARY KEY,
                no_transaksi VARCHAR(50) NOT NULL,
                bayar DECIMAL(15,2) NOT NULL,
                kembalian DECIMAL(15,2) NOT NULL,
                id_Cart INT NOT NULL,
                kode_barang VARCHAR(20) NOT NULL,
                nama_barang VARCHAR(100) NOT NULL,
                harga_barang DECIMAL(10,2) NOT NULL,
                quantity INT NOT NULL,
                subtotal DECIMAL(15,2) NOT NULL,
                tgl_input VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color: green;'>✓ Table '$table' created successfully!</p>";
            }
        }
        
        if ($table == 'login') {
            $sql = "CREATE TABLE login (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(50) NOT NULL,
                pass VARCHAR(50) NOT NULL,
                nama_toko VARCHAR(100) DEFAULT 'NiKaula Coffee Shop',
                alamat VARCHAR(200) DEFAULT 'Jl. Coffee Street No. 123, Jakarta',
                telp VARCHAR(20) DEFAULT '021-1234567'
            )";
            
            if (mysqli_query($conn, $sql)) {
                echo "<p style='color: green;'>✓ Table '$table' created successfully!</p>";
            }
        }
    }
}

echo "<hr>";
echo "<h3>Sample Data in Barang Table:</h3>";
$barang_data = mysqli_query($conn, "SELECT * FROM barang LIMIT 10");
if (mysqli_num_rows($barang_data) > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th></tr>";
    while ($row = mysqli_fetch_assoc($barang_data)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id_barang']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
        echo "<td>Rp " . number_format($row['harga_barang'], 0, ',', '.') . "</td>";
        echo "<td>" . $row['stok'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No data in barang table</p>";
}

echo "<hr>";
echo "<h3>Current Cart Contents:</h3>";
$cart_data = mysqli_query($conn, "SELECT * FROM keranjang");
if (mysqli_num_rows($cart_data) > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Kode</th><th>Nama</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Bayar</th><th>Kembalian</th></tr>";
    while ($row = mysqli_fetch_assoc($cart_data)) {
        echo "<tr>";
        echo "<td>" . $row['id_cart'] . "</td>";
        echo "<td>" . htmlspecialchars($row['kode_barang']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
        echo "<td>Rp " . number_format($row['harga_barang'], 0, ',', '.') . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>Rp " . number_format($row['subtotal'], 0, ',', '.') . "</td>";
        echo "<td>Rp " . number_format($row['bayar'], 0, ',', '.') . "</td>";
        echo "<td>Rp " . number_format($row['kembalian'], 0, ',', '.') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Cart is empty</p>";
}

mysqli_close($conn);
?>

<br><br>
<a href="index.php" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Back to Kasir</a>
