<?php
// Create Missing Tables Script
include 'config.php';

echo "<h2>🔧 Creating Missing Tables</h2>";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<p>✅ Connected to database 'tb_kasir'</p>";

// Create keranjang table
echo "<h3>Creating table 'keranjang'...</h3>";
$sql_keranjang = "CREATE TABLE IF NOT EXISTS `keranjang` (
    `id_cart` int(11) NOT NULL AUTO_INCREMENT,
    `no_transaksi` varchar(50) DEFAULT NULL,
    `kode_barang` varchar(20) NOT NULL,
    `nama_barang` varchar(100) NOT NULL,
    `harga_barang` decimal(10,2) NOT NULL,
    `quantity` int(11) NOT NULL,
    `subtotal` decimal(15,2) NOT NULL,
    `bayar` decimal(15,2) DEFAULT 0.00,
    `kembalian` decimal(15,2) DEFAULT 0.00,
    `tgl_input` varchar(50) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_cart`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql_keranjang)) {
    echo "<p>✅ Table 'keranjang' created successfully</p>";
} else {
    echo "<p>❌ Error creating table 'keranjang': " . mysqli_error($conn) . "</p>";
}

// Create laporanku table
echo "<h3>Creating table 'laporanku'...</h3>";
$sql_laporan = "CREATE TABLE IF NOT EXISTS `laporanku` (
    `id_laporan` int(11) NOT NULL AUTO_INCREMENT,
    `no_transaksi` varchar(50) NOT NULL,
    `bayar` decimal(15,2) NOT NULL,
    `kembalian` decimal(15,2) NOT NULL,
    `id_Cart` int(11) NOT NULL,
    `kode_barang` varchar(20) NOT NULL,
    `nama_barang` varchar(100) NOT NULL,
    `harga_barang` decimal(10,2) NOT NULL,
    `quantity` int(11) NOT NULL,
    `subtotal` decimal(15,2) NOT NULL,
    `tgl_input` varchar(50) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_laporan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $sql_laporan)) {
    echo "<p>✅ Table 'laporanku' created successfully</p>";
} else {
    echo "<p>❌ Error creating table 'laporanku': " . mysqli_error($conn) . "</p>";
}

// Update login table structure
echo "<h3>Updating table 'login'...</h3>";
$sql_update_login = "ALTER TABLE `login` 
    ADD COLUMN IF NOT EXISTS `nama_toko` varchar(100) DEFAULT 'NiKaula Coffee Shop',
    ADD COLUMN IF NOT EXISTS `alamat` varchar(200) DEFAULT 'Jl. Coffee Street No. 123, Jakarta',
    ADD COLUMN IF NOT EXISTS `telp` varchar(20) DEFAULT '021-1234567'";

if (mysqli_query($conn, $sql_update_login)) {
    echo "<p>✅ Table 'login' updated successfully</p>";
} else {
    echo "<p>⚠️ Table 'login' might already have these columns: " . mysqli_error($conn) . "</p>";
}

// Update login data
$sql_update_data = "UPDATE `login` SET 
    `nama_toko` = 'NiKaula Coffee Shop',
    `alamat` = 'Jl. Coffee Street No. 123, Jakarta',
    `telp` = '021-1234567'
    WHERE `user` = 'admin'";

if (mysqli_query($conn, $sql_update_data)) {
    echo "<p>✅ Login data updated with coffee shop info</p>";
} else {
    echo "<p>⚠️ Could not update login data: " . mysqli_error($conn) . "</p>";
}

// Verify tables exist
echo "<h3>🔍 Verifying Tables...</h3>";
$tables_to_check = ['barang', 'keranjang', 'login', 'laporanku'];

foreach ($tables_to_check as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p>✅ Table '$table': <span style='color: green;'>EXISTS</span></p>";
        
        // Show record count
        $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM `$table`");
        if ($count_result) {
            $count = mysqli_fetch_assoc($count_result);
            echo "<p>&nbsp;&nbsp;&nbsp;📊 Records: {$count['total']}</p>";
        }
    } else {
        echo "<p>❌ Table '$table': <span style='color: red;'>MISSING</span></p>";
    }
}

mysqli_close($conn);

echo "<hr>";
echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4 style='color: #155724; margin: 0;'>🎉 Tables Created Successfully!</h4>";
echo "<p style='color: #155724; margin: 5px 0 0 0;'>The kasir application should now work without errors.</p>";
echo "</div>";

echo "<div style='text-align: center; margin: 20px 0;'>";
echo "<a href='test_database.php' style='background: #17a2b8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; margin: 5px;'>🧪 Test Database</a>";
echo "<a href='login.php' style='background: #6f4e37; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; margin: 5px;'>🔐 Go to Login</a>";
echo "<a href='index.php' style='background: #8b4513; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; margin: 5px;'>🏪 Try Kasir</a>";
echo "</div>";
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

p {
    margin: 5px 0;
}
</style>
