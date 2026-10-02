<?php
require '../includes/auth.php';
requireRole(['admin']);
header('Location: crud.php?table=t_pelanggaran_kategori');
exit;
?>
