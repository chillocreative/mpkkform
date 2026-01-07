<?php
// Database setup script with No IC field
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database first
    $conn = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop and create database
    $conn->exec("DROP DATABASE IF EXISTS mpkk_attendance");
    $conn->exec("CREATE DATABASE mpkk_attendance");
    $conn->exec("USE mpkk_attendance");
    
    // Create table with no_ic field
    $sql = "CREATE TABLE mpkk_attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        no_ic VARCHAR(20) NOT NULL,
        no_telefon VARCHAR(20) NOT NULL,
        mpkk VARCHAR(255) NOT NULL,
        jawatan VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_mpkk (mpkk),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql);
    
    echo "Database setup completed successfully with No IC field!";
    
} catch(PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
