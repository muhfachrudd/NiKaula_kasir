# ☕ NiKaula Coffee Shop - Sistem Kasir

## 🚀 Cara Menjalankan Aplikasi

### 1. Setup Database
Kunjungi: `http://localhost:8000/setup_database.php` untuk setup otomatis

### 2. Login ke Aplikasi
- **URL**: `http://localhost:8000/login.php`
- **Username**: `admin`
- **Password**: `admin123`

### 3. Akses Kasir
Setelah login, buka: `http://localhost:8000/index.php`

---

## 📋 Menu Coffee Shop (40+ Items)

### ☕ **MINUMAN KOPI** (8 items)
| Kode  | Nama Produk | Harga   |
|-------|-------------|---------|
| CF001 | Espresso    | 18,000  |
| CF002 | Americano   | 20,000  |
| CF003 | Cappuccino  | 25,000  |
| CF004 | Latte       | 28,000  |
| CF005 | Macchiato   | 30,000  |
| CF006 | Mocha       | 32,000  |
| CF007 | Flat White  | 26,000  |
| CF008 | Cold Brew   | 24,000  |

### 🥤 **MINUMAN NON-KOPI** (8 items)
| Kode  | Nama Produk        | Harga   |
|-------|--------------------|---------|
| DR001 | Hot Chocolate      | 22,000  |
| DR002 | Green Tea Latte    | 24,000  |
| DR003 | Chai Tea Latte     | 26,000  |
| DR004 | Lemon Tea          | 15,000  |
| DR005 | Ice Tea            | 12,000  |
| DR006 | Fresh Orange Juice | 18,000  |
| DR007 | Berry Smoothie     | 28,000  |
| DR008 | Mineral Water      | 8,000   |

### 🥐 **MAKANAN RINGAN** (8 items)
| Kode  | Nama Produk         | Harga   |
|-------|---------------------|---------|
| SN001 | Croissant Plain     | 15,000  |
| SN002 | Chocolate Croissant | 18,000  |
| SN003 | Blueberry Muffin    | 20,000  |
| SN004 | Banana Bread        | 16,000  |
| SN005 | Cheese Danish       | 19,000  |
| SN006 | Bagel Cream Cheese  | 22,000  |
| SN007 | Donut Glazed        | 12,000  |
| SN008 | Cookies (3pcs)      | 15,000  |

### 🍔 **MAKANAN BERAT** (8 items)
| Kode  | Nama Produk      | Harga   |
|-------|------------------|---------|
| FD001 | Chicken Sandwich | 35,000  |
| FD002 | Beef Burger      | 42,000  |
| FD003 | Caesar Salad     | 28,000  |
| FD004 | Pasta Carbonara  | 38,000  |
| FD005 | Grilled Chicken  | 45,000  |
| FD006 | Fish & Chips     | 40,000  |
| FD007 | Chicken Wings    | 32,000  |
| FD008 | Club Sandwich    | 36,000  |

### 🍰 **DESSERT** (6 items)
| Kode  | Nama Produk        | Harga   |
|-------|--------------------|---------|
| DS001 | Tiramisu           | 32,000  |
| DS002 | Cheesecake         | 28,000  |
| DS003 | Chocolate Brownie  | 24,000  |
| DS004 | Apple Pie          | 26,000  |
| DS005 | Ice Cream Vanilla  | 18,000  |
| DS006 | Red Velvet Cake    | 30,000  |

---

## 🔧 **Fitur Aplikasi**

### 🏪 **Kasir** (`index.php`)
- Input transaksi dengan barcode scanner
- Pencarian barang otomatis
- Kalkulasi total, bayar, kembalian
- Print struk (opsional)

### ☕ **Management Menu** (`barang.php`)
- Tambah, edit, hapus menu
- Update harga dan stok
- Kategorisasi produk

### 📊 **Laporan** (`laporan.php`)
- Laporan penjualan harian
- Rekap pendapatan
- Export data

### ⚙️ **Pengaturan** (`pengaturan.php`)
- Konfigurasi toko
- Management user
- Backup data

---

## 🛠 **Teknologi yang Digunakan**

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 4, FontAwesome
- **JavaScript**: jQuery, DataTables

---

## 📝 **Notes**

1. Pastikan MySQL service berjalan
2. Database `tb_kasir` akan dibuat otomatis
3. Semua menu sudah ter-input dengan harga dan stok
4. Login sistem menggunakan tabel `login`
5. Design responsif untuk mobile dan desktop

---

## 🆘 **Troubleshooting**

### Database Connection Error
- Pastikan XAMPP/WAMP MySQL running
- Check `config.php` untuk kredensial database

### Menu tidak muncul
- Jalankan `setup_database.php` untuk reset data
- Check tabel `barang` di phpMyAdmin

### Login gagal
- Username: `admin`, Password: `admin123`
- Check tabel `login` di database

---

**Selamat menggunakan NiKaula Coffee Shop! ☕**
