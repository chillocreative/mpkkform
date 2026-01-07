<?php
session_start();
require_once 'database.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: results.php');
    exit;
}

// Get selected IDs
$selected_ids = $_POST['selected_ids'] ?? [];

// Validate that IDs are provided
if (empty($selected_ids)) {
    setFlashMessage('Tiada rekod dipilih untuk dipadam.', 'error');
    header('Location: results.php');
    exit;
}

// Sanitize IDs (ensure they are integers)
$selected_ids = array_filter($selected_ids, 'is_numeric');
$selected_ids = array_map('intval', $selected_ids);

if (empty($selected_ids)) {
    setFlashMessage('ID rekod tidak sah.', 'error');
    header('Location: results.php');
    exit;
}

try {
    // Create placeholders for IN clause
    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
    
    // Prepare delete statement
    $sql = "DELETE FROM mpkk_attendance WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    
    // Execute deletion
    $stmt->execute($selected_ids);
    
    // Get number of deleted records
    $deleted_count = $stmt->rowCount();
    
    // Set success message
    if ($deleted_count > 0) {
        setFlashMessage("Berjaya memadam $deleted_count rekod.", 'success');
    } else {
        setFlashMessage('Tiada rekod dipadam.', 'error');
    }
    
} catch(PDOException $e) {
    // Set error message
    setFlashMessage('Ralat: Gagal memadam rekod. Sila cuba lagi.', 'error');
}

// Redirect back to results page
header('Location: results.php');
exit;
