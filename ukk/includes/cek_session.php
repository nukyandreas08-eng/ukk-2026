<?php
/**
 * ============================================================
 * PEMERIKSAAN SESI LOGIN + HAK AKSES
 * ============================================================
 * File ini dipakai oleh seluruh halaman yang membutuhkan login.
 * Selain memastikan pengguna sudah login, file ini juga menerapkan
 * hak akses sehingga menu sidebar dan halaman yang dapat dibuka
 * selalu konsisten.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}

// Terapkan hak akses halaman (dibaca dari includes/cek_akses.php).
require_once __DIR__ . '/cek_akses.php';

hak_cek_permintaan();
