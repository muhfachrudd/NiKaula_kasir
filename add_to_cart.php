<?php
include 'config.php';

if ($_POST) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $quantity = $_POST['quantity'];
    $subtotal = $_POST['subtotal'];
    $tgl_input = $_POST['tgl_input'];
    
    // Validate data
    if (empty($kode_barang) || empty($nama_barang) || empty($harga_barang) || empty($quantity)) {
        echo "Error: Data tidak lengkap!";
        exit;
    }
    
    $sql = "INSERT INTO keranjang (kode_barang, nama_barang, harga_barang, quantity, subtotal, tgl_input) 
            VALUES ('$kode_barang', '$nama_barang', '$harga_barang', '$quantity', '$subtotal', '$tgl_input')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Success: Item berhasil ditambahkan ke keranjang!";
    } else {
        echo "Error: " . mysqli_error($conn);
        echo "<br>SQL: " . $sql;
    }
} else {
    echo "Error: No POST data received";
}
?>
