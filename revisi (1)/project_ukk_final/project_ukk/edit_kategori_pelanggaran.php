<?php
// Memeriksa sesi login
include 'includes/cek_session.php';

// Menghubungkan ke database
include 'config/koneksi.php';


// Mengambil ID kategori dari URL atau form
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

// Memeriksa apakah halaman dibuka melalui iframe
$embed = (
    ($_GET['embed'] ?? $_POST['embed'] ?? '') === '1'
);

// Menyimpan pesan kesalahan
$error = '';


// ========================================
// MENGAMBIL DATA KATEGORI
// ========================================
$query = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_pelanggaran_kategori WHERE id = ?"
);

mysqli_stmt_bind_param($query, 'i', $id);
mysqli_stmt_execute($query);

$hasil = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($query);


// Jika kategori tidak ditemukan
if (!$data) {
    http_response_code(404);
    exit('Kategori tidak ditemukan.');
}


// ========================================
// PROSES SIMPAN PERUBAHAN
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari form
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Checkbox aktif bernilai 1 jika dicentang
    $status = isset($_POST['status_aktif']) ? 1 : 0;


    // Memeriksa nama kategori
    if ($nama === '') {

        $error = 'Nama kategori wajib diisi.';

    } else {

        // Memperbarui data kategori
        $update = mysqli_prepare(
            $koneksi,
            "UPDATE t_pelanggaran_kategori
             SET nama = ?,
                 deskripsi = ?,
                 status_aktif = ?,
                 updated_at = NOW()
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            'ssii',
            $nama,
            $deskripsi,
            $status,
            $id
        );


        // Menjalankan perintah UPDATE
        if (mysqli_stmt_execute($update)) {

            mysqli_stmt_close($update);

            // Kembali ke halaman data kategori
            $halaman = 'kelola_kategori_pelanggaran.php';

            if ($embed) {
                $halaman .= '?embed=1';
            }

            header("Location: $halaman");
            exit;

        } else {

            $error = 'Gagal memperbarui kategori.';
        }

        mysqli_stmt_close($update);
    }


    // Menampilkan kembali data yang telah diisi
    $data['nama'] = $nama;
    $data['deskripsi'] = $deskripsi;
    $data['status_aktif'] = $status;
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Kategori</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<main class="container py-4">

    <div class="card">

        <div class="card-header">
            Edit Kategori Pelanggaran
        </div>

        <div class="card-body">

            <!-- Menampilkan pesan kesalahan -->
            <?php if ($error !== ''): ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php endif; ?>


            <!-- Form edit kategori -->
            <form method="post">

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


                <!-- Nama kategori -->
                <label class="form-label">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    class="form-control mb-3"
                    name="nama"
                    required
                    value="<?= htmlspecialchars(
                        $data['nama'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >


                <!-- Deskripsi kategori -->
                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    class="form-control mb-3"
                    name="deskripsi"
                    rows="3"
                ><?= htmlspecialchars(
                    $data['deskripsi'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></textarea>


                <!-- Status kategori -->
                <div class="form-check mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status_aktif"
                        id="status"
                        value="1"
                        <?= !empty($data['status_aktif']) ? 'checked' : '' ?>
                    >

                    <label
                        class="form-check-label"
                        for="status"
                    >
                        Aktif
                    </label>

                </div>


                <!-- Tombol aksi -->
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Perubahan
                </button>

                <a
                    class="btn btn-secondary"
                    href="kelola_kategori_pelanggaran.php<?= $embed ? '?embed=1' : '' ?>"
                >
                    Batal
                </a>

            </form>

        </div>
    </div>

</main>

</body>
</html>