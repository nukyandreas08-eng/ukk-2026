<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

// Ambil data pelanggaran dan kategori
$sql = "
    SELECT
        t_pelanggaran.*,
        t_pelanggaran_kategori.nama AS nama_kategori
    FROM t_pelanggaran
    LEFT JOIN t_pelanggaran_kategori
        ON t_pelanggaran.pelanggaran_kategori_id = t_pelanggaran_kategori.id
    ORDER BY t_pelanggaran.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data pelanggaran: " . mysqli_error($koneksi));
}

// Ambil data kategori
$sql_kategori = "
    SELECT *
    FROM t_pelanggaran_kategori
    ORDER BY nama ASC
";

$query_kategori = mysqli_query($koneksi, $sql_kategori);

if (!$query_kategori) {
    die("Gagal mengambil data kategori: " . mysqli_error($koneksi));
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
    <title>Kelola Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
<?php else: ?>
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h1 class="fw-bold mb-3">Kelola Pelanggaran</h1>
    <hr>

    <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= e($_SESSION['pesan']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <h4 class="fw-bold mb-3">Tambah Pelanggaran</h4>

    <form action="proses_pelanggaran.php" method="POST" class="mb-4">
        <input type="hidden" name="aksi" value="tambah">

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="pelanggaran_kategori_id" class="form-label">
                    Kategori
                </label>

                <select id="pelanggaran_kategori_id"
                        name="pelanggaran_kategori_id"
                        class="form-select"
                        required>
                    <option value="">-- Pilih Kategori --</option>

                    <?php while ($kategori = mysqli_fetch_assoc($query_kategori)): ?>
                        <option value="<?= (int) $kategori['id'] ?>">
                            <?= e($kategori['nama']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label for="kode" class="form-label">Kode</label>
                <input type="text"
                       id="kode"
                       name="kode"
                       class="form-control"
                       placeholder="PG-001"
                       required>
            </div>

            <div class="col-12 col-md-6">
                <label for="nama" class="form-label">Nama Pelanggaran</label>
                <input type="text"
                       id="nama"
                       name="nama"
                       class="form-control"
                       required>
            </div>

            <div class="col-12 col-md-6">
                <label for="poin" class="form-label">Poin</label>
                <input type="number"
                       id="poin"
                       name="poin"
                       class="form-control"
                       min="0"
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

            <div class="col-12 col-md-6">
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
                <button type="submit" class="btn btn-primary">
                    + Tambah Pelanggaran
                </button>

                <a href="dashboard.php?embed=1"
                   class="btn btn-secondary"
                   onclick="return kembaliDashboard(event)">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </form>

    <hr>

    <h4 class="fw-bold mb-3">Data Pelanggaran</h4>

    <div class="table-responsive">
        <table id="tabelPelanggaran"
               class="table table-bordered table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Kode</th>
                    <th>Nama Pelanggaran</th>
                    <th>Poin</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0):
                    while ($pelanggaran = mysqli_fetch_assoc($query)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($pelanggaran['nama_kategori'] ?? '-') ?></td>
                        <td><?= e($pelanggaran['kode'] ?? '') ?></td>
                        <td><?= e($pelanggaran['nama'] ?? '') ?></td>
                        <td><?= e($pelanggaran['poin'] ?? 0) ?></td>
                        <td><?= e($pelanggaran['deskripsi'] ?? '') ?></td>
                        <td>
                            <?php if ((int) $pelanggaran['status_aktif'] === 1): ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-primary"
                                   href="edit_pelanggaran.php?id=<?= (int) $pelanggaran['id'] ?><?= $embed ? '&embed=1' : '' ?>">
                                    Edit
                                </a>

                                <form method="post" action="proses_pelanggaran.php" onsubmit="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?= (int)$pelanggaran['id'] ?>">
                                    <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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
                            Belum ada data pelanggaran.
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
    const tabel = document.getElementById('tabelPelanggaran');

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
                    emptyTable: 'Belum ada data pelanggaran',
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
        // Gunakan tautan dashboard biasa jika tidak berada dalam iframe.
    }

    return true;
}
</script>

<?php if (!$embed): ?>
</body>
</html>
<?php endif; ?>