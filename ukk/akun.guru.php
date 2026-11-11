<?php
/**
 * ============================================================
 * KELOLA AKUN GURU
 * ============================================================
 * Halaman ini hanya dapat dibuka oleh administrator
 * (dijaga oleh includes/cek_session.php -> includes/cek_akses.php).
 *
 * Fungsi: membuat akun login untuk guru yang belum memiliki akun
 * dan menampilkan daftar guru yang akunnya sudah tersedia.
 */

require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

$embed = (($_GET['embed'] ?? $_POST['embed'] ?? '') === '1');

$hasil = [];
$error = '';

// ========================================
// MEMBUAT AKUN UNTUK SATU GURU
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'buat_akun') {

    $guru_id = (int) ($_POST['guru_id'] ?? 0);

    if ($guru_id <= 0) {
        $error = 'Data guru tidak valid.';
    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            'SELECT id, nama, email FROM t_guru
             WHERE id = ? AND (user_id IS NULL OR user_id = 0)'
        );

        mysqli_stmt_bind_param($stmt, 'i', $guru_id);
        mysqli_stmt_execute($stmt);

        $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        mysqli_stmt_close($stmt);

        if (!$guru) {
            $error = 'Guru sudah memiliki akun login.';
        } else {

            // Email akun mengikuti email data guru bila tersedia,
            // jika tidak memakai pola otomatis.
            $emailAkun = trim((string) ($guru['email'] ?? ''));

            if ($emailAkun === '' || !filter_var($emailAkun, FILTER_VALID_EMAIL)) {
                $emailAkun = 'guru' . (int) $guru['id'] . '@ukk.local';
            }

            $cekEmail = mysqli_prepare(
                $koneksi,
                'SELECT id FROM t_users WHERE email = ?'
            );

            mysqli_stmt_bind_param($cekEmail, 's', $emailAkun);
            mysqli_stmt_execute($cekEmail);

            $emailTerpakai = mysqli_num_rows(
                mysqli_stmt_get_result($cekEmail)
            ) > 0;

            mysqli_stmt_close($cekEmail);

            if ($emailTerpakai) {
                $error = 'Email akun sudah digunakan: ' . $emailAkun;
            } else {

                $password = 'Guru@2026#' . (int) $guru['id'];
                $hash = password_hash($password, PASSWORD_DEFAULT);

                mysqli_begin_transaction($koneksi);

                $insert = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_users
                    (name, username, email, password, remember_token, role, created_at, updated_at)
                    VALUES (?, ?, ?, ?, '', 'guru', NOW(), NOW())"
                );

                $username = 'guru' . preg_replace('/\D+/', '', (string)$guru['nip']);
                mysqli_stmt_bind_param($insert, 'ssss', $guru['nama'], $username, $emailAkun, $hash);

                if (!mysqli_stmt_execute($insert)) {
                    mysqli_rollback($koneksi);

                    $error = 'Gagal membuat akun: ' . mysqli_stmt_error($insert);

                    mysqli_stmt_close($insert);
                } else {

                    $user_id = mysqli_insert_id($koneksi);

                    $update = mysqli_prepare(
                        $koneksi,
                        'UPDATE t_guru SET user_id = ? WHERE id = ?'
                    );

                    mysqli_stmt_bind_param($update, 'ii', $user_id, $guru_id);

                    if (!mysqli_stmt_execute($update)) {
                        mysqli_rollback($koneksi);

                        $error = 'Gagal menghubungkan akun dengan data guru: ' .
                            mysqli_stmt_error($update);

                        mysqli_stmt_close($update);
                    } else {
                        mysqli_commit($koneksi);

                        $hasil[] = [
                            'id' => (int) $guru['id'],
                            'nama' => (string) $guru['nama'],
                            'email' => $emailAkun,
                            'password' => $password,
                        ];

                        mysqli_stmt_close($update);
                    }

                    mysqli_stmt_close($insert);
                }
            }
        }
    }
}

// ========================================
// MENGAMBIL DATA GURU DAN STATUS AKUN
// ========================================
$query = mysqli_query(
    $koneksi,
    "SELECT
        g.id,
        g.nip,
        g.nama,
        g.email,
        g.status_aktif,
        g.user_id,
        u.email AS email_akun
    FROM t_guru g
    LEFT JOIN t_users u ON u.id = g.user_id
    ORDER BY g.nama ASC"
);

if (!$query) {
    die('Gagal mengambil data guru: ' .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

$totalGuru = 0;
$totalBelumAda = 0;

while ($data = mysqli_fetch_assoc($query)) {
    $totalGuru++;

    if (empty($data['email_akun'])) {
        $totalBelumAda++;
    }
}

mysqli_data_seek($query, 0);

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
    <title>Kelola Akun Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
<?php endif; ?>

<div class="container-fluid p-3 p-md-4 bg-white">

    <h2 class="fw-bold mb-3">Kelola Akun Guru</h2>
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

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <?php if (count($hasil) > 0): ?>
        <div class="alert alert-success" role="alert">
            Berhasil membuat <?= count($hasil) ?> akun guru.
            Simpan daftar akun dan password di bawah ini.
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th>ID Guru</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($hasil as $akun): ?>
                        <tr>
                            <td><?= (int) $akun['id'] ?></td>
                            <td><?= e($akun['nama']) ?></td>
                            <td><?= e($akun['email']) ?></td>
                            <td><code><?= e($akun['password']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary">Total Guru</div>
                    <div class="display-6 fw-semibold text-primary">
                        <?= (int) $totalGuru ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-secondary">Belum Punya Akun</div>
                    <div class="display-6 fw-semibold text-warning">
                        <?= (int) $totalBelumAda ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="dashboard.php?embed=1"
           class="btn btn-secondary"
           onclick="return kembaliDashboard(event)">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle w-100">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Email Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = 1;

                while ($data = mysqli_fetch_assoc($query)):
                    $punyaAkun = !empty($data['email_akun']);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($data['nip']) ?></td>
                        <td><?= e($data['nama']) ?></td>

                        <td>
                            <?php if ($punyaAkun): ?>
                                <span class="badge text-bg-success">
                                    <?= e($data['email_akun']) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Belum ada akun</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!$punyaAkun): ?>
                                <form method="post"
                                      action="akun.guru.php"
                                      onsubmit="return confirm('Yakin ingin membuat akun login untuk guru ini?')">
                                    <input type="hidden" name="aksi" value="buat_akun">
                                    <input type="hidden" name="guru_id" value="<?= (int) $data['id'] ?>">
                                    <input type="hidden" name="embed" value="<?= $embed ? '1' : '0' ?>">
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        Buat Akun
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>

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
