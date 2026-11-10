<?php
/**
 * ============================================================
 * TAMBAH JENIS PELANGGARAN (MASTER DATA)
 * ============================================================
 * Halaman ini dapat dibuka oleh administrator
 * (dijaga oleh includes/cek_session.php -> includes/cek_akses.php).
 */

require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kategori_id = (int) ($_POST['pelanggaran_kategori_id'] ?? 0);
    $kode = trim($_POST['kode'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $poin = (int) ($_POST['poin'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $kategori_id <= 0 ||
        $kode === '' ||
        $nama === '' ||
        $poin < 0 ||
        $deskripsi === '' ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        $error = 'Semua kolom wajib diisi dengan benar.';
    } else {

        // Pastikan kategori yang dipilih benar-benar tersedia.
        $cekKategori = mysqli_prepare(
            $koneksi,
            'SELECT id FROM t_pelanggaran_kategori WHERE id = ?'
        );

        mysqli_stmt_bind_param($cekKategori, 'i', $kategori_id);
        mysqli_stmt_execute($cekKategori);

        $kategoriValid = mysqli_num_rows(
            mysqli_stmt_get_result($cekKategori)
        ) > 0;

        mysqli_stmt_close($cekKategori);

        if (!$kategoriValid) {
            $error = 'Kategori pelanggaran yang dipilih tidak tersedia.';
        } else {

            // Kode jenis pelanggaran harus unik.
            $cekKode = mysqli_prepare(
                $koneksi,
                'SELECT id FROM t_pelanggaran WHERE kode = ?'
            );

            mysqli_stmt_bind_param($cekKode, 's', $kode);
            mysqli_stmt_execute($cekKode);

            $kodeTerpakai = mysqli_num_rows(
                mysqli_stmt_get_result($cekKode)
            ) > 0;

            mysqli_stmt_close($cekKode);

            if ($kodeTerpakai) {
                $error = 'Kode pelanggaran sudah digunakan. Gunakan kode lain.';
            } else {

                $stmt = mysqli_prepare(
                    $koneksi,
                    'INSERT INTO t_pelanggaran
                    (pelanggaran_kategori_id, kode, nama, poin, deskripsi, status_aktif)
                    VALUES (?, ?, ?, ?, ?, ?)'
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    'issisi',
                    $kategori_id,
                    $kode,
                    $nama,
                    $poin,
                    $deskripsi,
                    $status_aktif
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);

                    $_SESSION['pesan'] = 'Jenis pelanggaran berhasil ditambahkan.';

                    header('Location: kelola_jenis_pelanggaran.php' .
                        ($embed ? '?embed=1' : ''));
                    exit;
                }

                $error = 'Gagal menyimpan jenis pelanggaran: ' .
                    mysqli_stmt_error($stmt);

                mysqli_stmt_close($stmt);
            }
        }
    }
}

$queryKategori = mysqli_query(
    $koneksi,
    'SELECT id, nama FROM t_pelanggaran_kategori ORDER BY nama ASC'
);

if (!$queryKategori) {
    die('Gagal mengambil data kategori pelanggaran: ' .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}
?>

<?php if (!$embed): ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Tambah Jenis Pelanggaran</h2>
    <hr>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <a href="kelola_jenis_pelanggaran.php<?= $embed ? '?embed=1' : '' ?>"
           class="btn btn-outline-secondary">
            Batal
        </a>
    </div>

    <form method="post"
          action="tambah_jenis_pelanggaran.php"
          class="row g-3">

        <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">

        <div class="col-md-6">
            <label for="pelanggaran_kategori_id" class="form-label">
                Kategori Pelanggaran
            </label>

            <select id="pelanggaran_kategori_id"
                    name="pelanggaran_kategori_id"
                    class="form-select"
                    required>

                <option value="">-- Pilih Kategori --</option>

                <?php while ($kategori = mysqli_fetch_assoc($queryKategori)): ?>
                    <option value="<?= (int) $kategori['id'] ?>">
                        <?= htmlspecialchars($kategori['nama'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label for="kode" class="form-label">Kode</label>

            <input id="kode"
                   name="kode"
                   type="text"
                   class="form-control"
                   maxlength="30"
                   required>
        </div>

        <div class="col-md-3">
            <label for="poin" class="form-label">Poin</label>

            <input id="poin"
                   name="poin"
                   type="number"
                   min="0"
                   class="form-control"
                   value="0"
                   required>
        </div>

        <div class="col-12">
            <label for="nama" class="form-label">Nama Pelanggaran</label>

            <input id="nama"
                   name="nama"
                   type="text"
                   class="form-control"
                   maxlength="150"
                   required>
        </div>

        <div class="col-12">
            <label for="deskripsi" class="form-label">Deskripsi</label>

            <textarea id="deskripsi"
                      name="deskripsi"
                      class="form-control"
                      rows="3"
                      required></textarea>
        </div>

        <div class="col-12">
            <label for="status_aktif" class="form-label">Status Aktif</label>

            <select id="status_aktif"
                    name="status_aktif"
                    class="form-select"
                    required>

                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">
                Simpan Jenis Pelanggaran
            </button>
        </div>

    </form>

</div>

<script>
function kembaliDashboard(event) {
    try {
        if (window.parent !== window) {
            const frame = window.parent.document.getElementById('contentFrame');

            if (frame) {
                window.parent.document
                    .querySelectorAll('.sidebar .nav-link')
                    .forEach(function (link) {
                        link.classList.remove('active');
                    });

                const dashboardLink = window.parent.document.querySelector(
                    '.sidebar .nav-link[href*="dashboard"]'
                );

                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }

                frame.src = 'dashboard.php?embed=1';
                event.preventDefault();
                return false;
            }
        }
    } catch (error) {
        // Gunakan tautan dashboard biasa jika tidak berada dalam iframe.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
