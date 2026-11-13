<?php
/**
 * Koneksi database tunggal untuk seluruh aplikasi.
 *
 * File ini aman di-require berkali-kali (require_once maupun include)
 * karena hanya satu koneksi yang dibuat dan dibagikan lewat $GLOBALS.
 */

if (!isset($GLOBALS['koneksi']) || !($GLOBALS['koneksi'] instanceof mysqli)) {

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'ukk_2026';

    $koneksi = mysqli_connect($host, $user, $password, $database);

    if (!$koneksi) {
        die('Koneksi database gagal: ' . mysqli_connect_error());
    }

    mysqli_set_charset($koneksi, "utf8mb4");

    $GLOBALS['koneksi'] = $koneksi;
}

// Pastikan variabel lokal pada file pemanggil ikut terisi.
$koneksi = $GLOBALS['koneksi'];
