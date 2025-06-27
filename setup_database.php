<?php
// Database Setup Script
echo "<h2>Setup Database NiKaula Kasir</h2>";

$host = "localhost";
$username = "root";
$password = "";

// Connect to MySQL server (without specifying database)
$conn = mysqli_connect($host, $username, $password);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

echo "<p>✓ Connected to MySQL server</p>";

// Create database
$sql_create_db = "CREATE DATABASE IF NOT EXISTS tb_kasir";
if (mysqli_query($conn, $sql_create_db)) {
    echo "<p>✓ Database 'tb_kasir' created successfully</p>";
} else {
    echo "<p>❌ Error creating database: " . mysqli_error($conn) . "</p>";
}

// Select database
mysqli_select_db($conn, "tb_kasir");

// Create table barang
$sql_barang = "CREATE TABLE IF NOT EXISTS barang (
    id_barang VARCHAR(20) PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    harga_barang DECIMAL(10,2) NOT NULL,
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_barang)) {
    echo "<p>✓ Table 'barang' created successfully</p>";
} else {
    echo "<p>❌ Error creating table barang: " . mysqli_error($conn) . "</p>";
}

// Create table transaksi
$sql_transaksi = "CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    tgl_transaksi DATE NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    bayar DECIMAL(15,2) NOT NULL,
    kembalian DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_transaksi)) {
    echo "<p>✓ Table 'transaksi' created successfully</p>";
} else {
    echo "<p>❌ Error creating table transaksi: " . mysqli_error($conn) . "</p>";
}

// Create table detail_transaksi
$sql_detail = "CREATE TABLE IF NOT EXISTS detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    id_barang VARCHAR(20),
    nama_barang VARCHAR(100),
    harga_barang DECIMAL(10,2),
    qty INT,
    subtotal DECIMAL(15,2),
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql_detail)) {
    echo "<p>✓ Table 'detail_transaksi' created successfully</p>";
} else {
    echo "<p>❌ Error creating table detail_transaksi: " . mysqli_error($conn) . "</p>";
}

// Create table users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    level ENUM('admin', 'kasir') DEFAULT 'kasir',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_users)) {
    echo "<p>✓ Table 'users' created successfully</p>";
} else {
    echo "<p>❌ Error creating table users: " . mysqli_error($conn) . "</p>";
}

// Create table login (for compatibility with login.php)
$sql_login = "CREATE TABLE IF NOT EXISTS login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    pass VARCHAR(50) NOT NULL,
    nama_toko VARCHAR(100) DEFAULT 'NiKaula Coffee Shop',
    alamat VARCHAR(200) DEFAULT 'Jl. Coffee Street No. 123, Jakarta',
    telp VARCHAR(20) DEFAULT '021-1234567'
)";

if (mysqli_query($conn, $sql_login)) {
    echo "<p>✓ Table 'login' created successfully</p>";
} else {
    echo "<p>❌ Error creating table login: " . mysqli_error($conn) . "</p>";
}

// Create table keranjang (shopping cart)
$sql_keranjang = "CREATE TABLE IF NOT EXISTS keranjang (
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

if (mysqli_query($conn, $sql_keranjang)) {
    echo "<p>✓ Table 'keranjang' created successfully</p>";
} else {
    echo "<p>❌ Error creating table keranjang: " . mysqli_error($conn) . "</p>";
}

// Create table laporanku (reports)
$sql_laporan = "CREATE TABLE IF NOT EXISTS laporanku (
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

if (mysqli_query($conn, $sql_laporan)) {
    echo "<p>✓ Table 'laporanku' created successfully</p>";
} else {
    echo "<p>❌ Error creating table laporanku: " . mysqli_error($conn) . "</p>";
}

// Insert coffee shop menu data
$sample_barang = [
    // MINUMAN KOPI  
    ['CF001', 'Espresso', 18000, 100],
    ['CF002', 'Americano', 20000, 100],
    ['CF003', 'Cappuccino', 25000, 100],
    ['CF004', 'Latte', 28000, 100],
    ['CF005', 'Macchiato', 30000, 100],
    ['CF006', 'Mocha', 32000, 100],
    ['CF007', 'Flat White', 26000, 100],
    ['CF008', 'Cold Brew', 24000, 100],
    
    // MINUMAN NON KOPI
    ['DR001', 'Hot Chocolate', 22000, 100],
    ['DR002', 'Green Tea Latte', 24000, 100],
    ['DR003', 'Chai Tea Latte', 26000, 100],
    ['DR004', 'Lemon Tea', 15000, 100],
    ['DR005', 'Ice Tea', 12000, 100],
    ['DR006', 'Fresh Orange Juice', 18000, 100],
    ['DR007', 'Berry Smoothie', 28000, 100],
    ['DR008', 'Mineral Water', 8000, 100],
    
    // MAKANAN RINGAN
    ['SN001', 'Croissant Plain', 15000, 50],
    ['SN002', 'Chocolate Croissant', 18000, 50],
    ['SN003', 'Blueberry Muffin', 20000, 50],
    ['SN004', 'Banana Bread', 16000, 50],
    ['SN005', 'Cheese Danish', 19000, 50],
    ['SN006', 'Bagel Cream Cheese', 22000, 50],
    ['SN007', 'Donut Glazed', 12000, 50],
    ['SN008', 'Cookies (3pcs)', 15000, 50],
    
    // MAKANAN BERAT
    ['FD001', 'Chicken Sandwich', 35000, 30],
    ['FD002', 'Beef Burger', 42000, 30],
    ['FD003', 'Caesar Salad', 28000, 30],
    ['FD004', 'Pasta Carbonara', 38000, 30],
    ['FD005', 'Grilled Chicken', 45000, 30],
    ['FD006', 'Fish & Chips', 40000, 30],
    ['FD007', 'Chicken Wings', 32000, 30],
    ['FD008', 'Club Sandwich', 36000, 30],
    
    // DESSERT
    ['DS001', 'Tiramisu', 32000, 25],
    ['DS002', 'Cheesecake', 28000, 25],
    ['DS003', 'Chocolate Brownie', 24000, 25],
    ['DS004', 'Apple Pie', 26000, 25],
    ['DS005', 'Ice Cream Vanilla', 18000, 25],
    ['DS006', 'Red Velvet Cake', 30000, 25]
];

