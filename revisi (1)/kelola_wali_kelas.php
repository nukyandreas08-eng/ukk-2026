<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

$sql = "
    SELECT
        wk.id,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        g.nama AS nama_guru,
        wk.tanggal_mulai,
        wk.tanggal_selesai,
        wk.status_aktif
    FROM t_wali_kelas wk
    LEFT JOIN t_tahun_ajaran ta
        ON wk.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k
        ON wk.kelas_id = k.id
    LEFT JOIN t_guru g
        ON wk.guru_id = g.id
    ORDER BY wk.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data wali kelas: " . mysqli_error($koneksi));
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Kelola Wali Kelas</h2>
    <hr>

    <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($_SESSION['pesan']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <p>Halaman pengelolaan data wali kelas sekolah.</p>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <a href="tambah_wali_kelas.php<?= $embed ? '?embed=1' : '' ?>"
           class="btn btn-primary">
            + Tambah Wali Kelas
        </a>
    </div>

    <div class="table-responsive">
        <table id="tabelWaliKelas"
               class="table table-bordered table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Kelas</th>
                    <th>Nama Guru</th>
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
                        <td><?= e($row['tahun_ajaran'] ?? '-') ?></td>
                        <td><?= e($row['nama_kelas'] ?? '-') ?></td>
                        <td><?= e($row['nama_guru'] ?? '-') ?></td>
                        <td><?= e($row['tanggal_mulai'] ?? '-') ?></td>
                        <td><?= e($row['tanggal_selesai'] ?? '-') ?></td>
                        <td>
                            <?php if ((int) $row['status_aktif'] === 1): ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-primary"
                                   href="edit_wali_kelas.php?id=<?= (int) $row['id'] ?><?= $embed ? '&embed=1' : '' ?>">
                                    Edit
                                </a>

                                <a class="btn btn-sm btn-danger"
                                   href="hapus_wali_kelas.php?id=<?= (int) $row['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                                   onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')">
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
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada data wali kelas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabelWaliKelas');

    if (
        tabel &&
        typeof DataTable !== 'undefined' &&
        !DataTable.isDataTable(tabel)
    ) {
        const baris = tabel.querySelectorAll('tbody tr');
        const pesanKosong = baris.length === 1 &&
            baris[0].querySelectorAll('td').length === 1;

        if (!pesanKosong) {
            new DataTable(tabel, {
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],
                scrollX: true,
                columnDefs: [
                    {
                        targets: 7,
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Data tidak ditemukan',
                    emptyTable: 'Belum ada data wali kelas',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });
        }
    }
});

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
        // Jika tidak berada di dalam iframe, lanjutkan ke halaman dashboard.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>