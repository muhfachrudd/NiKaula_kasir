<?php
include 'config.php';

echo "<h2>Database Table Structure Check</h2>";

// Check login table structure
echo "<h3>Login Table Structure:</h3>";
$result = mysqli_query($conn, "DESCRIBE login");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Error checking login table: " . mysqli_error($conn) . "</p>";
}

// Check if login table has any data
echo "<h3>Login Table Data:</h3>";
$data = mysqli_query($conn, "SELECT * FROM login");
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
} else {
    echo "<p>No data found in login table or error: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>
