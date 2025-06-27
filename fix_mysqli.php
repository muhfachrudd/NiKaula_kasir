<?php
// Fix mysqli connection issue
echo "<h2>🔧 Fixing mysqli connection issue...</h2>";

$source_file = 'index.php';
$backup_file = 'index_backup.php';

// Create backup
if (copy($source_file, $backup_file)) {
    echo "<p>✅ Backup created: $backup_file</p>";
} else {
    echo "<p>❌ Failed to create backup</p>";
}

// Read the file
$content = file_get_contents($source_file);

// Remove all mysqli_close() calls except at the very end
$content = str_replace('mysqli_close($conn);', '// mysqli_close($conn); // Moved to end of file', $content);

// Add proper mysqli_close at the end before footer include
$content = str_replace(
    '<?php include \'template/footer.php\';?>',
    '<?php 
// Close database connection properly at the end
if (isset($conn) && $conn instanceof mysqli) {
    mysqli_close($conn);
}
include \'template/footer.php\';?>',
    $content
);

// Write back to file
if (file_put_contents($source_file, $content)) {
    echo "<p>✅ Fixed mysqli connection issue in $source_file</p>";
    echo "<p>✅ Database connection will only be closed at the end of the script</p>";
} else {
    echo "<p>❌ Failed to write to $source_file</p>";
}

echo "<hr>";
echo "<p><a href='index.php' class='btn btn-success'>Test Fixed Kasir</a></p>";
echo "<p><a href='simple_kasir.php' class='btn btn-primary'>Test Simple Kasir</a></p>";
echo "<p><small>Backup saved as: $backup_file</small></p>";
?>
