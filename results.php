<?php
session_start();
require_once 'database.php';

// Get filter parameters
$search = $_GET['search'] ?? '';
$mpkk_filter = $_GET['mpkk_filter'] ?? '';
$jawatan_filter = $_GET['jawatan_filter'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$sql = "SELECT * FROM mpkk_attendance WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nama LIKE :search OR no_telefon LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($mpkk_filter)) {
    $sql .= " AND mpkk = :mpkk";
    $params[':mpkk'] = $mpkk_filter;
}

if (!empty($jawatan_filter)) {
    $sql .= " AND jawatan = :jawatan";
    $params[':jawatan'] = $jawatan_filter;
}

if (!empty($date_from)) {
    $sql .= " AND DATE(created_at) >= :date_from";
    $params[':date_from'] = $date_from;
}

if (!empty($date_to)) {
    $sql .= " AND DATE(created_at) <= :date_to";
    $params[':date_to'] = $date_to;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$records = $stmt->fetchAll();

// Get statistics
$stats_stmt = $conn->query("SELECT COUNT(*) as total FROM mpkk_attendance");
$stats = $stats_stmt->fetch();
$total = (int)($stats['total'] ?? 0);

// Get unique MPKKs for filter dropdown
$mpkk_stmt = $conn->query("SELECT DISTINCT mpkk FROM mpkk_attendance ORDER BY mpkk");
$mpkks = $mpkk_stmt->fetchAll(PDO::FETCH_COLUMN);

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Kehadiran MPKK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-gray: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-gray);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            margin-bottom: 24px;
            color: white;
            text-align: center;
        }

        .stats-number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stats-label {
            font-size: 16px;
            opacity: 0.9;
        }

        .filter-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .filter-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }

        .form-input,
        .form-select {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .nav-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .table-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f9fafb;
        }

        th {
            padding: 12px 24px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .empty-state {
            padding: 60px 24px;
            text-align: center;
            color: var(--text-secondary);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .record-count {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-secondary);
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Bulk delete styles */
        .bulk-actions {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f9fafb;
        }

        .bulk-actions-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .selected-count {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: none;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-danger.show {
            display: inline-block;
        }

        /* Checkbox styling */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        th input[type="checkbox"] {
            margin-right: 8px;
        }

        td input[type="checkbox"] {
            margin: 0;
        }

        .checkbox-cell {
            width: 50px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .header h1 {
                font-size: 24px;
            }

            .stats-number {
                font-size: 36px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
            }

            .table-container {
                font-size: 13px;
            }

            th, td {
                padding: 12px 16px;
            }

            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .checkbox-cell {
                width: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar">
            <a href="index.php" class="btn-home">&#8592; Kembali ke Utama</a>
        </div>
        <div class="header">
            <h1>Senarai Kehadiran</h1>
            <p>Majlis Pengurusan Komuniti Kampung</p>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="stats-card">
            <div class="stats-number"><?= number_format($total) ?></div>
            <div class="stats-label">Jumlah Kehadiran</div>
        </div>

        <div class="filter-card">
            <div class="filter-title">Penapis & Carian</div>
            <form method="GET" action="">
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">Carian</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-input" 
                            placeholder="Nama atau No. Telefon"
                            value="<?= htmlspecialchars($search) ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">MPKK</label>
                        <select name="mpkk_filter" class="form-select">
                            <option value="">Semua MPKK</option>
                            <?php foreach ($mpkks as $mpkk_option): ?>
                                <option value="<?= htmlspecialchars($mpkk_option) ?>" 
                                    <?= $mpkk_filter === $mpkk_option ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mpkk_option) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jawatan</label>
                        <select name="jawatan_filter" class="form-select">
                            <option value="">Semua Jawatan</option>
                            <option value="Pengerusi" <?= $jawatan_filter === 'Pengerusi' ? 'selected' : '' ?>>Pengerusi</option>
                            <option value="Setiausaha" <?= $jawatan_filter === 'Setiausaha' ? 'selected' : '' ?>>Setiausaha</option>
                            <option value="Bendahari" <?= $jawatan_filter === 'Bendahari' ? 'selected' : '' ?>>Bendahari</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tarikh Dari</label>
                        <input 
                            type="date" 
                            name="date_from" 
                            class="form-input"
                            value="<?= htmlspecialchars($date_from) ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tarikh Hingga</label>
                        <input 
                            type="date" 
                            name="date_to" 
                            class="form-input"
                            value="<?= htmlspecialchars($date_to) ?>"
                        >
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="results.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <div class="table-title">Rekod Kehadiran</div>
            </div>

            <?php if (empty($records)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div>Tiada Rekod Dijumpai</div>
                </div>
            <?php else: ?>
                <div class="bulk-actions">
                    <div class="bulk-actions-left">
                        <span class="selected-count" id="selectedCount">0 dipilih</span>
                    </div>
                    <button type="button" class="btn-danger" id="bulkDeleteBtn" onclick="confirmBulkDelete()">
                        🗑️ Padam Dipilih
                    </button>
                </div>

                <form id="bulkDeleteForm" method="POST" action="delete_bulk.php">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th class="checkbox-cell">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                    </th>
                                    <th>Bil</th>
                                    <th>Nama</th>
                                    <th>No. IC</th>
                                    <th>No. Telefon</th>
                                    <th>MPKK</th>
                                    <th>Jawatan</th>
                                    <th>Tarikh & Masa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $index => $record): ?>
                                    <tr>
                                        <td class="checkbox-cell">
                                            <input 
                                                type="checkbox" 
                                                name="selected_ids[]" 
                                                value="<?= $record['id'] ?>" 
                                                class="record-checkbox"
                                                onchange="updateSelectedCount()"
                                            >
                                        </td>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($record['nama']) ?></td>
                                        <td><?= htmlspecialchars($record['no_ic']) ?></td>
                                        <td><?= htmlspecialchars($record['no_telefon']) ?></td>
                                        <td><?= htmlspecialchars($record['mpkk']) ?></td>
                                        <td><?= htmlspecialchars($record['jawatan']) ?></td>
                                        <td><?= date('d/m/Y h:i A', strtotime($record['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="record-count">
                    Menunjukkan <?= count($records) ?> daripada <?= $total ?> rekod
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle select all checkboxes
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateSelectedCount();
        }

        // Update selected count and show/hide delete button
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.record-checkbox:checked');
            const count = checkboxes.length;
            const selectedCountEl = document.getElementById('selectedCount');
            const deleteBtn = document.getElementById('bulkDeleteBtn');
            const selectAllCheckbox = document.getElementById('selectAll');

            selectedCountEl.textContent = count + ' dipilih';

            if (count > 0) {
                deleteBtn.classList.add('show');
            } else {
                deleteBtn.classList.remove('show');
            }

            // Update select all checkbox state
            const totalCheckboxes = document.querySelectorAll('.record-checkbox').length;
            selectAllCheckbox.checked = count === totalCheckboxes && count > 0;
            selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
        }

        // Confirm bulk delete
        function confirmBulkDelete() {
            const checkboxes = document.querySelectorAll('.record-checkbox:checked');
            const count = checkboxes.length;

            if (count === 0) {
                alert('Sila pilih sekurang-kurangnya satu rekod untuk dipadam.');
                return;
            }

            const message = `Adakah anda pasti mahu memadam ${count} rekod yang dipilih?\n\nTindakan ini tidak boleh dibatalkan.`;

            if (confirm(message)) {
                document.getElementById('bulkDeleteForm').submit();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });
    </script>
</body>
</html>
