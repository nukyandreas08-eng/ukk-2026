<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? '') === '1');

$sql = "SELECT id, nama, tingkat, jurusan, status_aktif
        FROM t_kelas
        ORDER BY id DESC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data kelas: ' .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

function e_kelas($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<?php if (!$embed): ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">
    <h2 class="fw-bold mb-3">Kelola Kelas</h2>
    <hr>

    <?php
    $pesan = $_GET['pesan'] ?? '';
    $notifikasi = [
        'tambah_berhasil' => ['success', 'Data kelas berhasil ditambahkan.'],
        'edit_berhasil' => ['success', 'Data kelas berhasil diperbarui.'],
        'hapus_berhasil' => ['success', 'Data kelas berhasil dihapus.'],
        'data_tidak_ditemukan' => ['warning', 'Data kelas tidak ditemukan.'],
        'hapus_gagal' => ['danger', 'Data kelas gagal dihapus karena masih digunakan data lain.'],
        'id_tidak_valid' => ['warning', 'ID kelas tidak valid.']
    ];

    if (isset($notifikasi[$pesan])):
        [$jenis, $isi] = $notifikasi[$pesan];
    ?>
        <div class="alert alert-<?= $jenis ?>">
            <?= e_kelas($isi) ?>
        </div>
    <?php endif; ?>

    <p>Kelola data kelas sekolah.</p>

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
            while ($row = mysqli_fetch_assoc($query)):
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e_kelas($row['nama']) ?></td>
                    <td><?= e_kelas($row['tingkat']) ?></td>
                    <td><?= e_kelas($row['jurusan']) ?></td>
                    <td>
                        <?php if ((int)$row['status_aktif'] === 1): ?>
                            <span class="badge text-bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a class="btn btn-sm btn-primary"
                               href="edit_kelas.php?id=<?= (int)$row['id'] ?><?= $embed ? '&embed=1' : '' ?>">
                                Edit
                            </a>
                            <a class="btn btn-sm btn-danger"
                               href="hapus_kelas.php?id=<?= (int)$row['id'] ?><?= $embed ? '&embed=1' : '' ?>"
                               onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
(function () {
    const init = () => {
        const tabel = document.getElementById('tabelKelas');

        if (tabel && typeof DataTable !== 'undefined' &&
            !DataTable.isDataTable(tabel)) {
            new DataTable(tabel, {
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],
                scrollX: true,
                columnDefs: [{
                    targets: 5,
                    orderable: false,
                    searchable: false
                }],
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
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

function kembaliDashboard(event) {
    try {
        if (window.parent !== window) {
            const doc = window.parent.document;
            const frame = doc.getElementById('contentFrame');

            if (frame) {
                doc.querySelectorAll('.sidebar .nav-link')
                    .forEach(link => link.classList.remove('active'));

                const dashboardLink = doc.querySelector(
                    '.sidebar .nav-link[href*="dashboard"]'
                );

                if (dashboardLink) dashboardLink.classList.add('active');

                frame.src = 'dashboard.php?embed=1';
                event.preventDefault();
                return false;
            }
        }
    } catch (error) {}

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
