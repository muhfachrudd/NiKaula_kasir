<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Menu Data</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h2>🧪 Test Menu Coffee Shop</h2>
    
    <div class="row">
        <div class="col-md-6">
            <h4>📋 Available Menu</h4>
            <?php
            $barang = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang");
            if (mysqli_num_rows($barang) > 0) {
                echo "<table class='table table-striped'>";
                echo "<tr><th>Kode</th><th>Nama</th><th>Harga</th></tr>";
                while ($row = mysqli_fetch_assoc($barang)) {
                    echo "<tr>";
                    echo "<td>{$row['id_barang']}</td>";
                    echo "<td>{$row['nama_barang']}</td>";
                    echo "<td>Rp " . number_format($row['harga_barang'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='alert alert-warning'>❌ Tidak ada menu ditemukan!</div>";
                echo "<p><a href='setup_database.php' class='btn btn-primary'>Setup Database</a></p>";
            }
            ?>
        </div>
        
        <div class="col-md-6">
            <h4>🧾 Test Kasir Form</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Pilih Menu:</label>
                    <select name="test_barang" class="form-control" id="test_barang" onchange="loadBarangData()">
                        <option value="">-- Pilih Menu --</option>
                        <?php
                        mysqli_data_seek($barang, 0);
                        while ($row = mysqli_fetch_assoc($barang)) {
                            echo "<option value='{$row['id_barang']}' data-nama='{$row['nama_barang']}' data-harga='{$row['harga_barang']}'>";
                            echo "{$row['id_barang']} - {$row['nama_barang']} - Rp " . number_format($row['harga_barang'], 0, ',', '.');
                            echo "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Nama Menu:</label>
                    <input type="text" class="form-control" id="test_nama" readonly>
                </div>
                
                <div class="form-group">
                    <label>Harga:</label>
                    <input type="number" class="form-control" id="test_harga" readonly>
                </div>
                
                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" class="form-control" id="test_qty" min="1" onchange="hitungTotal()">
                </div>
                
                <div class="form-group">
                    <label>Subtotal:</label>
                    <input type="number" class="form-control" id="test_subtotal" readonly>
                </div>
                
                <button type="button" class="btn btn-primary" onclick="testAddToCart()">Test Add to Cart</button>
                <a href="index.php" class="btn btn-success">Go to Kasir</a>
            </form>
        </div>
    </div>

    <script>
        function loadBarangData() {
            const select = document.getElementById('test_barang');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                document.getElementById('test_nama').value = option.getAttribute('data-nama');
                document.getElementById('test_harga').value = option.getAttribute('data-harga');
                hitungTotal();
            } else {
                document.getElementById('test_nama').value = '';
                document.getElementById('test_harga').value = '';
                document.getElementById('test_subtotal').value = '';
            }
        }
        
        function hitungTotal() {
            const harga = parseFloat(document.getElementById('test_harga').value) || 0;
            const qty = parseInt(document.getElementById('test_qty').value) || 0;
            const subtotal = harga * qty;
            document.getElementById('test_subtotal').value = subtotal;
        }
        
        function testAddToCart() {
            const kode = document.getElementById('test_barang').value;
            const nama = document.getElementById('test_nama').value;
            const harga = document.getElementById('test_harga').value;
            const qty = document.getElementById('test_qty').value;
            const subtotal = document.getElementById('test_subtotal').value;
            
            if (!kode || !nama || !harga || !qty) {
                alert('Mohon lengkapi semua field!');
                return;
            }
            
            // Send to keranjang via AJAX
            const formData = new FormData();
            formData.append('kode_barang', kode);
            formData.append('nama_barang', nama);
            formData.append('harga_barang', harga);
            formData.append('quantity', qty);
            formData.append('subtotal', subtotal);
            formData.append('tgl_input', new Date().toLocaleDateString('id-ID'));
            
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Item berhasil ditambahkan ke keranjang!');
                console.log(data);
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    </script>
</body>
</html>
