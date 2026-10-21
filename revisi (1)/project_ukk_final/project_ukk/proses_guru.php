<?php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

// ========================================
// PROSES TAMBAH GURU DAN AKUN LOGIN OTOMATIS
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'tambah') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $status_aktif = filter_var(
        $_POST['status_aktif'] ?? null,
        FILTER_VALIDATE_INT
    );

    // Memeriksa kelengkapan dan kebenaran data formulir.
    if (
        $nip === '' ||
        $nama === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($password) < 8 ||
        $password !== $konfirmasi_password ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        if ($password !== $konfirmasi_password) {
            $_SESSION['pesan'] = 'Password dan konfirmasi password tidak sama.';
        } elseif (strlen($password) < 8) {
            $_SESSION['pesan'] = 'Password minimal 8 karakter.';
        } else {
            $_SESSION['pesan'] = 'Semua data wajib diisi dengan benar.';
        }

        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    // Memastikan NIP belum digunakan guru lain.
    $cek_nip = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_guru WHERE nip = ?'
    );
    mysqli_stmt_bind_param($cek_nip, 's', $nip);
    mysqli_stmt_execute($cek_nip);
    $nip_dipakai = mysqli_num_rows(mysqli_stmt_get_result($cek_nip)) > 0;
    mysqli_stmt_close($cek_nip);

    if ($nip_dipakai) {
        $_SESSION['pesan'] = 'NIP sudah digunakan guru lain.';
        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    // Email harus unik pada akun login dan data guru.
    $cek_email = mysqli_prepare(
        $koneksi,
        'SELECT id FROM t_users WHERE email = ?
         UNION
         SELECT id FROM t_guru WHERE email = ?'
    );
    mysqli_stmt_bind_param($cek_email, 'ss', $email, $email);
    mysqli_stmt_execute($cek_email);
    $email_dipakai = mysqli_num_rows(mysqli_stmt_get_result($cek_email)) > 0;
    mysqli_stmt_close($cek_email);

    if ($email_dipakai) {
        $_SESSION['pesan'] = 'Email sudah digunakan. Gunakan email yang berbeda.';
        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    // Password tidak disimpan sebagai teks biasa.
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Akun dan data guru disimpan dalam satu transaksi.
    mysqli_begin_transaction($koneksi);

    // Membuat akun login dengan role guru secara otomatis.
    $buat_akun = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_users
            (name, email, password, remember_token, role, created_at, updated_at)
         VALUES (?, ?, ?, '', 'guru', NOW(), NOW())"
    );

    mysqli_stmt_bind_param(
        $buat_akun,
        'sss',
        $nama,
        $email,
        $password_hash
    );

    if (!mysqli_stmt_execute($buat_akun)) {
        mysqli_rollback($koneksi);
        $_SESSION['pesan'] = 'Gagal membuat akun guru: ' . mysqli_stmt_error($buat_akun);
        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    // Mengambil ID akun yang baru dibuat.
    $user_id = mysqli_insert_id($koneksi);

    // Menghubungkan data guru dengan akun login yang baru dibuat.
    $buat_guru = mysqli_prepare(
        $koneksi,
        'INSERT INTO t_guru
            (nip, nama, email, status_aktif, user_id, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
    );

    mysqli_stmt_bind_param(
        $buat_guru,
        'sssii',
        $nip,
        $nama,
        $email,
        $status_aktif,
        $user_id
    );

    if (mysqli_stmt_execute($buat_guru)) {
        mysqli_commit($koneksi);
        $_SESSION['pesan'] = 'Data guru dan akun login berhasil dibuat.';
    } else {
        mysqli_rollback($koneksi);
        $_SESSION['pesan'] = 'Gagal menyimpan data guru: ' . mysqli_stmt_error($buat_guru);
    }

    header('Location: kelola_guru.php?embed=1');
    exit;
}


// ========================================
// PROSES HAPUS GURU BESERTA AKUN LOGIN
// ========================================
if (($_GET['aksi'] ?? '') === 'hapus') {
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        $_SESSION['pesan'] = 'ID guru tidak valid.';
        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    // Mengambil ID akun yang terhubung dengan guru.
    $ambil = mysqli_prepare(
        $koneksi,
        'SELECT user_id FROM t_guru WHERE id = ?'
    );
    mysqli_stmt_bind_param($ambil, 'i', $id);
    mysqli_stmt_execute($ambil);
    $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($ambil));
    mysqli_stmt_close($ambil);

    if (!$guru) {
        $_SESSION['pesan'] = 'Data guru tidak ditemukan.';
        header('Location: kelola_guru.php?embed=1');
        exit;
    }

    mysqli_begin_transaction($koneksi);

    // Menghapus data guru terlebih dahulu.
    $hapus_guru = mysqli_prepare(
        $koneksi,
        'DELETE FROM t_guru WHERE id = ?'
    );
    mysqli_stmt_bind_param($hapus_guru, 'i', $id);
    $berhasil_hapus_guru = mysqli_stmt_execute($hapus_guru);

    // Menghapus akun login yang terkait supaya tidak tertinggal.
    $berhasil_hapus_akun = true;
    $user_id = (int) ($guru['user_id'] ?? 0);

    if ($berhasil_hapus_guru && $user_id > 0) {
        $hapus_akun = mysqli_prepare(
            $koneksi,
            "DELETE FROM t_users WHERE id = ? AND role = 'guru'"
        );
        mysqli_stmt_bind_param($hapus_akun, 'i', $user_id);
        $berhasil_hapus_akun = mysqli_stmt_execute($hapus_akun);
        mysqli_stmt_close($hapus_akun);
    }

    if ($berhasil_hapus_guru && $berhasil_hapus_akun) {
        mysqli_commit($koneksi);
        $_SESSION['pesan'] = 'Data guru dan akun login berhasil dihapus.';
    } else {
        mysqli_rollback($koneksi);
        $_SESSION['pesan'] = 'Gagal menghapus data guru atau akun login.';
    }

    mysqli_stmt_close($hapus_guru);
    header('Location: kelola_guru.php?embed=1');
    exit;
}

// Jika aksi tidak dikenali, kembali ke halaman kelola guru.
$_SESSION['pesan'] = 'Aksi tidak dikenali.';
header('Location: kelola_guru.php?embed=1');
exit;
