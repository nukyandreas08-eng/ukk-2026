<?php
// Memeriksa apakah pengguna sudah login
include 'includes/cek_session.php';

// Menghubungkan website ke database
include 'config/koneksi.php';


// ========================================
// 1. MENGAMBIL ID KELAS DARI URL
// ========================================

// Memeriksa ID yang dikirim melalui URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Jika ID tidak valid, kembali ke halaman data kelas
if (!$id || $id < 1) {
    header('Location: kelola_kelas.php?pesan=id_tidak_valid');
    exit;
}


// ========================================
// 2. MENGAMBIL DATA KELAS DARI DATABASE
// ========================================

// Menyiapkan query untuk mencari kelas berdasarkan ID
$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id, nama, tingkat, jurusan, status_aktif
     FROM t_kelas
     WHERE id = ?'
);

// Memasukkan ID ke dalam query
mysqli_stmt_bind_param($stmt, 'i', $id);

// Menjalankan query
mysqli_stmt_execute($stmt);

// Mengambil hasil query
$result = mysqli_stmt_get_result($stmt);

// Menyimpan data kelas dalam bentuk array
$kelas = mysqli_fetch_assoc($result);

// Jika data kelas tidak ditemukan, kembali ke halaman data kelas
if (!$kelas) {
    header('Location: kelola_kelas.php?pesan=data_tidak_ditemukan');
    exit;
}


// ========================================
// 3. MEMPROSES PERUBAHAN DATA KELAS
// ========================================

// Menyiapkan variabel untuk pesan kesalahan
$error = '';

// Memeriksa apakah formulir dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari formulir dan menghapus spasi di awal/akhir
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    // Mengambil status aktif, jika tidak ada maka bernilai -1
    $status = isset($_POST['status_aktif'])
        ? (int) $_POST['status_aktif']
        : -1;


    // ========================================
    // 4. VALIDASI DATA YANG DIINPUT
    // ========================================

    // Memeriksa apakah semua data sudah diisi dengan benar
    if (
        $nama === '' ||
        !in_array($tingkat, ['X', 'XI', 'XII'], true) ||
        $jurusan === '' ||
        !in_array($status, [0, 1], true)
    ) {

        // Menampilkan pesan jika data tidak valid
        $error = 'Mohon lengkapi semua data dengan benar.';

        // Mempertahankan data yang sudah dimasukkan ke formulir
        $kelas = array_merge($kelas, [
            'nama' => $nama,
            'tingkat' => $tingkat,
            'jurusan' => $jurusan,
            'status_aktif' => $status
        ]);

    } else {

        // ========================================
        // 5. MEMPERBARUI DATA KELAS
        // ========================================

        // Menyiapkan query untuk memperbarui data kelas
        $update = mysqli_prepare(
            $koneksi,
            'UPDATE t_kelas
             SET nama = ?,
                 tingkat = ?,
                 jurusan = ?,
                 status_aktif = ?,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = ?'
        );

        // Memasukkan data ke dalam query UPDATE
        mysqli_stmt_bind_param(
            $update,
            'sssii',
            $nama,
            $tingkat,
            $jurusan,
            $status,
            $id
        );

        // Menjalankan proses pembaruan data
        if (mysqli_stmt_execute($update)) {

            // Jika berhasil, kembali ke halaman data kelas
            header('Location: kelola_kelas.php?pesan=edit_berhasil');
            exit;

        } else {

            // Menampilkan pesan jika pembaruan gagal
            $error = 'Gagal mengubah data kelas: ' . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Kelas</title>

    <!-- Bootstrap 5.3.3 untuk tampilan halaman -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="card shadow-sm">

            <!-- Judul halaman -->
            <div class="card-header">
                <h4 class="mb-0">Edit Kelas</h4>
            </div>

            <div class="card-body">

                <!-- Menampilkan pesan kesalahan jika ada -->
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>


                <!-- Formulir untuk mengubah data kelas -->
                <form method="post">

                    <!-- Input nama kelas -->
                    <div class="mb-3">
                        <label class="form-label" for="nama">
                            Nama Kelas
                        </label>

                        <input
                            class="form-control"
                            id="nama"
                            name="nama"
                            value="<?= htmlspecialchars((string) $kelas['nama']) ?>"
                            required
                        >
                    </div>


                    <!-- Pilihan tingkat kelas -->
                    <div class="mb-3">
                        <label class="form-label" for="tingkat">
                            Tingkat
                        </label>

                        <select
                            class="form-select"
                            id="tingkat"
                            name="tingkat"
                            required
                        >
                            <option value="">Pilih Tingkat</option>

                            <?php foreach (['X', 'XI', 'XII'] as $t): ?>
                                <option
                                    value="<?= $t ?>"
                                    <?= $kelas['tingkat'] === $t ? 'selected' : '' ?>
                                >
                                    <?= $t ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>


                    <!-- Input jurusan -->
                    <div class="mb-3">
                        <label class="form-label" for="jurusan">
                            Jurusan
                        </label>

                        <input
                            class="form-control"
                            id="jurusan"
                            name="jurusan"
                            value="<?= htmlspecialchars((string) $kelas['jurusan']) ?>"
                            required
                        >
                    </div>


                    <!-- Pilihan status kelas -->
                    <div class="mb-3">
                        <label class="form-label" for="status_aktif">
                            Status
                        </label>

                        <select
                            class="form-select"
                            id="status_aktif"
                            name="status_aktif"
                            required
                        >
                            <option
                                value="1"
                                <?= (int) $kelas['status_aktif'] === 1 ? 'selected' : '' ?>
                            >
                                Aktif
                            </option>

                            <option
                                value="0"
                                <?= (int) $kelas['status_aktif'] === 0 ? 'selected' : '' ?>
                            >
                                Tidak Aktif
                            </option>
                        </select>
                    </div>


                    <!-- Tombol simpan dan batal -->
                    <button class="btn btn-primary" type="submit">
                        Simpan Perubahan
                    </button>

                    <a
                        class="btn btn-secondary"
                        href="kelola_kelas.php"
                    >
                        Batal
                    </a>

                </form>

            </div>
        </div>
    </div>

</body>
</html>