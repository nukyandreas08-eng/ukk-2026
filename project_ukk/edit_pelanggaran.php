<?php
// Memeriksa sesi login
include 'includes/cek_session.php';

// Menghubungkan ke database
include 'config/koneksi.php';


// Mengambil ID jenis pelanggaran
$id = (int) ($_GET['id'] ?? 0);

// Memeriksa apakah halaman dibuka melalui iframe
$embed = (($_GET['embed'] ?? '') === '1');


// ========================================
// MENGAMBIL DATA JENIS PELANGGARAN
// ========================================
$query = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_pelanggaran WHERE id = ?"
);

mysqli_stmt_bind_param($query, 'i', $id);
mysqli_stmt_execute($query);

$hasil = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($query);


// Jika data tidak ditemukan
if (!$data) {
    http_response_code(404);
    exit('Jenis pelanggaran tidak ditemukan.');
}


// ========================================
// MENGAMBIL DATA KATEGORI
// ========================================
$kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_pelanggaran_kategori
     ORDER BY nama"
);


// Fungsi untuk mengamankan teks HTML
function e($nilai)
{
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Jenis Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<main class="container py-4">

    <div class="card">

        <div class="card-header">
            Edit Jenis Pelanggaran
        </div>

        <div class="card-body">

            <!-- Form untuk mengedit jenis pelanggaran -->
            <form action="proses_pelanggaran.php" method="post">

                <!-- Data tersembunyi -->
                <input
                    type="hidden"
                    name="aksi"
                    value="edit"
                >

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


                <!-- Pilihan kategori -->
                <label class="form-label">
                    Kategori
                </label>

                <select
                    class="form-select mb-3"
                    name="pelanggaran_kategori_id"
                    required
                >

                    <?php while ($k = mysqli_fetch_assoc($kategori)): ?>

                        <option
                            value="<?= $k['id'] ?>"
                            <?= $k['id'] == $data['pelanggaran_kategori_id'] ? 'selected' : '' ?>
                        >
                            <?= e($k['nama']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>


                <!-- Kode pelanggaran -->
                <label class="form-label">
                    Kode
                </label>

                <input
                    type="text"
                    class="form-control mb-3"
                    name="kode"
                    required
                    value="<?= e($data['kode']) ?>"
                >


                <!-- Nama pelanggaran -->
                <label class="form-label">
                    Nama Pelanggaran
                </label>

                <input
                    type="text"
                    class="form-control mb-3"
                    name="nama"
                    required
                    value="<?= e($data['nama']) ?>"
                >


                <!-- Poin pelanggaran -->
                <label class="form-label">
                    Poin
                </label>

                <input
                    type="number"
                    min="0"
                    class="form-control mb-3"
                    name="poin"
                    required
                    value="<?= (int) $data['poin'] ?>"
                >


                <!-- Deskripsi pelanggaran -->
                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    class="form-control mb-3"
                    name="deskripsi"
                    required
                ><?= e($data['deskripsi']) ?></textarea>


                <!-- Status pelanggaran -->
                <label class="form-label">
                    Status
                </label>

                <select
                    class="form-select mb-3"
                    name="status_aktif"
                >

                    <option
                        value="1"
                        <?= $data['status_aktif'] ? 'selected' : '' ?>
                    >
                        Aktif
                    </option>

                    <option
                        value="0"
                        <?= !$data['status_aktif'] ? 'selected' : '' ?>
                    >
                        Tidak Aktif
                    </option>

                </select>


                <!-- Tombol simpan dan batal -->
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Perubahan
                </button>

                <a
                    class="btn btn-secondary"
                    href="catatan_pelanggaran.php<?= $embed ? '?embed=1' : '' ?>"
                >
                    Batal
                </a>

            </form>

        </div>
    </div>

</main>

</body>
</html>