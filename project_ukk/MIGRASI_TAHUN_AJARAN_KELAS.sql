-- Jalankan file ini HANYA jika database db_ukk_2026 sudah terlanjur dibuat
-- menggunakan file SQL versi lama. Jangan jalankan jika kolom tahun_ajaran_id
-- sudah ada di tabel t_kelas.

ALTER TABLE t_kelas
    ADD COLUMN tahun_ajaran_id INT(11) NULL AFTER jurusan,
    ADD INDEX idx_t_kelas_tahun_ajaran_id (tahun_ajaran_id);

ALTER TABLE t_kelas
    ADD CONSTRAINT t_kelas_ibfk_1
    FOREIGN KEY (tahun_ajaran_id) REFERENCES t_tahun_ajaran(id)
    ON DELETE SET NULL;
