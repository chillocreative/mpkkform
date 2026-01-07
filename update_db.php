<?php
// Safe database update script for production
// usage: upload to server and visit /update_db.php

$host = 'localhost'; // Change if needed for production
$username = 'root';  // Change to your production db user
$password = '';      // Change to your production db password
$dbname = 'mpkk_attendance'; // Change to your production db name

// You might need to update these credentials manually if they differ from localhost
// Better yet, include database.php if it has the correct credentials
if (file_exists('database.php')) {
    require_once 'database.php';
} else {
    // Fallback connection if database.php not found/usable directly
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

try {
    // Check if column exists
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = :dbname 
        AND TABLE_NAME = 'mpkk_attendance' 
        AND COLUMN_NAME = 'no_ic'
    ");
    
    // We need to get the actual database name from the connection if possible,
    // or use the one defined/from config.
    $current_db = $conn->query('select database()')->fetchColumn();
    
    $stmt->execute([':dbname' => $current_db]);
    $column_exists = $stmt->fetchColumn();

    if (!$column_exists) {
        // Add the column
        $sql = "ALTER TABLE mpkk_attendance ADD COLUMN no_ic VARCHAR(20) NOT NULL AFTER nama";
        $conn->exec($sql);
        echo "<div style='color: green; font-family: sans-serif; padding: 20px; border: 1px solid green; background: #eaffea; border-radius: 5px; margin: 20px;'>";
        echo "✅ SUCCESS: Column 'no_ic' has been successfully added to the database.<br>";
        echo "You can now submit the form.";
        echo "</div>";
    } else {
        echo "<div style='color: blue; font-family: sans-serif; padding: 20px; border: 1px solid blue; background: #eaf4ff; border-radius: 5px; margin: 20px;'>";
        echo "ℹ️ INFO: Column 'no_ic' already exists. No changes needed.";
        echo "</div>";
    }

} catch(PDOException $e) {
    echo "<div style='color: red; font-family: sans-serif; padding: 20px; border: 1px solid red; background: #ffeaea; border-radius: 5px; margin: 20px;'>";
    echo "❌ ERROR: " . $e->getMessage();
    echo "</div>";
}
