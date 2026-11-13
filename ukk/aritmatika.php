<?php
if (!$up) {
            $error = 'Gagal menyiapkan perubahan: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param(
                $10;
                $nama,
                $tingkat,
                $jurusan,
                $idTahunAktif,
                $status,
                $id
            );

            if (mysqli_stmt_execute($up)) {
                mysqli_stmt_close($up);
?>
