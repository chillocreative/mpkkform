<?php
require_once 'config.php';

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mpkk_attendance_' . date('Y-m-d') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Headers
$headers = [
    'ID',
    'Nama Penuh',
    'No. Kad Pengenalan',
    'No. Telefon',
    'Email',
    'Jawatan',
    'Nama MPKK',
    'Daerah',
    'Negeri',
    'Tarikh Kehadiran',
    'Masa Kehadiran',
    'Tujuan Kehadiran',
    'Catatan',
    'Status',
    'Tarikh Dihantar',
    'Tarikh Dikemaskini'
];

fputcsv($output, $headers);

// Get all records
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM mpkk_attendance ORDER BY created_at DESC");

while ($record = $stmt->fetch()) {
    $row = [
        $record['id'],
        $record['nama_penuh'],
        $record['no_kad_pengenalan'],
        $record['no_telefon'],
        $record['email'],
        $record['jawatan'],
        $record['nama_mpkk'],
        $record['daerah'],
        $record['negeri'],
        date('d/m/Y', strtotime($record['tarikh_kehadiran'])),
        date('h:i A', strtotime($record['masa_kehadiran'])),
        $record['tujuan_kehadiran'],
        $record['catatan'],
        $record['status'],
        date('d/m/Y h:i A', strtotime($record['created_at'])),
        date('d/m/Y h:i A', strtotime($record['updated_at']))
    ];
    
    fputcsv($output, $row);
}

fclose($output);
exit;
