<?php
// Quick Database Test - NiKaula Coffee Shop
include 'config.php';

echo "<h2>🧪 Database Connection Test</h2>";

// Test database connection
if ($conn) {
    echo "<p>✅ Database connection: <strong>SUCCESS</strong></p>";
} else {
    echo "<p>❌ Database connection: <strong>FAILED</strong></p>";
    die("Connection error: " . mysqli_connect_error());
}

echo "<hr>";

// Test barang table
echo "<h3>☕ Menu Test</h3>";
$barang_test = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang");
if ($barang_test) {
    $count = mysqli_fetch_assoc($barang_test);
    echo "<p>✅ Table 'barang': <strong>{$count['total']} menu items found</strong></p>";
    
    // Show sample menu
    $sample_menu = mysqli_query($conn, "SELECT * FROM barang LIMIT 5");
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background: #6f4e37; color: white;'><th>Kode</th><th>Menu</th><th>Harga</th><th>Stok</th></tr>";
    while ($menu = mysqli_fetch_assoc($sample_menu)) {
        echo "<tr>";
        echo "<td>{$menu['id_barang']}</td>";
        echo "<td>{$menu['nama_barang']}</td>";
        echo "<td>Rp " . number_format($menu['harga_barang'], 0, ',', '.') . "</td>";
        echo "<td>{$menu['stok']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ Table 'barang': <strong>ERROR</strong> - " . mysqli_error($conn) . "</p>";
}

// Test keranjang table
echo "<h3>🛒 Cart Test</h3>";
$keranjang_test = mysqli_query($conn, "SELECT COUNT(*) as total FROM keranjang");
if ($keranjang_test) {
    $count = mysqli_fetch_assoc($keranjang_test);
    echo "<p>✅ Table 'keranjang': <strong>Ready ({$count['total']} items in cart)</strong></p>";
} else {
    echo "<p>❌ Table 'keranjang': <strong>ERROR</strong> - " . mysqli_error($conn) . "</p>";
}

// Test login table
echo "<h3>🔐 Login Test</h3>";
$login_test = mysqli_query($conn, "SELECT COUNT(*) as total FROM login");
if ($login_test) {
    $count = mysqli_fetch_assoc($login_test);
    echo "<p>✅ Table 'login': <strong>{$count['total']} users configured</strong></p>";
    
    // Show login users
    $users = mysqli_query($conn, "SELECT user, nama_toko FROM login");
    echo "<ul>";
    while ($user = mysqli_fetch_assoc($users)) {
        echo "<li><strong>{$user['user']}</strong> - {$user['nama_toko']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>❌ Table 'login': <strong>ERROR</strong> - " . mysqli_error($conn) . "</p>";
}

// Test laporanku table
echo "<h3>📊 Reports Test</h3>";
$laporan_test = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporanku");
if ($laporan_test) {
    $count = mysqli_fetch_assoc($laporan_test);
    echo "<p>✅ Table 'laporanku': <strong>Ready ({$count['total']} transactions recorded)</strong></p>";
} else {
    echo "<p>❌ Table 'laporanku': <strong>ERROR</strong> - " . mysqli_error($conn) . "</p>";
}

echo "<hr>";
echo "<h3>🎯 System Status</h3>";

// Check if all tables exist
$tables_needed = ['barang', 'keranjang', 'login', 'laporanku', 'users', 'transaksi', 'detail_transaksi'];
$tables_exist = [];

foreach ($tables_needed as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        $tables_exist[] = $table;
        echo "<p>✅ Table '$table': <span style='color: green;'>EXISTS</span></p>";
    } else {
        echo "<p>❌ Table '$table': <span style='color: red;'>MISSING</span></p>";
    }
}

if (count($tables_exist) == count($tables_needed)) {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4 style='color: #155724; margin: 0;'>🎉 ALL SYSTEMS GO!</h4>";
    echo "<p style='color: #155724; margin: 5px 0 0 0;'>Database is fully configured and ready for NiKaula Coffee Shop operations.</p>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin: 20px 0;'>";
    echo "<a href='login.php' style='background: #6f4e37; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; margin: 5px;'>🔐 Go to Login</a>";
    echo "<a href='index.php' style='background: #8b4513; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; margin: 5px;'>🏪 Go to Kasir</a>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4 style='color: #721c24; margin: 0;'>⚠️ SETUP REQUIRED</h4>";
    echo "<p style='color: #721c24; margin: 5px 0 0 0;'>Some tables are missing. Please run the database setup.</p>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin: 20px 0;'>";
    echo "<a href='setup_database.php' style='background: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px;'>🔧 Run Database Setup</a>";
    echo "</div>";
}

mysqli_close($conn);
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}

h2, h3 {
    color: #6f4e37;
}

table {
    font-size: 14px;
}

th {
    padding: 8px;
    text-align: left;
}

td {
    padding: 6px 8px;
    background: white;
}

tr:nth-child(even) td {
    background: #f8f9fa;
}
</style>
