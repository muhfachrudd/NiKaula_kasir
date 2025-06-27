<?php
include 'config.php';

echo "<h2>Updating Login Table Structure</h2>";

// Add nama_toko column
echo "<p>Adding nama_toko column...</p>";
$result1 = mysqli_query($conn, "ALTER TABLE login ADD COLUMN nama_toko VARCHAR(100) DEFAULT 'NiKaula Coffee Shop'");
if ($result1) {
    echo "<p style='color: green;'>✓ Added nama_toko column successfully</p>";
} else {
    echo "<p style='color: red;'>Error adding nama_toko: " . mysqli_error($conn) . "</p>";
}

// Add alamat column  
echo "<p>Adding alamat column...</p>";
$result2 = mysqli_query($conn, "ALTER TABLE login ADD COLUMN alamat VARCHAR(200) DEFAULT 'Jl. Coffee Street No. 123, Jakarta'");
if ($result2) {
    echo "<p style='color: green;'>✓ Added alamat column successfully</p>";
} else {
    echo "<p style='color: red;'>Error adding alamat: " . mysqli_error($conn) . "</p>";
}

// Add telp column
echo "<p>Adding telp column...</p>";
$result3 = mysqli_query($conn, "ALTER TABLE login ADD COLUMN telp VARCHAR(20) DEFAULT '021-1234567'");
if ($result3) {
    echo "<p style='color: green;'>✓ Added telp column successfully</p>";
} else {
    echo "<p style='color: red;'>Error adding telp: " . mysqli_error($conn) . "</p>";
}

// Update existing records with default values
echo "<p>Updating existing records...</p>";
$update = mysqli_query($conn, "UPDATE login SET 
    nama_toko = COALESCE(nama_toko, 'NiKaula Coffee Shop'), 
    alamat = COALESCE(alamat, 'Jl. Coffee Street No. 123, Jakarta'), 
    telp = COALESCE(telp, '021-1234567')");
    
if ($update) {
    echo "<p style='color: green;'>✓ Updated existing records with default values</p>";
} else {
    echo "<p style='color: red;'>Error updating records: " . mysqli_error($conn) . "</p>";
}

// Show updated table structure
echo "<h3>Updated Login Table Structure:</h3>";
$result = mysqli_query($conn, "DESCRIBE login");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . ($row['Default'] ?: 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Show updated data
echo "<h3>Updated Login Table Data:</h3>";
$data = mysqli_query($conn, "SELECT * FROM login LIMIT 3");
if ($data && mysqli_num_rows($data) > 0) {
    echo "<table border='1'>";
    $first_row = true;
    while ($row = mysqli_fetch_assoc($data)) {
        if ($first_row) {
            echo "<tr>";
            foreach (array_keys($row) as $key) {
                echo "<th>" . htmlspecialchars($key) . "</th>";
            }
            echo "</tr>";
            $first_row = false;
        }
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

mysqli_close($conn);
echo "<hr>";
echo "<p><strong>Database update completed!</strong></p>";
echo "<a href='index.php' style='background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>← Back to Kasir</a>";
?>
