CREATE TABLE IF NOT EXISTS `mpkk_attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(255) NOT NULL,
    `no_ic` VARCHAR(20) NOT NULL,
    `no_telefon` VARCHAR(20) NOT NULL,
    `mpkk` VARCHAR(255) NOT NULL,
    `jawatan` VARCHAR(100) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Hadir',
    `sebab_tidak_hadir` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_mpkk` (`mpkk`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