echo "<h3>Inserting Sample Data...</h3>";

foreach ($sample_barang as $barang) {
    $sql_insert = "INSERT IGNORE INTO barang (id_barang, nama_barang, harga_barang, stok) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt, "ssdi", $barang[0], $barang[1], $barang[2], $barang[3]);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<p>✓ Inserted: {$barang[1]}</p>";
    } else {
        echo "<p>• Skipped: {$barang[1]} (already exists)</p>";
    }
    mysqli_stmt_close($stmt);
}

// Insert default users
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$kasir_password = password_hash('kasir123', PASSWORD_DEFAULT);

$sql_insert_admin = "INSERT IGNORE INTO users (username, password, nama_lengkap, level) VALUES ('admin', ?, 'Administrator', 'admin')";
$stmt_admin = mysqli_prepare($conn, $sql_insert_admin);
mysqli_stmt_bind_param($stmt_admin, "s", $admin_password);

if (mysqli_stmt_execute($stmt_admin)) {
    echo "<p>✓ Admin user created (username: admin, password: admin123)</p>";
} else {
    echo "<p>• Admin user already exists</p>";
}
mysqli_stmt_close($stmt_admin);

$sql_insert_kasir = "INSERT IGNORE INTO users (username, password, nama_lengkap, level) VALUES ('kasir1', ?, 'Kasir 1', 'kasir')";
$stmt_kasir = mysqli_prepare($conn, $sql_insert_kasir);
mysqli_stmt_bind_param($stmt_kasir, "s", $kasir_password);

if (mysqli_stmt_execute($stmt_kasir)) {
    echo "<p>✓ Kasir user created (username: kasir1, password: kasir123)</p>";
} else {
    echo "<p>• Kasir user already exists</p>";
}
mysqli_stmt_close($stmt_kasir);

// Insert login data (for compatibility with login.php)
$sql_insert_login = "INSERT IGNORE INTO login (user, pass, nama_toko, alamat, telp) VALUES ('admin', 'admin123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567')";
if (mysqli_query($conn, $sql_insert_login)) {
    echo "<p>✓ Login admin created (username: admin, password: admin123)</p>";
} else {
    echo "<p>• Login admin already exists</p>";
}

$sql_insert_login_kasir = "INSERT IGNORE INTO login (user, pass, nama_toko, alamat, telp) VALUES ('kasir', 'kasir123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567')";
if (mysqli_query($conn, $sql_insert_login_kasir)) {
    echo "<p>✓ Login kasir created (username: kasir, password: kasir123)</p>";
} else {
    echo "<p>• Login kasir already exists</p>";
}

mysqli_close($conn);

echo "<hr>";
echo "<h3>✅ Coffee Shop Database Setup Complete!</h3>";
echo "<div style='background:#f8f9fa;padding:15px;border-radius:8px;margin:10px 0;'>";
echo "<h4>☕ Selamat datong di NiKaula Coffee Shop!</h4>";
echo "<p><strong>Menu yang tersedia:</strong></p>";
echo "<ul style='columns:2;'>";
echo "<li>🏷️ <strong>40+ Menu Items</strong> (Kopi, Minuman, Makanan, Dessert)</li>";
echo "<li>☕ <strong>8 Jenis Kopi</strong> (Espresso, Americano, Cappuccino, dll)</li>";
echo "<li>🥤 <strong>8 Minuman Non-Kopi</strong> (Hot Chocolate, Tea Latte, dll)</li>";
echo "<li>🥐 <strong>8 Makanan Ringan</strong> (Croissant, Muffin, Danish, dll)</li>";
echo "<li>🍔 <strong>8 Makanan Berat</strong> (Sandwich, Burger, Pasta, dll)</li>";
echo "<li>🍰 <strong>6 Dessert</strong> (Tiramisu, Cheesecake, Brownie, dll)</li>";
echo "</ul>";
echo "<p><strong>Login Info:</strong> Username: <code>admin</code> | Password: <code>admin123</code></p>";
echo "</div>";
echo "<p><strong>Aplikasi kasir coffee shop Anda siap digunakan!</strong></p>";
echo "<p><a href='index.php' style='background:#6f4e37;color:white;padding:12px 24px;text-decoration:none;border-radius:25px;margin-right:10px;'>🏪 Buka Kasir Coffee Shop</a></p>";
echo "<p><a href='login.php' style='background:#8b4513;color:white;padding:12px 24px;text-decoration:none;border-radius:25px;margin-right:10px;'>🔐 Halaman Login</a></p>";
?>
