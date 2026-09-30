<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /ukk_role_access_structure/index.php');
    exit;
}
function requireRole(array $roles): void {
    $role = strtolower($_SESSION['role'] ?? '');
    if (!in_array($role, $roles, true)) {
        header('Location: ../pages/forbidden.php');
        exit;
    }
}
