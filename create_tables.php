<?php
// Simple Table Creator
include 'config.php';

// Direct SQL Commands
$tables = [
    "CREATE TABLE IF NOT EXISTS `keranjang` (
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
        PRIMARY KEY (`id_cart`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS `laporanku` (
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
        PRIMARY KEY (`id_laporan`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

echo "Creating tables...<br>";

foreach ($tables as $index => $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "✅ Table " . ($index + 1) . " created successfully<br>";
    } else {
        echo "❌ Error creating table " . ($index + 1) . ": " . mysqli_error($conn) . "<br>";
    }
}

// Test keranjang table
$test = mysqli_query($conn, "SELECT COUNT(*) FROM keranjang");
if ($test) {
    echo "<br>✅ Keranjang table is working!<br>";
} else {
    echo "<br>❌ Keranjang table error: " . mysqli_error($conn) . "<br>";
}

echo "<br><a href='login.php'>Go to Login</a> | <a href='index.php'>Try Kasir</a>";
?>
