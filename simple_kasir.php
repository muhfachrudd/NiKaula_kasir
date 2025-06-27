<?php
session_start();
$_SESSION['user'] = 'admin';
$_SESSION['status'] = 'login';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Kasir Test</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h2>Simple Kasir Test</h2>
    
    <?php
    include 'config.php';
    
    // Test 1: Show available menu
    echo "<h4>Available Menu (Top 5)</h4>";
    $barang = mysqli_query($conn, "SELECT * FROM barang LIMIT 5");
    if ($barang) {
        echo "<div class='row'>";
        while ($item = mysqli_fetch_assoc($barang)) {
            echo "<div class='col-md-4 mb-3'>";
            echo "<div class='card'>";
            echo "<div class='card-body'>";
            echo "<h6>{$item['nama_barang']}</h6>";
            echo "<p class='text-muted'>{$item['id_barang']}</p>";
            echo "<p class='h5 text-primary'>Rp " . number_format($item['harga_barang'], 0, ',', '.') . "</p>";
            echo "<button class='btn btn-sm btn-primary' onclick='addToCart(\"{$item['id_barang']}\", \"{$item['nama_barang']}\", {$item['harga_barang']})'>Add to Cart</button>";
            echo "</div></div></div>";
        }
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>Error loading menu: " . mysqli_error($conn) . "</div>";
    }
    
    // Test 2: Show current cart
    echo "<h4>🛒 Current Cart</h4>";
    $keranjang = mysqli_query($conn, "SELECT * FROM keranjang");
    if ($keranjang && mysqli_num_rows($keranjang) > 0) {
        echo "<table class='table'>";
        echo "<tr><th>Menu</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th>Action</th></tr>";
        $total_cart = 0;
        while ($cart = mysqli_fetch_assoc($keranjang)) {
            echo "<tr>";
            echo "<td>{$cart['nama_barang']}</td>";
            echo "<td>{$cart['quantity']}</td>";
            echo "<td>Rp " . number_format($cart['harga_barang'], 0, ',', '.') . "</td>";
            echo "<td>Rp " . number_format($cart['subtotal'], 0, ',', '.') . "</td>";
            echo "<td><a href='?remove={$cart['id_cart']}' class='btn btn-sm btn-danger'>Remove</a></td>";
            echo "</tr>";
            $total_cart += $cart['subtotal'];
        }
        echo "<tr class='table-primary'><th colspan='3'>Total</th><th>Rp " . number_format($total_cart, 0, ',', '.') . "</th><th></th></tr>";
        echo "</table>";
        echo "<a href='?clear=1' class='btn btn-warning'>Clear Cart</a>";
    } else {
        echo "<div class='alert alert-info'>Cart is empty</div>";
    }
    
    // Handle remove item
    if (isset($_GET['remove'])) {
        $id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_cart = '$id'");
        echo "<script>window.location='simple_kasir.php'</script>";
    }
    
    // Handle clear cart
    if (isset($_GET['clear'])) {
        mysqli_query($conn, "DELETE FROM keranjang");
        echo "<script>window.location='simple_kasir.php'</script>";
    }
    
    // Handle add to cart
    if (isset($_POST['add_cart'])) {
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $qty = $_POST['qty'] ?: 1;
        $subtotal = $harga * $qty;
        $tgl = date('Y-m-d');
        
        $sql = "INSERT INTO keranjang (kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input) 
                VALUES ('$kode', '$nama', '$harga', '$qty', '$subtotal', '$tgl')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>window.location='simple_kasir.php'</script>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
    ?>
    
    <div class="mt-4">
        <a href="index.php" class="btn btn-success">Go to Full Kasir</a>
        <a href="login.php" class="btn btn-secondary">Logout</a>
    </div>
    
    <script>
        function addToCart(kode, nama, harga) {
            const qty = prompt("Masukkan quantity:", "1");
            if (qty && qty > 0) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="add_cart" value="1">
                    <input type="hidden" name="kode" value="${kode}">
                    <input type="hidden" name="nama" value="${nama}">
                    <input type="hidden" name="harga" value="${harga}">
                    <input type="hidden" name="qty" value="${qty}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
