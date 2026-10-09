<?php
session_start();
require 'config.php';
if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id,name,email,password,role FROM t_user WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['name'] = $u['name'];
            $_SESSION['role'] = strtolower($u['role']);
            header('Location: dashboard.php'); exit;
        }
        $error = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Login - Justice Admin</title></head>
<body>
<br><br><br>
<table>
<tr><td><h1>LOGIN</h1><p>Justice Admin - UKK 2026</p></td></tr>
<tr><td><?php if ($error): ?><p><strong><?= htmlspecialchars($error) ?></strong></p><?php endif; ?>
<form method="post">
<p><label>Email</label><br><input type="email" name="email" size="42" required></p>
<p><label>Password</label><br><input type="password" name="password" size="42" required></p>
<p><button type="submit">Login</button></p>
</form></td></tr>
</table>
</body></html>
