
<?php
// Memeriksa sesi login
include 'includes/cek_session.php';

// Menghubungkan ke database
include 'config/koneksi.php';


// ========================================
// PENGATURAN HALAMAN
// ========================================

// Memeriksa apakah halaman dibuka melalui iframe
$embed = (($_GET['embed'] ?? '') === '1');

// Mengambil ID guru dari URL
$id = (int) ($_GET['id'] ?? 0);


// ========================================
// MEMERIKSA ID GURU
// ========================================
if ($id <= 0) {

    $_SESSION['pesan'] = 'ID guru tidak valid.';

    header('Location: kelola_guru.php?embed=1');
    exit;
}


// ========================================
// MENGAMBIL DATA GURU
// ========================================
$query = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_guru WHERE id = ?"
);

mysqli_stmt_bind_param($query, 'i', $id);
mysqli_stmt_execute($query);

$hasil = mysqli_stmt_get_result($query);
$guru = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($query);


// Jika data guru tidak ditemukan
if (!$guru) {

    $_SESSION['pesan'] = 'Data guru tidak ditemukan.';

    header('Location: kelola_guru.php?embed=1');
    exit;
}

// Guru lama yang belum terhubung ke akun perlu membuat password saat diedit.
$perlu_buat_akun = (int) ($guru['user_id'] ?? 0) < 1;


// ========================================
// FUNGSI MENGAMANKAN TEKS
// ========================================
function e($nilai)
{
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>


<!-- ========================================
     BAGIAN HTML
========================================= -->

<?php if (!$embed): ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Edit Data Guru</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<?php endif; ?>


<!-- KONTEN EDIT DATA GURU -->
<div class="container-fluid p-3">

    <h1 class="mb-3">Edit Data Guru</h1>

    <hr>


    <!-- Menampilkan pesan -->
    <?php if (isset($_SESSION['pesan'])): ?>

        <div class="alert alert-info">
            <?= e($_SESSION['pesan']) ?>
        </div>

        <?php unset($_SESSION['pesan']); ?>

    <?php endif; ?>


    <!-- FORM EDIT GURU -->
    <form
        action="proses_edit_guru.php"
        method="POST"
        class="row g-3"
    >

        <!-- Menyimpan ID guru -->
        <input
            type="hidden"
            name="id"
            value="<?= (int) $guru['id'] ?>"
        >

        <!-- Menandai halaman menggunakan iframe -->
        <input
            type="hidden"
            name="embed"
            value="1"
        >


        <!-- NIP -->
        <div class="col-12 col-md-6">

            <label class="form-label">
                NIP
            </label>

            <input
                type="text"
                class="form-control"
                name="nip"
                value="<?= e($guru['nip'] ?? '') ?>"
                required
            >

        </div>


        <!-- NAMA GURU -->
        <div class="col-12 col-md-6">

            <label class="form-label">
                Nama Guru
            </label>

            <input
                type="text"
                class="form-control"
                name="nama"
                value="<?= e($guru['nama'] ?? '') ?>"
                required
            >

        </div>


        <!-- EMAIL -->
        <div class="col-12 col-md-6">

            <label class="form-label">
                Email
            </label>

            <input
                type="email"
                class="form-control"
                name="email"
                value="<?= e($guru['email'] ?? '') ?>"
                required
            >

        </div>


        <!-- Password baru bersifat opsional -->
        <div class="col-12 col-md-6">
            <label class="form-label">
                <?= $perlu_buat_akun ? 'Password Akun Guru' : 'Password Baru' ?>
            </label>
            <input
                type="password"
                class="form-control"
                name="password"
                minlength="8"
                autocomplete="new-password"
                <?= $perlu_buat_akun ? 'required' : '' ?>
            >
            <small class="text-muted">
                <?= $perlu_buat_akun ? 'Buat password minimal 8 karakter untuk akun login guru.' : 'Kosongkan jika password tidak ingin diubah.' ?>
            </small>
        </div>

        <!-- Konfirmasi password baru -->
        <div class="col-12 col-md-6">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input
                type="password"
                class="form-control"
                name="konfirmasi_password"
                minlength="8"
                autocomplete="new-password"
                <?= $perlu_buat_akun ? 'required' : '' ?>
            >
        </div>


        <!-- STATUS AKTIF -->
        <div class="col-12 col-md-6">

            <label class="form-label">
                Status Aktif
            </label>

            <select
                name="status_aktif"
                class="form-select"
                required
            >

                <option
                    value="1"
                    <?= ((int) $guru['status_aktif'] === 1) ? 'selected' : '' ?>
                >
                    Aktif
                </option>

                <option
                    value="0"
                    <?= ((int) $guru['status_aktif'] === 0) ? 'selected' : '' ?>
                >
                    Tidak Aktif
                </option>

            </select>

        </div>


        <!-- TOMBOL AKSI -->
        <div class="col-12">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Perubahan
            </button>

            <a
                href="kelola_guru.php?embed=1"
                class="btn btn-secondary"
            >
                Batal
            </a>

        </div>

    </form>

</div>


<?php if (!$embed): ?>

</body>
</html>

<?php endif; ?>