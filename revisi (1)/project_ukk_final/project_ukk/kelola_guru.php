<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? '') === '1');

$sql = "SELECT
            t_guru.*,
            t_users.email AS email_user
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        ORDER BY t_guru.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
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
    <title>Kelola Guru</title>

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

<main class="container-fluid p-3 p-md-4 bg-white">

    <h1 class="fw-bold mb-3">Kelola Guru</h1>
    <hr>

    <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($_SESSION['pesan']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <!-- FORM TAMBAH GURU -->
    <h5 class="fw-bold mb-3">Tambah Data Guru</h5>

    <form action="proses_guru.php" method="POST" class="mb-4">
        <input type="hidden" name="aksi" value="tambah">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <tbody>
                    <tr>
                        <td style="width:20%">NIP</td>
                        <td style="width:2%">:</td>
                        <td>
                            <input type="text" name="nip"
                                   class="form-control" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Nama Guru</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="nama"
                                   class="form-control" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>
                            <input type="email" name="email"
                                   class="form-control" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Password</td>
                        <td>:</td>
                        <td>
                            <input type="password" name="password"
                                   class="form-control" minlength="8"
                                   autocomplete="new-password" required>
                            <small class="text-muted">Minimal 8 karakter.</small>
                        </td>
                    </tr>

                    <tr>
                        <td>Konfirmasi Password</td>
                        <td>:</td>
                        <td>
                            <input type="password" name="konfirmasi_password"
                                   class="form-control" minlength="8"
                                   autocomplete="new-password" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Status Aktif</td>
                        <td>:</td>
                        <td>
                            <select name="status_aktif"
                                    class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <button type="submit" class="btn btn-primary">
                                + Tambah Guru
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <hr>

    <!-- TABEL DATA GURU -->
    <h5 class="fw-bold mb-3">Data Guru</h5>

    <div class="table-responsive">
        <table id="tabelGuru"
               class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($guru = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= (int)$guru['id'] ?></td>

                            <td><?= e($guru['nip'] ?? '') ?></td>

                            <td><?= e($guru['nama'] ?? '') ?></td>

                            <td>
                                <?= e(
                                    $guru['email_user']
                                    ?? $guru['email']
                                    ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <?php if ((int)($guru['status_aktif'] ?? 0) === 1): ?>
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
                                    <a href="edit_guru.php?id=<?= (int)$guru['id'] ?>&amp;embed=1"
                                       class="btn btn-primary btn-sm">
                                        Edit
                                    </a>

                                    <a href="proses_guru.php?aksi=hapus&amp;id=<?= (int)$guru['id'] ?>&amp;embed=1"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data guru.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- KEMBALI KE DASHBOARD -->
    <a href="dashboard.php?embed=1"
       class="btn btn-secondary mt-2"
       onclick="return kembaliDashboard(event)">
        Kembali ke Dashboard
    </a>

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
(function () {
    function aktifkanDataTables() {
        var tabel = document.getElementById('tabelGuru');

        if (!tabel || typeof DataTable === 'undefined') {
            return;
        }

        if (DataTable.isDataTable(tabel)) {
            return;
        }

        // Lewati jika tabel hanya berisi pesan data kosong.
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
            order: [[0, 'desc']],
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
                emptyTable: 'Belum ada data guru',

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
        // Gunakan tautan biasa jika dashboard tidak dapat diakses.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>