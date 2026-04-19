<?php
require_once 'database.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Safe Migration: add mpkk + jawatan, keep answer ===\n\n";

try {
    echo "[1/4] Snapshot of existing rows (for your reference):\n";
    $rows = $conn->query("SELECT id, nama, no_ic, no_telefon, answer FROM mpkk_attendance")->fetchAll();
    foreach ($rows as $r) {
        echo " #{$r['id']}  {$r['nama']}  ic={$r['no_ic']}  tel={$r['no_telefon']}  answer={$r['answer']}\n";
    }
    echo "\n";

    $cols = $conn->query("SHOW COLUMNS FROM mpkk_attendance")->fetchAll(PDO::FETCH_COLUMN);

    echo "[2/4] Adding mpkk column (if missing)...\n";
    if (!in_array('mpkk', $cols, true)) {
        $conn->exec("ALTER TABLE mpkk_attendance ADD COLUMN mpkk VARCHAR(255) NOT NULL DEFAULT '' AFTER no_telefon");
        echo "  -> added\n";
    } else {
        echo "  -> already exists, skipping\n";
    }

    echo "[3/4] Adding jawatan column (if missing)...\n";
    if (!in_array('jawatan', $cols, true)) {
        $conn->exec("ALTER TABLE mpkk_attendance ADD COLUMN jawatan VARCHAR(100) NOT NULL DEFAULT '' AFTER mpkk");
        echo "  -> added\n";
    } else {
        echo "  -> already exists, skipping\n";
    }

    echo "[4/4] Making 'answer' nullable so new inserts don't fail...\n";
    if (in_array('answer', $cols, true)) {
        $conn->exec("ALTER TABLE mpkk_attendance MODIFY answer VARCHAR(255) NULL");
        echo "  -> answer is now nullable (old data preserved)\n";
    } else {
        echo "  -> answer column not present, skipping\n";
    }

    echo "\n--- Final schema ---\n";
    foreach ($conn->query("SHOW COLUMNS FROM mpkk_attendance")->fetchAll() as $c) {
        $null = $c['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
        echo sprintf(" %-15s %-20s %s\n", $c['Field'], $c['Type'], $null);
    }

    echo "\nDone. You can now submit the form.\n";
    echo "After verifying it works, DELETE migrate_db.php and check_db.php from the server.\n";

} catch (PDOException $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
}
