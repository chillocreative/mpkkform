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

function columnExists($conn, $dbname, $table, $column) {
    $stmt = $conn->prepare("
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = :dbname
        AND TABLE_NAME = :table
        AND COLUMN_NAME = :column
    ");
    $stmt->execute([
        ':dbname' => $dbname,
        ':table' => $table,
        ':column' => $column,
    ]);
    return (bool)$stmt->fetchColumn();
}

function renderBox($color, $bg, $message) {
    echo "<div style='color: {$color}; font-family: sans-serif; padding: 20px; border: 1px solid {$color}; background: {$bg}; border-radius: 5px; margin: 20px;'>";
    echo $message;
    echo "</div>";
}

try {
    $current_db = $conn->query('select database()')->fetchColumn();

    $migrations = [
        ['column' => 'no_ic', 'sql' => "ALTER TABLE mpkk_attendance ADD COLUMN no_ic VARCHAR(20) NOT NULL AFTER nama"],
        ['column' => 'status', 'sql' => "ALTER TABLE mpkk_attendance ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'Hadir'"],
        ['column' => 'sebab_tidak_hadir', 'sql' => "ALTER TABLE mpkk_attendance ADD COLUMN sebab_tidak_hadir TEXT NULL"],
    ];

    foreach ($migrations as $migration) {
        if (!columnExists($conn, $current_db, 'mpkk_attendance', $migration['column'])) {
            $conn->exec($migration['sql']);
            renderBox('green', '#eaffea', "✅ SUCCESS: Column '{$migration['column']}' has been added.");
        } else {
            renderBox('blue', '#eaf4ff', "ℹ️ INFO: Column '{$migration['column']}' already exists. No changes needed.");
        }
    }

} catch(PDOException $e) {
    renderBox('red', '#ffeaea', "❌ ERROR: " . htmlspecialchars($e->getMessage()));
}
