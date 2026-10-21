<?php
// Memeriksa sesi login pengguna
include 'includes/cek_session.php';

// Menghubungkan website ke database
include 'config/koneksi.php';


// ========================================
// 1. MENGAMBIL ID DAN STATUS EMBED
// ========================================

// Mengambil ID dari URL atau formulir
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

// Memeriksa apakah halaman dibuka melalui iframe
$embed = (
    ($_GET['embed'] ?? $_POST['embed'] ?? '') === '1'
);

// Variabel untuk menyimpan pesan kesalahan
$error = '';


// ========================================
// 2. MENGAMBIL DATA PENEMPATAN SISWA
// ========================================

// Mencari data penempatan berdasarkan ID
$stmt = mysqli_prepare(
    $koneksi,
    'SELECT * FROM t_kelas_siswa WHERE id = ?'
);

// Memasukkan ID ke dalam query
mysqli_stmt_bind_param($stmt, 'i', $id);

// Menjalankan query
mysqli_stmt_execute($stmt);

// Mengambil data penempatan
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Menutup statement
mysqli_stmt_close($stmt);

// Jika data tidak ditemukan, tampilkan pesan
if (!$data) {
    http_response_code(404);
    exit('Data penempatan tidak ditemukan.');
}


// ========================================
// 3. MEMPROSES FORMULIR EDIT
// ========================================

// Memeriksa apakah formulir dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari formulir
    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);


    // ========================================
    // 4. MEMVALIDASI PILIHAN
    // ========================================

    // Memastikan semua pilihan sudah diisi
    if ($siswa_id < 1 || $kelas_id < 1 || $tahun_id < 1) {

        $error = 'Semua pilihan wajib diisi.';

    } else {

        // ========================================
        // 5. MEMERIKSA PENEMPATAN SISWA
        // ========================================

        // Memeriksa apakah siswa sudah ditempatkan
        // pada tahun ajaran yang sama
        // ID saat ini dikecualikan karena sedang diedit
        $cek = mysqli_prepare(
            $koneksi,
            'SELECT id
             FROM t_kelas_siswa
             WHERE siswa_id = ?
             AND tahun_ajaran_id = ?
             AND id <> ?'
        );

        // Memasukkan data untuk pemeriksaan
        mysqli_stmt_bind_param(
            $cek,
            'iii',
            $siswa_id,
            $tahun_id,
            $id
        );

        // Menjalankan pemeriksaan
        mysqli_stmt_execute($cek);

        // Memeriksa apakah data penempatan sudah ada
        $exists = mysqli_num_rows(
            mysqli_stmt_get_result($cek)
        ) > 0;

        // Menutup statement pemeriksaan
        mysqli_stmt_close($cek);


        // Jika siswa sudah ditempatkan pada tahun ajaran tersebut
        if ($exists) {

            $error = 'Siswa sudah ditempatkan pada tahun ajaran tersebut.';

        } else {

            // ========================================
            // 6. MEMPERBARUI DATA PENEMPATAN
            // ========================================

            // Menyiapkan query untuk mengubah data
            $up = mysqli_prepare(
                $koneksi,
                'UPDATE t_kelas_siswa
                 SET siswa_id = ?,
                     kelas_id = ?,
                     tahun_ajaran_id = ?,
                     updated_at = NOW()
                 WHERE id = ?'
            );

            // Memasukkan data baru ke query UPDATE
            mysqli_stmt_bind_param(
                $up,
                'iiii',
                $siswa_id,
                $kelas_id,
                $tahun_id,
                $id
            );

            // Menjalankan proses pembaruan
            if (mysqli_stmt_execute($up)) {

                // Menutup statement
                mysqli_stmt_close($up);

                // Kembali ke halaman penempatan siswa
                // Parameter embed dipertahankan jika diperlukan
                header(
                    'Location: kelola_penempatan_siswa.php'
                    . ($embed ? '?embed=1' : '')
                );

                exit;

            } else {

                // Menampilkan pesan jika pembaruan gagal
                $error = 'Gagal memperbarui penempatan.';

                // Menutup statement
                mysqli_stmt_close($up);
            }
        }
    }
}


// ========================================
// 7. MENGAMBIL DATA UNTUK PILIHAN FORM
// ========================================

// Mengambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    'SELECT id, nis, nama
     FROM t_siswa
     ORDER BY nama'
);

// Mengambil daftar kelas
$kelas = mysqli_query(
    $koneksi,
    'SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat, jurusan, nama'
);

// Mengambil daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    'SELECT id, nama
     FROM t_tahun_ajaran
     ORDER BY id DESC'
);
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Penempatan Siswa</title>

    <!-- Bootstrap 5.3.3 untuk tampilan halaman -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <main class="container py-4">

        <div class="card">

            <!-- Judul halaman -->
            <div class="card-header">
                Edit Penempatan Siswa
            </div>

            <div class="card-body">

                <!-- Menampilkan pesan kesalahan -->
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>


                <!-- Formulir edit penempatan siswa -->
                <form method="post">

                    <!-- Menyimpan ID dan status embed -->
                    <input
                        type="hidden"
                        name="id"
                        value="<?= $id ?>"
                    >

                    <input
                        type="hidden"
                        name="embed"
                        value="<?= $embed ? '1' : '0' ?>"
                    >


                    <!-- Pilihan siswa -->
                    <label class="form-label">
                        Siswa
                    </label>

                    <select
                        class="form-select mb-3"
                        name="siswa_id"
                        required
                    >
                        <?php while ($r = mysqli_fetch_assoc($siswa)): ?>

                            <option
                                value="<?= $r['id'] ?>"
                                <?= $r['id'] == $data['siswa_id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($r['nis'] . ' - ' . $r['nama']) ?>
                            </option>

                        <?php endwhile; ?>
                    </select>


                    <!-- Pilihan kelas -->
                    <label class="form-label">
                        Kelas
                    </label>

                    <select
                        class="form-select mb-3"
                        name="kelas_id"
                        required
                    >
                        <?php while ($r = mysqli_fetch_assoc($kelas)): ?>

                            <option
                                value="<?= $r['id'] ?>"
                                <?= $r['id'] == $data['kelas_id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($r['nama'] . ' - ' . $r['tingkat'] . ' - ' . $r['jurusan']) ?>
                            </option>

                        <?php endwhile; ?>
                    </select>


                    <!-- Pilihan tahun ajaran -->
                    <label class="form-label">
                        Tahun Ajaran
                    </label>

                    <select
                        class="form-select mb-3"
                        name="tahun_ajaran_id"
                        required
                    >
                        <?php while ($r = mysqli_fetch_assoc($tahun)): ?>

                            <option
                                value="<?= $r['id'] ?>"
                                <?= $r['id'] == $data['tahun_ajaran_id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($r['nama']) ?>
                            </option>

                        <?php endwhile; ?>
                    </select>


                    <!-- Tombol simpan perubahan -->
                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <!-- Tombol kembali ke halaman sebelumnya -->
                    <a
                        class="btn btn-secondary"
                        href="kelola_penempatan_siswa.php<?= $embed ? '?embed=1' : '' ?>"
                    >
                        Batal
                    </a>

                </form>

            </div>
        </div>
    </main>

</body>
</html>