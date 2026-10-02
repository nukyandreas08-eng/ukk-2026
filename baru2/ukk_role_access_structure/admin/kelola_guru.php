<?php
require '../includes/auth.php';
requireRole(['admin']);
header('Location: crud.php?table=t_guru');
exit;
?>
