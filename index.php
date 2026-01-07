<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengesahan Kehadiran MPKK</title>
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
            --primary-light: #818cf8;
            --success: #10b981;
            --danger: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-gradient-start: #667eea;
            --bg-gradient-end: #764ba2;
            --card-bg: #ffffff;
            --input-border: #e5e7eb;
            --input-focus: #6366f1;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        body::after {
            width: 400px;
            height: 400px;
            bottom: -200px;
            right: -200px;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .container {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .card-header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            letter-spacing: -0.5px;
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 40px 30px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .form-label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            border: 2px solid var(--input-border);
            border-radius: 12px;
            background: white;
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .form-input.error,
        .form-select.error {
            border-color: var(--danger);
        }

        .error-message {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
            display: none;
            animation: shake 0.3s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-message.show {
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-text {
            position: relative;
            z-index: 1;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Mobile responsiveness */
        @media (max-width: 640px) {
            body {
                padding: 16px;
            }

            .card-header {
                padding: 32px 24px;
            }

            .card-header h1 {
                font-size: 24px;
            }

            .card-body {
                padding: 32px 24px;
            }

            .form-group {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 400px) {
            .card-header h1 {
                font-size: 22px;
            }

            .card-body {
                padding: 24px 20px;
            }
        }

        /* Loading state */
        .btn-submit.loading {
            pointer-events: none;
        }

        .btn-submit.loading .btn-text::after {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            margin-left: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Pengesahan Kehadiran</h1>
                <p>Majlis Pengurusan Komuniti Kampung</p>
            </div>
            
            <div class="card-body">
                <?php
                if (isset($_SESSION['flash_message'])) {
                    $type = $_SESSION['flash_type'] ?? 'success';
                    echo '<div class="alert alert-' . $type . '">';
                    echo htmlspecialchars($_SESSION['flash_message']);
                    echo '</div>';
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                }
                ?>

                <form id="attendanceForm" method="POST" action="submit.php">
                    <div class="form-group">
                        <label class="form-label" for="nama">
                            Nama <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            class="form-input"
                            placeholder="NAMA PENUH ANDA"
                            required
                            style="text-transform: uppercase;"
                        >
                        <div class="error-message" id="nama-error">Sila masukkan nama anda</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="no_ic">
                            No. IC <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="no_ic" 
                            name="no_ic" 
                            class="form-input"
                            placeholder="123456789012"
                            pattern="[0-9]{12}"
                            maxlength="12"
                            required
                        >
                        <div class="error-message" id="ic-error">Sila masukkan nombor IC yang sah (12 digit)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="no_telefon">
                            No. Telefon <span class="required">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="no_telefon" 
                            name="no_telefon" 
                            class="form-input"
                            placeholder="0123456789"
                            pattern="[0-9]{10,11}"
                            maxlength="11"
                            required
                        >
                        <div class="error-message" id="telefon-error">Sila masukkan nombor telefon yang sah (10-11 digit)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mpkk">
                            MPKK <span class="required">*</span>
                        </label>
                        <select id="mpkk" name="mpkk" class="form-select" required>
                            <option value="">-- Pilih MPKK --</option>
                            <option value="MPKK BERTAM INDAH">MPKK BERTAM INDAH</option>
                            <option value="MPKK PMTG TINGGI B">MPKK PMTG TINGGI B</option>
                            <option value="MPKK PAYA KELADI">MPKK PAYA KELADI</option>
                            <option value="MPKK LADANG MALAKOF">MPKK LADANG MALAKOF</option>
                            <option value="MPKK PMTG LANGSAT">MPKK PMTG LANGSAT</option>
                            <option value="MPKK PINANG TUNGGAL">MPKK PINANG TUNGGAL</option>
                            <option value="MPKK KG BAHARU">MPKK KG BAHARU</option>
                            <option value="MPKK KUBANG MENERONG">MPKK KUBANG MENERONG</option>
                            <option value="MPKK KG TOK BEDU">MPKK KG TOK BEDU</option>
                            <option value="MPKK KG SELAMAT RANCANGAN">MPKK KG SELAMAT RANCANGAN</option>
                            <option value="MPKK KG SELAMAT SELATAN">MPKK KG SELAMAT SELATAN</option>
                            <option value="MPKK JALAN KEDAH">MPKK JALAN KEDAH</option>
                            <option value="MPKK KG DATUK">MPKK KG DATUK</option>
                            <option value="MPKK PMTG SINTOK">MPKK PMTG SINTOK</option>
                            <option value="MPKK PMTG RAMBAI">MPKK PMTG RAMBAI</option>
                            <option value="MPKK PADANG BENGGALI">MPKK PADANG BENGGALI</option>
                            <option value="MPKK KUALA MUDA">MPKK KUALA MUDA</option>
                        </select>
                        <div class="error-message" id="mpkk-error">Sila pilih MPKK anda</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jawatan">
                            Jawatan Dalam MPKK <span class="required">*</span>
                        </label>
                        <select id="jawatan" name="jawatan" class="form-select" required>
                            <option value="">-- Pilih Jawatan --</option>
                            <option value="Pengerusi">Pengerusi</option>
                            <option value="Setiausaha">Setiausaha</option>
                            <option value="Bendahari">Bendahari</option>
                        </select>
                        <div class="error-message" id="jawatan-error">Sila pilih jawatan anda</div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-text">Hantar Pengesahan</span>
                    </button>
                </form>
            </div>
        </div>

        <p class="footer-text">© 2026 Majlis Pengurusan Komuniti Kampung</p>
    </div>

    <script>
        // Form validation and enhancement
        const form = document.getElementById('attendanceForm');
        const namaInput = document.getElementById('nama');
        const icInput = document.getElementById('no_ic');
        const telefonInput = document.getElementById('no_telefon');
        const mpkkSelect = document.getElementById('mpkk');
        const jawatanSelect = document.getElementById('jawatan');
        const submitBtn = document.getElementById('submitBtn');

        // Auto-uppercase for nama
        namaInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Only allow digits for IC number
        icInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Only allow digits for phone number
        telefonInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate nama
            if (namaInput.value.trim() === '') {
                showError('nama');
                isValid = false;
            } else {
                hideError('nama');
            }

            // Validate IC
            const icValue = icInput.value.trim();
            if (icValue === '' || icValue.length !== 12) {
                showError('ic');
                isValid = false;
            } else {
                hideError('ic');
            }

            // Validate telefon
            const telefonValue = telefonInput.value.trim();
            if (telefonValue === '' || telefonValue.length < 10 || telefonValue.length > 11) {
                showError('telefon');
                isValid = false;
            } else {
                hideError('telefon');
            }

            // Validate MPKK
            if (mpkkSelect.value === '') {
                showError('mpkk');
                isValid = false;
            } else {
                hideError('mpkk');
            }

            // Validate jawatan
            if (jawatanSelect.value === '') {
                showError('jawatan');
                isValid = false;
            } else {
                hideError('jawatan');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // Add loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        function showError(field) {
            const input = document.getElementById(field === 'telefon' ? 'no_telefon' : (field === 'ic' ? 'no_ic' : field));
            const error = document.getElementById(field + '-error');
            input.classList.add('error');
            error.classList.add('show');
        }

        function hideError(field) {
            const input = document.getElementById(field === 'telefon' ? 'no_telefon' : (field === 'ic' ? 'no_ic' : field));
            const error = document.getElementById(field + '-error');
            input.classList.remove('error');
            error.classList.remove('show');
        }

        // Remove error on input
        [namaInput, icInput, telefonInput, mpkkSelect, jawatanSelect].forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('error');
                const errorId = this.id === 'no_telefon' ? 'telefon-error' : (this.id === 'no_ic' ? 'ic-error' : this.id + '-error');
                document.getElementById(errorId).classList.remove('show');
            });
        });
    </script>
</body>
</html>
