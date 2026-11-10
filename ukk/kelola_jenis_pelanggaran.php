<?php
/**
 * ============================================================
 * KELOLA JENIS PELANGGARAN (MASTER DATA)
 * ============================================================
 * Halaman ini dapat dibuka oleh administrator
 * (dijaga oleh includes/cek_session.php -> includes/cek_akses.php).
 */

require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');

$sql = "
    SELECT
        p.id,
        p.kode,
        p.nama,
        p.poin,
        p.deskripsi,
        p.status_aktif,
        k.nama AS nama_kategori
    FROM t_pelanggaran p
    LEFT JOIN t_pelanggaran_kategori k
        ON k.id = p.pelanggaran_kategori_id
    ORDER BY p.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data jenis pelanggaran: ' .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<?php if (!$embed): ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jenis Pelanggaran</title>

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

    <h2 class="fw-bold mb-3">Kelola Jenis Pelanggaran</h2>
    <hr>

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

    <p class="text-secondary">
        Daftar jenis pelanggaran beserta kategori dan poin yang digunakan
        pada pencatatan pelanggaran siswa.
    </p>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>

        <a href="tambah_jenis_pelanggaran.php<?= $embed ? '?embed=1' : '' ?>"
           class="btn btn-primary">
            + Tambah Jenis Pelanggaran
        </a>
    </div>

    <div class="table-responsive">
        <table id="tabelJenisPelanggaran"
               class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Kategori</th>
                    <th>Nama Pelanggaran</th>
                    <th>Poin</th>
                    <th>Deskripsi</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($data = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($data['kode']) ?></td>
                        <td><?= e($data['nama_kategori'] ?? '-') ?></td>
                        <td><?= e($data['nama']) ?></td>

                        <td>
                            <span class="badge text-bg-primary">
                                <?= (int) $data['poin'] ?>
                            </span>
                        </td>

                        <td><?= e($data['deskripsi']) ?></td>

                        <td>
                            <?php if ((int) $data['status_aktif'] === 1): ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-primary"
                                   href="edit_pelanggaran.php?id=<?= (int) $data['id'] ?><?= $embed ? '&embed=1' : '' ?>">
                                    Edit
                                </a>

                                <form method="post"
                                      action="proses_pelanggaran.php"
                                      onsubmit="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?= (int) $data['id'] ?>">
                                    <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada data jenis pelanggaran.
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
    const tabel = document.getElementById('tabelJenisPelanggaran');

    if (!tabel || typeof DataTable === 'undefined' || DataTable.isDataTable(tabel)) {
        return;
    }

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
                    targets: 0,
                    orderable: false,
                    searchable: false
                },
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
                emptyTable: 'Belum ada data jenis pelanggaran',
                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
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
        // Gunakan tautan dashboard biasa jika tidak berada dalam iframe.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>
