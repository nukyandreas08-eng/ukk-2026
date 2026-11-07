<?php
require_once __DIR__ . '/cek_session.php';

$roleAkses = strtolower(trim((string)($_SESSION['role'] ?? '')));
if ($roleAkses !== 'guru') {
    http_response_code(403);
    echo '<!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Akses Ditolak</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <main class="container py-5">
            <div class="card border-0 shadow-sm mx-auto" style="max-width:520px">
                <div class="card-body text-center p-4">
                    <h3 class="text-danger fw-bold">403 - Akses Ditolak</h3>
                    <p class="text-secondary">Fitur ini hanya dapat diakses oleh akun Guru.</p>
                    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
                </div>
            </div>
        </main>
    </body>
    </html>';
    exit;
}
