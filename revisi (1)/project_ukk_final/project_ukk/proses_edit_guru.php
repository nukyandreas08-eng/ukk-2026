<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

// Form edit harus dikirim menggunakan POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_guru.php?embed=1');
    exit;
}

// Mengambil data dari formulir.
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
$status_aktif = filter_var($_POST['status_aktif'] ?? null, FILTER_VALIDATE_INT);
$ubah_password = ($password !== '' || $konfirmasi_password !== '');

if (
    !$id || $id < 1 ||
    $nip === '' ||
    $nama === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !in_array($status_aktif, [0, 1], true) ||
    ($ubah_password && (
        $password === '' ||
        $konfirmasi_password === '' ||
        $password !== $konfirmasi_password ||
        strlen($password) < 8
    ))
) {
    if ($ubah_password && $password !== $konfirmasi_password) {
        $_SESSION['pesan'] = 'Password dan konfirmasi password tidak sama.';
    } elseif ($ubah_password && strlen($password) < 8) {
        $_SESSION['pesan'] = 'Password baru minimal 8 karakter.';
    } else {
        $_SESSION['pesan'] = 'Data guru tidak valid.';
    }

    header('Location: edit_guru.php?id=' . (int) $id . '&embed=1');
    exit;
}

// Mengambil akun login yang terhubung dengan data guru.
$stmt = mysqli_prepare(
    $koneksi,
    'SELECT id, user_id FROM t_guru WHERE id = ?'
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$guru) {
    $_SESSION['pesan'] = 'Data guru tidak ditemukan.';
    header('Location: kelola_guru.php?embed=1');
    exit;
}

$user_id = (int) ($guru['user_id'] ?? 0);

// Guru yang belum punya akun wajib mengisi password.
if ($user_id < 1 && !$ubah_password) {
    $_SESSION['pesan'] = 'Guru ini belum memiliki akun. Silakan isi password dan konfirmasi password.';
    header('Location: edit_guru.php?id=' . $id . '&embed=1');
    exit;
}

// Memeriksa NIP agar tidak digunakan guru lain.
$cek_nip = mysqli_prepare(
    $koneksi,
    'SELECT id FROM t_guru WHERE nip = ? AND id <> ?'
);
mysqli_stmt_bind_param($cek_nip, 'si', $nip, $id);
mysqli_stmt_execute($cek_nip);
$nip_dipakai = mysqli_num_rows(mysqli_stmt_get_result($cek_nip)) > 0;
mysqli_stmt_close($cek_nip);

if ($nip_dipakai) {
    $_SESSION['pesan'] = 'NIP sudah digunakan guru lain.';
    header('Location: edit_guru.php?id=' . $id . '&embed=1');
    exit;
}

// Memastikan email belum dipakai akun lain.
if ($user_id > 0) {
    $cek_email = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_users WHERE email = ? AND id <> ?'
    );
    mysqli_stmt_bind_param($cek_email, 'si', $email, $user_id);
} else {
    $cek_email = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_users WHERE email = ?'
    );
    mysqli_stmt_bind_param($cek_email, 's', $email);
}
mysqli_stmt_execute($cek_email);
$email_akun_dipakai = mysqli_num_rows(mysqli_stmt_get_result($cek_email)) > 0;
mysqli_stmt_close($cek_email);

$cek_email_guru = mysqli_prepare(
    $koneksi,
    'SELECT id FROM t_guru WHERE email = ? AND id <> ?'
);
mysqli_stmt_bind_param($cek_email_guru, 'si', $email, $id);
mysqli_stmt_execute($cek_email_guru);
$email_guru_dipakai = mysqli_num_rows(mysqli_stmt_get_result($cek_email_guru)) > 0;
mysqli_stmt_close($cek_email_guru);

if ($email_akun_dipakai || $email_guru_dipakai) {
    $_SESSION['pesan'] = 'Email sudah digunakan. Gunakan email yang berbeda.';
    header('Location: edit_guru.php?id=' . $id . '&embed=1');
    exit;
}

mysqli_begin_transaction($koneksi);

// Jika akun belum ada, buat akun login guru secara otomatis.
if ($user_id < 1) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $buat_akun = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_users
            (name, email, password, remember_token, role, created_at, updated_at)
         VALUES (?, ?, ?, '', 'guru', NOW(), NOW())"
    );
    mysqli_stmt_bind_param($buat_akun, 'sss', $nama, $email, $password_hash);

    if (!mysqli_stmt_execute($buat_akun)) {
        mysqli_rollback($koneksi);
        $_SESSION['pesan'] = 'Gagal membuat akun guru: ' . mysqli_stmt_error($buat_akun);
        header('Location: edit_guru.php?id=' . $id . '&embed=1');
        exit;
    }

    $user_id = mysqli_insert_id($koneksi);
}

// Memperbarui data guru termasuk relasi akun login.
$update_guru = mysqli_prepare(
    $koneksi,
    'UPDATE t_guru
     SET nip = ?, nama = ?, email = ?, user_id = ?, status_aktif = ?, updated_at = NOW()
     WHERE id = ?'
);
mysqli_stmt_bind_param(
    $update_guru,
    'sssiii',
    $nip,
    $nama,
    $email,
    $user_id,
    $status_aktif,
    $id
);
$guru_berhasil = mysqli_stmt_execute($update_guru);

// Sinkronkan nama dan email akun dengan data guru.
if ($guru_berhasil) {
    if ($ubah_password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_akun = mysqli_prepare(
            $koneksi,
            "UPDATE t_users
             SET name = ?, email = ?, password = ?, updated_at = NOW()
             WHERE id = ? AND role = 'guru'"
        );
        mysqli_stmt_bind_param(
            $update_akun,
            'sssi',
            $nama,
            $email,
            $password_hash,
            $user_id
        );
    } else {
        $update_akun = mysqli_prepare(
            $koneksi,
            "UPDATE t_users
             SET name = ?, email = ?, updated_at = NOW()
             WHERE id = ? AND role = 'guru'"
        );
        mysqli_stmt_bind_param($update_akun, 'ssi', $nama, $email, $user_id);
    }

    $akun_berhasil = mysqli_stmt_execute($update_akun);
} else {
    $akun_berhasil = false;
}

if ($guru_berhasil && $akun_berhasil) {
    mysqli_commit($koneksi);
    $_SESSION['pesan'] = 'Data guru dan akun login berhasil diperbarui.';
} else {
    mysqli_rollback($koneksi);
    $_SESSION['pesan'] = 'Gagal memperbarui data guru atau akun login.';
}

header('Location: kelola_guru.php?embed=1');
exit;
