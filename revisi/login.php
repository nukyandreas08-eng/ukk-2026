<?php
session_start();
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background-color: #249ccf;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 266px;
            background-color: #f9f8f8;
            padding: 20px;
            border-radius: 25px;
        }

        .brand-title {
            text-align: center;
            color: #1f3b58;
            font-size: 19px;
            font-weight: 700;
            line-height: 1.25;
            margin: 8px 0 20px;
        }

        .brand-title span {
            display: block;
            color: #249ccf;
            font-size: 17px;
            font-weight: 600;
        }

        .form-label {
            font-size: 12px;
            margin: 0 0 1px 5px;
            color: #111;
        }

        .form-control {
            height: 40px;
            border: none;
            border-radius: 11px;
            background-color: #e2e2e2;
            font-size: 13px;
            margin-bottom: 0;
        }

        .form-control:focus {
            background-color: #e2e2e2;
            box-shadow: 0 0 0 2px #0dcaf0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .btn-login {
            width: 88%;
            height: 32px;
            display: block;
            margin: 14px auto 5px;
            border: none;
            border-radius: 11px;
            background-color: #08c9e9;
            color: white;
            font-family: Georgia, serif;
            font-size: 18px;
        }

        .btn-login:hover {
            background-color: #00b8d6;
            color: white;
        }

        .pesan-error {
            font-size: 12px;
            text-align: center;
            color: red;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- Nama aplikasi sebagai pengganti logo -->
        <div class="brand-title">
            Sistem Informasi
            <span>Pelanggaran Siswa</span>
        </div>

        <!-- Pesan error -->
        <?php if (isset($_SESSION['pesan_error'])) : ?>
            <div class="pesan-error">
                <?= htmlspecialchars($_SESSION['pesan_error']) ?>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="proses_login.php" method="POST">

            <div class="form-group">
                <label for="username" class="form-label">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control"
                    placeholder="Username atau email"
                    required
                    autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password">
            </div>

            <button type="submit" name="login" value="Login"
                    class="btn btn-login">
                Login
            </button>

        </form>

    </div>

</body>
</html>
