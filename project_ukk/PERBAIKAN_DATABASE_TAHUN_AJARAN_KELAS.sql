-- PERBAIKAN DATABASE: HUBUNGAN KELAS DENGAN TAHUN AJARAN
-- Pilih database db_ukk_2026 di phpMyAdmin sebelum menjalankan file ini.
-- Jalankan SATU KALI saja pada database yang belum memiliki kolom tahun_ajaran_id.

ALTER TABLE t_kelas
    ADD COLUMN tahun_ajaran_id INT(11) NULL AFTER jurusan,
    ADD INDEX idx_t_kelas_tahun_ajaran_id (tahun_ajaran_id);

ALTER TABLE t_kelas
    ADD CONSTRAINT fk_t_kelas_tahun_ajaran
    FOREIGN KEY (tahun_ajaran_id)
    REFERENCES t_tahun_ajaran(id)
    ON DELETE SET NULL;

-- Hubungkan data kelas lama ke tahun ajaran aktif terbaru, jika tersedia.
UPDATE t_kelas
SET tahun_ajaran_id = (
    SELECT id
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
    LIMIT 1
)
WHERE tahun_ajaran_id IS NULL
  AND EXISTS (SELECT 1 FROM t_tahun_ajaran WHERE status_aktif = 1);
