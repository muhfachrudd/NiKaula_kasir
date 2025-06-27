<?php
session_start();
$_SESSION['user'] = 'admin';
$_SESSION['status'] = 'login';

include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Kasir</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h2>🔍 Debug Kasir Page</h2>
    
    <?php
    echo "<h4>1. Database Connection</h4>";
    if ($conn) {
        echo "<p class='text-success'>✅ Database connected</p>";
    } else {
        echo "<p class='text-danger'>❌ Database connection failed</p>";
    }
    
    echo "<h4>2. Barang Data</h4>";
    $barang = mysqli_query($conn, "SELECT * FROM barang LIMIT 3");
    if ($barang && mysqli_num_rows($barang) > 0) {
        echo "<p class='text-success'>✅ Barang data available (" . mysqli_num_rows($barang) . " items)</p>";
        echo "<table class='table table-sm'>";
        echo "<tr><th>ID</th><th>Nama</th><th>Harga</th></tr>";
        while ($row = mysqli_fetch_assoc($barang)) {
            echo "<tr><td>{$row['id_barang']}</td><td>{$row['nama_barang']}</td><td>{$row['harga_barang']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='text-danger'>❌ No barang data found</p>";
    }
    
    echo "<h4>3. JavaScript Array Generation</h4>";
    $barang = mysqli_query($conn, "SELECT * FROM barang LIMIT 2");
    $jsArray = "var harga_barang = new Array();\n";
    $jsArray1 = "var nama_barang = new Array();\n";
    
    if ($barang) {
        while ($row_brg = mysqli_fetch_array($barang)) {
            $jsArray .= "harga_barang['" . $row_brg['id_barang'] . "'] = {harga_barang:'" . $row_brg['harga_barang'] . "'};\n";
            $jsArray1 .= "nama_barang['" . $row_brg['id_barang'] . "'] = {nama_barang:'" . addslashes($row_brg['nama_barang']) . "'};\n";
        }
    }
    
    echo "<pre style='background:#f8f9fa; padding:10px; border-radius:5px;'>";
    echo htmlspecialchars($jsArray);
    echo htmlspecialchars($jsArray1);
    echo "</pre>";
    
    echo "<h4>4. Keranjang Table</h4>";
    $keranjang = mysqli_query($conn, "SELECT * FROM keranjang");
    if ($keranjang) {
        echo "<p class='text-success'>✅ Keranjang table accessible (" . mysqli_num_rows($keranjang) . " items)</p>";
    } else {
        echo "<p class='text-danger'>❌ Keranjang table error: " . mysqli_error($conn) . "</p>";
    }
    
    echo "<h4>5. Session Data</h4>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    ?>
    
    <div class="mt-4">
        <a href="test_menu.php" class="btn btn-primary">Test Menu</a>
        <a href="index.php" class="btn btn-success">Go to Kasir</a>
        <a href="create_tables.php" class="btn btn-warning">Recreate Tables</a>
    </div>
</body>
</html>
