<?php
require_once 'database.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Database Diagnostic ===\n\n";

try {
    $row = $conn->query("SELECT DATABASE() AS db, VERSION() AS ver")->fetch();
    echo "Connected to DB : {$row['db']}\n";
    echo "MySQL version  : {$row['ver']}\n\n";

    echo "--- Tables ---\n";
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (!$tables) {
        echo "(no tables)\n";
    } else {
        foreach ($tables as $t) {
            echo " - $t\n";
        }
    }

    echo "\n--- mpkk_attendance columns ---\n";
    $cols = $conn->query("SHOW COLUMNS FROM mpkk_attendance")->fetchAll();
    if (!$cols) {
        echo "(table not found or empty)\n";
    } else {
        foreach ($cols as $c) {
            $null = $c['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            echo sprintf(" %-15s %-20s %s\n", $c['Field'], $c['Type'], $null);
        }
    }

    echo "\n--- Row count ---\n";
    $count = $conn->query("SELECT COUNT(*) FROM mpkk_attendance")->fetchColumn();
    echo "Total rows: $count\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
