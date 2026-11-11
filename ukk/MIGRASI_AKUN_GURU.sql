-- PERBAIKAN AKUN LOGIN SEMUA GURU
-- Database: db_ukk_2026
-- Aman dijalankan setelah database diimpor. Bisa dijalankan ulang.
-- Username = nama guru di t_guru.nama
-- Password sementara semua guru = Guru2026!
-- Password disimpan sebagai bcrypt hash.

START TRANSACTION;

-- Pastikan kolom username tersedia.
SET @has_username := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 't_users'
    AND COLUMN_NAME = 'username'
);
SET @ddl := IF(@has_username = 0,
  'ALTER TABLE t_users ADD COLUMN username VARCHAR(80) NULL AFTER name',
  'SELECT 1');
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Pastikan indeks unik username tersedia.
SET @has_idx := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 't_users'
    AND INDEX_NAME = 'uq_t_users_username'
);
SET @ddl := IF(@has_idx = 0,
  'ALTER TABLE t_users ADD UNIQUE KEY uq_t_users_username (username)',
  'SELECT 1');
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Buat akun untuk setiap guru aktif. Nama guru menjadi username.
INSERT INTO t_users
  (name, username, email, password, remember_token, role, created_at, updated_at)
SELECT
  TRIM(g.nama),
  TRIM(g.nama),
  CONCAT('guru', g.id, '@sekolah.local'),
  '$2y$12$XVFV7RDWOV8CARg70T71MOv8bYQeAClr0Vdg2QiP5x9jrkt5UlQHq',
  '',
  'guru',
  NOW(),
  NOW()
FROM t_guru g
WHERE g.status_aktif = 1
  AND g.nama IS NOT NULL
  AND TRIM(g.nama) <> ''
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password = VALUES(password),
  role = 'guru',
  updated_at = NOW();

-- Hubungkan setiap data guru ke akun login yang username-nya sama dengan nama guru.
UPDATE t_guru g
JOIN t_users u ON u.username = TRIM(g.nama) AND u.role = 'guru'
SET g.user_id = u.id,
    g.updated_at = NOW()
WHERE g.status_aktif = 1;

COMMIT;

-- Pemeriksaan hasil:
SELECT g.id AS guru_id, g.nama, u.id AS user_id, u.username, u.role
FROM t_guru g
LEFT JOIN t_users u ON u.id = g.user_id
WHERE g.status_aktif = 1
ORDER BY g.id;
