<?php
session_start();
require_once 'database.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get and sanitize form data
$nama = strtoupper(trim($_POST['nama'] ?? ''));
$no_ic = preg_replace('/[^0-9]/', '', trim($_POST['no_ic'] ?? ''));
$no_telefon = preg_replace('/[^0-9]/', '', trim($_POST['no_telefon'] ?? ''));
$mpkk = trim($_POST['mpkk'] ?? '');
$jawatan = trim($_POST['jawatan'] ?? '');

// Server-side validation
$errors = [];

if (empty($nama)) {
    $errors[] = 'Nama diperlukan';
}

if (empty($no_ic) || strlen($no_ic) !== 12) {
    $errors[] = 'Nombor IC tidak sah (12 digit diperlukan)';
}

if (empty($no_telefon) || strlen($no_telefon) < 10 || strlen($no_telefon) > 11) {
    $errors[] = 'Nombor telefon tidak sah (10-11 digit diperlukan)';
}

if (empty($mpkk)) {
    $errors[] = 'Sila pilih MPKK';
}

$valid_jawatan = ['Pengerusi', 'Setiausaha', 'Bendahari', 'Ahli Jawatankuasa'];
if (empty($jawatan) || !in_array($jawatan, $valid_jawatan)) {
    $errors[] = 'Sila pilih jawatan yang sah';
}

// If there are validation errors, redirect back with error message
if (!empty($errors)) {
    setFlashMessage(implode(', ', $errors), 'error');
    header('Location: index.php');
    exit;
}

try {
    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO mpkk_attendance (nama, no_ic, no_telefon, mpkk, jawatan)
        VALUES (:nama, :no_ic, :no_telefon, :mpkk, :jawatan)
    ");
    
    $stmt->execute([
        ':nama' => $nama,
        ':no_ic' => $no_ic,
        ':no_telefon' => $no_telefon,
        ':mpkk' => $mpkk,
        ':jawatan' => $jawatan
    ]);
    
    // Set success message
    setFlashMessage('Pengesahan kehadiran berjaya dihantar! Terima kasih.', 'success');
    header('Location: index.php');
    exit;
    
} catch(PDOException $e) {
    // Set error message
    setFlashMessage('Ralat: ' . $e->getMessage(), 'error');
    header('Location: index.php');
    exit;
}
