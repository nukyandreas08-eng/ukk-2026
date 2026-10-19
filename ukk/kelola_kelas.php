<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

$query = mysqli_query($koneksi, "
    SELECT * FROM t_kelas
    ORDER BY id DESC
");

if (!$query) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
      rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h1 class="fw-bold mb-2">Kelola Kelas</h1>
    <hr>

    <!-- PESAN NOTIFIKASI -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php $pesan = $_GET['pesan']; ?>

        <?php if ($pesan === 'edit_berhasil' || $pesan === 'hapus_berhasil'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Data kelas berhasil
                <?= $pesan === 'edit_berhasil' ? 'diperbarui' : 'dihapus' ?>.
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>

        <?php elseif ($pesan === 'data_tidak_ditemukan'): ?>
            <div class="alert alert-warning">
                Data kelas tidak ditemukan.
            </div>

        <?php elseif ($pesan === 'hapus_gagal'): ?>
            <div class="alert alert-danger">
                Data kelas gagal dihapus. Periksa relasi database.
            </div>

        <?php elseif ($pesan === 'id_tidak_valid'): ?>
            <div class="alert alert-warning">
                ID kelas tidak valid.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <p>Halaman untuk mengelola data kelas.</p>

    <!-- TOMBOL NAVIGASI -->
    <div class="d-flex flex-wrap gap-2 mb-3">

        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <a href="tambah_kelas.php<?= $embed ? '?embed=1' : '' ?>"
           class="btn btn-primary">
            + Tambah Kelas
        </a>

    </div>

    <!-- TABEL DATA KELAS -->
    <div class="table-responsive">
        <table id="tabelKelas"
               class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($kelas = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars(
                                (string)$kelas['nama'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                (string)$kelas['tingkat'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                (string)$kelas['jurusan'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?php if ((int)$kelas['status_aktif'] === 1): ?>
                                <span class="badge text-bg-success">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">
                                    Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="edit_kelas.php?id=<?= (int)$kelas['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_kelas.php?id=<?= (int)$kelas['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-4">
                            Belum ada data kelas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

<!-- JAVASCRIPT DATATABLES -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
(function () {
    function aktifkanDataTables() {
        var tabel = document.getElementById('tabelKelas');

        if (!tabel || typeof DataTable === 'undefined') {
            return;
        }

        if (DataTable.isDataTable(tabel)) {
            return;
        }

        // Hindari inisialisasi pada tabel yang hanya berisi
        // pesan "Belum ada data kelas".
        if (
            tabel.tBodies.length &&
            tabel.tBodies[0].rows.length === 1 &&
            tabel.tBodies[0].rows[0].cells.length === 1
        ) {
            return;
        }

        new DataTable(tabel, {
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [],
            scrollX: true,

            columnDefs: [
                {
                    targets: 5,
                    orderable: false,
                    searchable: false
                }
            ],

            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data kelas',

                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', aktifkanDataTables);
    } else {
        aktifkanDataTables();
    }
})();

// Kembali ke dashboard tanpa membuka halaman di luar iframe.
function kembaliDashboard(event) {
    try {
        if (window.parent !== window) {
            var parentDoc = window.parent.document;
            var frame = parentDoc.getElementById('contentFrame');

            if (frame) {
                parentDoc.querySelectorAll('.sidebar .nav-link')
                    .forEach(function (link) {
                        link.classList.remove('active');
                    });

                var dashboardLink = parentDoc.querySelector(
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
        // Gunakan tautan biasa jika iframe tidak dapat diakses.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>