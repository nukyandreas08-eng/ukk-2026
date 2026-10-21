
<?php
// ========================================
// 1. MENGHUBUNGKAN SESSION DAN DATABASE
// ========================================

// Memeriksa apakah pengguna sudah login
require_once __DIR__ . '/includes/cek_session.php';

// Menghubungkan website ke database
require_once __DIR__ . '/config/koneksi.php';


// ========================================
// 2. MENGAMBIL ID SISWA DAN MODE HALAMAN
// ========================================

// Memeriksa apakah halaman dibuka melalui iframe
$embed = (($_GET['embed'] ?? '') === '1');

// Mengambil dan memvalidasi ID siswa dari URL
$id = filter_var(
    $_GET['id'] ?? null,
    FILTER_VALIDATE_INT
);


// Jika ID tidak valid, kembali ke halaman data siswa
if (!$id || $id < 1) {
    $_SESSION['pesan'] = 'ID siswa tidak valid.';

    header('Location: kelola_siswa.php?embed=1');
    exit;
}


// ========================================
// 3. MENGAMBIL DATA SISWA DARI DATABASE
// ========================================

// Menyiapkan query untuk mencari siswa berdasarkan ID
$stmt = mysqli_prepare(
    $koneksi,
    'SELECT * FROM t_siswa WHERE id = ?'
);

// Memasukkan ID siswa ke dalam query
mysqli_stmt_bind_param($stmt, 'i', $id);

// Menjalankan query
mysqli_stmt_execute($stmt);

// Mengambil data siswa
$siswa = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

// Menutup statement
mysqli_stmt_close($stmt);


// Jika data siswa tidak ditemukan
if (!$siswa) {
    $_SESSION['pesan'] = 'Data siswa tidak ditemukan.';

    header('Location: kelola_siswa.php?embed=1');
    exit;
}


// ========================================
// 4. FUNGSI UNTUK MENGAMANKAN TEKS
// ========================================

// Mengamankan teks sebelum ditampilkan pada HTML
function e($v)
{
    return htmlspecialchars(
        (string) $v,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<?php
// ========================================
// 5. MEMBUKA HTML JIKA BUKAN MODE IFRAME
// ========================================

// Jika halaman dibuka secara langsung,
// tampilkan struktur HTML lengkap
if (!$embed):
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Edit Data Siswa</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>
<?php endif; ?>


<!-- ========================================
     6. TAMPILAN FORM EDIT DATA SISWA
========================================= -->

<div class="container-fluid p-3 p-md-4 bg-white">

    <!-- Judul halaman -->
    <h2 class="fw-bold">Edit Data Siswa</h2>

    <hr>


    <!-- Menampilkan pesan informasi jika tersedia -->
    <?php if (!empty($_SESSION['pesan'])): ?>

        <div class="alert alert-info">
            <?= e($_SESSION['pesan']) ?>
        </div>

        <?php
        // Menghapus pesan setelah ditampilkan
        unset($_SESSION['pesan']);
        ?>

    <?php endif; ?>


    <!-- Formulir untuk mengubah data siswa -->
    <form
        action="proses_edit_siswa.php"
        method="post"
        class="row g-3"
    >

        <!-- Menyimpan ID siswa yang sedang diedit -->
        <input
            type="hidden"
            name="id"
            value="<?=(int) $siswa['id']?>"
        >

        <!-- Menandakan bahwa halaman menggunakan mode iframe -->
        <input
            type="hidden"
            name="embed"
            value="1"
        >


        <!-- ========================================
             INPUT NIS
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="nis">
                NIS
            </label>

            <input
                id="nis"
                class="form-control"
                name="nis"
                maxlength="30"
                value="<?= e($siswa['nis']) ?>"
                required
            >

        </div>


        <!-- ========================================
             INPUT NISN
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="nisn">
                NISN
            </label>

            <input
                id="nisn"
                class="form-control"
                name="nisn"
                maxlength="30"
                value="<?= e($siswa['nisn']) ?>"
                required
            >

        </div>


        <!-- ========================================
             INPUT NAMA SISWA
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="nama">
                Nama Siswa
            </label>

            <input
                id="nama"
                class="form-control"
                name="nama"
                maxlength="150"
                value="<?= e($siswa['nama']) ?>"
                required
            >

        </div>


        <!-- ========================================
             PILIHAN JENIS KELAMIN
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="jenis_kelamin">
                Jenis Kelamin
            </label>

            <select
                id="jenis_kelamin"
                name="jenis_kelamin"
                class="form-select"
                required
            >

                <option value="">
                    -- Pilih Jenis Kelamin --
                </option>

                <option
                    value="L"
                    <?= $siswa['jenis_kelamin'] === 'L' ? 'selected' : '' ?>
                >
                    Laki-laki
                </option>

                <option
                    value="P"
                    <?= $siswa['jenis_kelamin'] === 'P' ? 'selected' : '' ?>
                >
                    Perempuan
                </option>

            </select>

        </div>


        <!-- ========================================
             INPUT TANGGAL LAHIR
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="tanggal_lahir">
                Tanggal Lahir
            </label>

            <input
                id="tanggal_lahir"
                type="date"
                class="form-control"
                name="tanggal_lahir"
                value="<?= e($siswa['tanggal_lahir']) ?>"
                required
            >

        </div>


        <!-- ========================================
             PILIHAN STATUS AKTIF
        ========================================= -->

        <div class="col-12 col-md-6">

            <label class="form-label" for="status_aktif">
                Status Aktif
            </label>

            <select
                id="status_aktif"
                name="status_aktif"
                class="form-select"
                required
            >

                <option
                    value="1"
                    <?=(int) $siswa['status_aktif'] === 1 ? 'selected' : ''?>
                >
                    Aktif
                </option>

                <option
                    value="0"
                    <?=(int) $siswa['status_aktif'] === 0 ? 'selected' : ''?>
                >
                    Tidak Aktif
                </option>

            </select>

        </div>


        <!-- ========================================
             INPUT ALAMAT
        ========================================= -->

        <div class="col-12">

            <label class="form-label" for="alamat">
                Alamat
            </label>

            <textarea
                id="alamat"
                name="alamat"
                class="form-control"
                rows="2"
                required
            ><?= e($siswa['alamat']) ?></textarea>

        </div>


        <!-- ========================================
             TOMBOL SIMPAN DAN BATAL
        ========================================= -->

        <div class="col-12">

            <!-- Mengirim data ke proses_edit_siswa.php -->
            <button
                class="btn btn-primary"
                type="submit"
            >
                Simpan Perubahan
            </button>

            <!-- Kembali ke halaman kelola siswa -->
            <a
                class="btn btn-secondary"
                href="kelola_siswa.php?embed=1"
            >
                Batal
            </a>

        </div>

    </form>

</div>


<?php
// ========================================
// 7. MENUTUP HTML JIKA BUKAN MODE IFRAME
// ========================================

// Bootstrap JavaScript dan penutup HTML
// hanya ditampilkan jika halaman dibuka langsung
if (!$embed):
?>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>

<?php endif; ?>