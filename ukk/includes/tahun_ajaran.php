<?php
/**
 * ============================================================
 * TAHUN AJARAN AKTIF
 * ============================================================
 * File ini menyediakan cara tunggal untuk menentukan tahun ajaran
 * aktif, sehingga dropdown tahun ajaran pada seluruh formulir
 * otomatis terpilih tahun yang sedang aktif.
 *
 * Definisi tahun ajaran aktif:
 * 1. Statusnya "Aktif" (status_aktif = 1).
 * 2. Jika ada beberapa, diutamakan yang periodeacademiknya sedang
 *    berjalan (tanggal_mulai <= hari ini <= tanggal_selesai).
 * 3. Jika tetap lebih dari satu, yang paling baru (id terbesar).
 */

if (!function_exists('tahun_ajaran_aktif_id')) {

    /**
     * Mengembalikan ID tahun ajaran aktif.
     * Mengembalikan 0 jika tidak ada tahun ajaran aktif.
     */
    function tahun_ajaran_aktif_id($koneksi): int
    {
        static $cache = [];

        $db = $koneksi instanceof mysqli ? $koneksi : ($GLOBALS['koneksi'] ?? null);

        if (!($db instanceof mysqli)) {
            return 0;
        }

        if (isset($cache[$db->thread_id ?? 'default'])) {
            return $cache[$db->thread_id ?? 'default'];
        }

        $query = mysqli_query(
            $db,
            "SELECT id
             FROM t_tahun_ajaran
             WHERE status_aktif = 1
             ORDER BY (tanggal_mulai <= CURDATE()
                       AND tanggal_selesai >= CURDATE()) DESC,
                      id DESC
             LIMIT 1"
        );

        $id = 0;

        if ($query) {
            $data = mysqli_fetch_assoc($query);

            $id = (int) ($data['id'] ?? 0);

            mysqli_free_result($query);
        }

        return $cache[$db->thread_id ?? 'default'] = $id;
    }


    /**
     * Mengambil daftar tahun ajaran aktif untuk ditampilkan pada
     * dropdown. Tahun ajaran aktif otomatis menjadi terpilih.
     *
     * @param int $terpilih  ID yang akan menjadi terpilih. Jika 0,
     *                       memakai tahun ajaran aktif.
     */
    function tahun_ajaran_aktif_daftar($koneksi, int $terpilih = 0): array
    {
        $db = $koneksi instanceof mysqli ? $koneksi : ($GLOBALS['koneksi'] ?? null);

        $daftar = [];

        if (!($db instanceof mysqli)) {
            return $daftar;
        }

        if ($terpilih <= 0) {
            $terpilih = tahun_ajaran_aktif_id($db);
        }

        $query = mysqli_query(
            $db,
            'SELECT id, nama, tanggal_mulai, tanggal_selesai
             FROM t_tahun_ajaran
             WHERE status_aktif = 1
             ORDER BY (tanggal_mulai <= CURDATE()
                       AND tanggal_selesai >= CURDATE()) DESC,
                      id DESC'
        );

        if ($query) {
            while ($data = mysqli_fetch_assoc($query)) {
                $data['terpilih'] = ((int) $data['id'] === $terpilih);

                $daftar[] = $data;
            }

            mysqli_free_result($query);
        }

        return $daftar;
    }
}
