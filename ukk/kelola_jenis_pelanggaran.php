<?php
$embed = (($_GET['embed'] ?? '') === '1');
header('Location: catatan_pelanggaran.php' . ($embed ? '?embed=1' : ''));
exit;
