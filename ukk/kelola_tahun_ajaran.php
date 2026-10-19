<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

$query = mysqli_query($koneksi, "
    SELECT * FROM t_tahun_ajaran
    ORDER BY id DESC
");

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Tahun Ajaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>
<body class="bg-white">
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
      rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Kelola Tahun Ajaran</h2>
    <hr>

    <!-- PESAN NOTIFIKASI -->
    <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($_SESSION['pesan']) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>

        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <p>Halaman pengelolaan data tahun ajaran sekolah.</p>

    <!-- TOMBOL NAVIGASI -->
    <div class="d-flex flex-wrap gap-2 mb-3">

        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <a href="tambah_tahun_ajaran.php<?= $embed ? '?embed=1' : '' ?>"
           class="btn btn-primary">
            + Tambah Tahun Ajaran
        </a>

    </div>

    <!-- TABEL DATA TAHUN AJARAN -->
    <div class="table-responsive">
        <table id="tabelTahunAjaran"
               class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td><?= e($row['nama']) ?></td>

                        <td><?= e($row['tanggal_mulai']) ?></td>

                        <td><?= e($row['tanggal_selesai']) ?></td>

                        <td>
                            <?php if ((int)$row['status_aktif'] === 1): ?>
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
                                <a href="edit_tahun_ajaran.php?id=<?= (int)$row['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_tahun_ajaran.php?id=<?= (int)$row['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
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
                            Belum ada data tahun ajaran.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
(function () {
    function aktifkanDataTables() {
        var tabel = document.getElementById('tabelTahunAjaran');

        if (!tabel || typeof DataTable === 'undefined') {
            return;
        }

        if (DataTable.isDataTable(tabel)) {
            return;
        }

        // Hindari inisialisasi ketika tabel hanya berisi pesan kosong.
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
                emptyTable: 'Belum ada data tahun ajaran',

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

// Kembali ke dashboard melalui iframe.
function kembaliDashboard(event) {
    try {
        if (window.parent !== window) {
            var parentDoc = window.parent.document;
            var frame = parentDoc.getElementById('contentFrame');

            if (frame) {
                parentDoc.querySelectorAll('.sidebar .nav-link')
                    .forEach(function(link) {
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