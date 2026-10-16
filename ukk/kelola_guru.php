
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lanjutkan pemeriksaan login di bawah ini.
// Pertahankan kode pemeriksaan session yang sudah ada.


include 'includes/cek_session.php';
include 'config/koneksi.php';

$embed = isset($_GET['embed']) && $_GET['embed'] === '1';

// Ambil data guru dan email akun
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

// Ambil akun dengan role guru
$sql_user = "SELECT id, name, email
             FROM t_users
             WHERE role = 'guru'
             ORDER BY name ASC";

$query_user = mysqli_query($koneksi, $sql_user);

if (!$query_user) {
    die("Query akun guru gagal: " . mysqli_error($koneksi));
}
?>

<?php if (!$embed): ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Guru</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-white">
<?php endif; ?>

<main class="container-fluid p-3 p-md-4">

    <h1 class="mb-3">Kelola Guru</h1>
    <hr>

    <!-- Pesan notifikasi -->
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['pesan']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <!-- FORM TAMBAH GURU -->
    <h2 class="mb-3">Tambah Data Guru</h2>

    <form action="proses_guru.php" method="POST">
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
                        <td>Akun Guru</td>
                        <td>:</td>
                        <td>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih Akun Guru --</option>

                                <?php while ($user = mysqli_fetch_assoc($query_user)): ?>
                                    <option value="<?= (int) $user['id'] ?>">
                                        <?= htmlspecialchars($user['name']) ?>
                                        - <?= htmlspecialchars($user['email']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
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
                                Tambah Guru
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <hr class="my-4">

    <!-- TABEL DATA GURU -->
    <h2 class="mb-3">Data Guru</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>User ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>

                    <?php while ($guru = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= (int) $guru['id'] ?></td>

                            <td>
                                <?= htmlspecialchars($guru['nip'] ?? '') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($guru['nama'] ?? '') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $guru['email_user']
                                    ?? $guru['email']
                                    ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <?php if (($guru['status_aktif'] ?? 0) == 1): ?>
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
                                <?= htmlspecialchars(
                                    (string) ($guru['user_id'] ?? '-')
                                ) ?>
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="edit_guru.php?id=<?= (int) $guru['id'] ?>&embed=1"
                                       class="btn btn-primary btn-sm">
                                        Edit
                                    </a>

                                    <a href="proses_guru.php?aksi=hapus&id=<?= (int) $guru['id'] ?>&embed=1"
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
                        <td colspan="7" class="text-center">
                            Belum ada data guru.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- KEMBALI KE DASHBOARD -->
    <a href="dashboard.php?embed=1" class="btn btn-secondary mt-2" onclick="if(window.parent!==window){try{var f=window.parent.document.getElementById('contentFrame');if(f){window.parent.document.querySelectorAll('.sidebar .nav-link').forEach(function(a){a.classList.remove('active')});var d=window.parent.document.querySelector('.sidebar .nav-link[href*=\'dashboard\']');if(d)d.classList.add('active');f.src='dashboard.php?embed=1';return false;}}catch(e){}}"
>
        Kembali ke Dashboard
    </a>

</main>

<?php if (!$embed): ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>
