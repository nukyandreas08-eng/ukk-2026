<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        a { display: block; width: 220px; padding: 12px; margin: 8px 0;
            background: #eee; color: #222; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <h2>Dashboard</h2>
    <p>Login sebagai: <b><?= htmlspecialchars($role) ?></b></p>

    <?php if ($role === 'admin' || $role === 'ADMIN'): ?>
        <h3>Menu Admin</h3>
        <a href="menu1.php">Menu 1</a>
        <a href="menu2.php">Menu 2</a>
        <a href="menu3.php">Menu 3</a>
        <a href="menu4.php">Menu 4</a>
    <?php elseif ($role === 'guru' || $role === 'GURU'): ?>
        <h3>Menu Guru</h3>
        <a href="menu3.php">Menu 3</a>
        <a href="menu4.php">Menu 4</a>
    <?php endif; ?>
</body>
</html>
