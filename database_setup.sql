-- Database Setup for NiKaula Kasir
-- Create database
CREATE DATABASE IF NOT EXISTS tb_kasir;
USE tb_kasir;

-- Create table barang
CREATE TABLE IF NOT EXISTS barang (
    id_barang VARCHAR(20) PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    harga_barang DECIMAL(10,2) NOT NULL,
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    tgl_transaksi DATE NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    bayar DECIMAL(15,2) NOT NULL,
    kembalian DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create table detail_transaksi
CREATE TABLE IF NOT EXISTS detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    id_barang VARCHAR(20),
    nama_barang VARCHAR(100),
    harga_barang DECIMAL(10,2),
    qty INT,
    subtotal DECIMAL(15,2),
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
);

-- Create table login (for compatibility with login.php)
CREATE TABLE IF NOT EXISTS login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    pass VARCHAR(50) NOT NULL,
    nama_toko VARCHAR(100) DEFAULT 'NiKaula Coffee Shop',
    alamat VARCHAR(200) DEFAULT 'Jl. Coffee Street No. 123, Jakarta',
    telp VARCHAR(20) DEFAULT '021-1234567'
);

-- Create table keranjang (shopping cart)
CREATE TABLE IF NOT EXISTS keranjang (
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
);

-- Create table laporanku (reports)
CREATE TABLE IF NOT EXISTS laporanku (
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
);

-- Create table users (for login system)
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    level ENUM('admin', 'kasir') DEFAULT 'kasir',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert coffee shop menu data
INSERT INTO barang (id_barang, nama_barang, harga_barang, stok) VALUES
-- MINUMAN KOPI
('CF001', 'Espresso', 18000, 100),
('CF002', 'Americano', 20000, 100),
('CF003', 'Cappuccino', 25000, 100),
('CF004', 'Latte', 28000, 100),
('CF005', 'Macchiato', 30000, 100),
('CF006', 'Mocha', 32000, 100),
('CF007', 'Flat White', 26000, 100),
('CF008', 'Cold Brew', 24000, 100),

-- MINUMAN NON KOPI
('DR001', 'Hot Chocolate', 22000, 100),
('DR002', 'Green Tea Latte', 24000, 100),
('DR003', 'Chai Tea Latte', 26000, 100),
('DR004', 'Lemon Tea', 15000, 100),
('DR005', 'Ice Tea', 12000, 100),
('DR006', 'Fresh Orange Juice', 18000, 100),
('DR007', 'Berry Smoothie', 28000, 100),
('DR008', 'Mineral Water', 8000, 100),

-- MAKANAN RINGAN
('SN001', 'Croissant Plain', 15000, 50),
('SN002', 'Chocolate Croissant', 18000, 50),
('SN003', 'Blueberry Muffin', 20000, 50),
('SN004', 'Banana Bread', 16000, 50),
('SN005', 'Cheese Danish', 19000, 50),
('SN006', 'Bagel Cream Cheese', 22000, 50),
('SN007', 'Donut Glazed', 12000, 50),
('SN008', 'Cookies (3pcs)', 15000, 50),

-- MAKANAN BERAT
('FD001', 'Chicken Sandwich', 35000, 30),
('FD002', 'Beef Burger', 42000, 30),
('FD003', 'Caesar Salad', 28000, 30),
('FD004', 'Pasta Carbonara', 38000, 30),
('FD005', 'Grilled Chicken', 45000, 30),
('FD006', 'Fish & Chips', 40000, 30),
('FD007', 'Chicken Wings', 32000, 30),
('FD008', 'Club Sandwich', 36000, 30),

-- DESSERT
('DS001', 'Tiramisu', 32000, 25),
('DS002', 'Cheesecake', 28000, 25),
('DS003', 'Chocolate Brownie', 24000, 25),
('DS004', 'Apple Pie', 26000, 25),
('DS005', 'Ice Cream Vanilla', 18000, 25),
('DS006', 'Red Velvet Cake', 30000, 25);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, nama_lengkap, level) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('kasir1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kasir 1', 'kasir');

-- Insert login data (for compatibility with login.php)
INSERT INTO login (user, pass, nama_toko, alamat, telp) VALUES
('admin', 'admin123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567'),
('kasir', 'kasir123', 'NiKaula Coffee Shop', 'Jl. Coffee Street No. 123, Jakarta', '021-1234567');

-- Show tables
SHOW TABLES;
