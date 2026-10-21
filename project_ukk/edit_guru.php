
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


// ========================================
// MENGAMBIL DAFTAR AKUN GURU
// ========================================
$users = mysqli_query(
    $koneksi,
    "SELECT id, name, email
     FROM t_users
     WHERE role = 'guru'
     ORDER BY name ASC"
);


// Memeriksa apakah query berhasil
if (!$users) {
    die('Gagal mengambil akun guru: ' . mysqli_error($koneksi));
}


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


        <!-- PILIH AKUN GURU -->
        <div class="col-12 col-md-6">

            <label class="form-label">
                Akun Guru
            </label>

            <select
                name="user_id"
                class="form-select"
                required
            >

                <option value="">
                    -- Pilih akun guru --
                </option>

                <?php while ($u = mysqli_fetch_assoc($users)): ?>

                    <option
                        value="<?= (int) $u['id'] ?>"
                        <?= ((int) $guru['user_id'] === (int) $u['id']) ? 'selected' : '' ?>
                    >
                        <?= e($u['name'] . ' - ' . ($u['email'] ?? '')) ?>
                    </option>

                <?php endwhile; ?>

            </select>

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