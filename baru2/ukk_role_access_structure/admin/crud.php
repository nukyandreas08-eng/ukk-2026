<?php
require '../includes/auth.php';
require '../config.php';
requireRole(['admin']);

$allowed = [
    't_siswa' => 'Kelola Siswa',
    't_guru' => 'Kelola Guru',
    't_kelas' => 'Kelola Kelas',
    't_tahun_ajaran' => 'Kelola Tahun Ajaran',
    't_kelas_siswa' => 'Penempatan Siswa',
    't_wali_kelas' => 'Kelola Wali Kelas',
    't_pelanggaran_kategori' => 'Kategori Pelanggaran',
    't_pelanggaran' => 'Jenis Pelanggaran',
    't_pelanggaran_siswa' => 'Data Pelanggaran'
];

$table = $_GET['table'] ?? $_POST['table'] ?? '';
if (!isset($allowed[$table])) {
    http_response_code(404);
    exit('Tabel tidak diizinkan.');
}

$title = $allowed[$table];
$section = 'admin';

function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function labelField(string $name): string {
    $labels = [
        'nisn'=>'NISN','nama'=>'Nama','jenis_kelamin'=>'Jenis Kelamin','status_aktif'=>'Status Aktif',
        'nis'=>'NIS','email'=>'Email','alamat'=>'Alamat','tingkat'=>'Tingkat','jurusan'=>'Jurusan',
        'tanggal_mulai'=>'Tanggal Mulai','tanggal_selesai'=>'Tanggal Selesai','siswa_id'=>'ID Siswa',
        'kelas_id'=>'ID Kelas','tahun_ajaran_id'=>'ID Tahun Ajaran','guru_id'=>'ID Guru','kode'=>'Kode',
        'poin'=>'Poin','deksripsi'=>'Deskripsi','deskripsi'=>'Deskripsi','pelanggaran_id'=>'ID Pelanggaran',
        'pelanggaran_kategori_id'=>'ID Kategori','nama_siswa'=>'Nama Siswa','nama_kelas'=>'Nama Kelas',
        'nama_pelanggaran'=>'Nama Pelanggaran','nama_guru'=>'Nama Guru','tanggal'=>'Tanggal',
        'keterangan'=>'Keterangan','tindakan'=>'Tindakan','status'=>'Status','id_user'=>'ID User',
        'tahun_ajaran'=>'Tahun Ajaran','remember_token'=>'Remember Token','password'=>'Password','role'=>'Role'
    ];
    return $labels[$name] ?? ucwords(str_replace('_', ' ', $name));
}

$columns = $pdo->query("SHOW FULL COLUMNS FROM `$table`")->fetchAll();
$columnMap = [];
foreach ($columns as $column) $columnMap[$column['Field']] = $column;

$idColumn = isset($columnMap['id']) ? 'id' : $columns[0]['Field'];
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

$editableColumns = array_values(array_filter($columns, function($c) {
    if ($c['Field'] === 'id') return false;
    if (($c['Extra'] ?? '') === 'auto_increment') return false;
    if (in_array($c['Field'], ['created_at','updated_at'], true) && stripos((string)$c['Default'], 'CURRENT_TIMESTAMP') !== false) return false;
    return true;
}));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['crud_action'] ?? '';
    try {
        if ($postAction === 'delete') {
            $deleteId = (int)($_POST['id'] ?? 0);
            if ($deleteId <= 0) {
                throw new Exception('ID data tidak valid.');
            }
            $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$idColumn` = ?");
            $stmt->execute([$deleteId]);
            header('Location: crud.php?table=' . urlencode($table) . '&deleted=1');
            exit;
        }

        $data = [];
        foreach ($editableColumns as $c) {
            $field = $c['Field'];
            if (($c['Extra'] ?? '') === 'DEFAULT_GENERATED' && !array_key_exists($field, $_POST)) continue;
            if (($c['Type'] ?? '') === 'tinyint(1)' || ($c['Type'] ?? '') === 'tinyint(1) unsigned') {
                $data[$field] = isset($_POST[$field]) ? 1 : 0;
            } else {
                $value = $_POST[$field] ?? null;
                if (is_string($value)) $value = trim($value);
                if ($value === '') $value = null;
                $data[$field] = $value;
            }
        }

        if ($postAction === 'insert') {
            $fields = array_keys($data);
            $placeholders = implode(',', array_fill(0, count($fields), '?'));
            $sql = "INSERT INTO `$table` (" . implode(',', array_map(fn($f) => "`$f`", $fields)) . ") VALUES ($placeholders)";
            $pdo->prepare($sql)->execute(array_values($data));
            header('Location: crud.php?table=' . urlencode($table));
            exit;
        }

        if ($postAction === 'update') {
            $updateId = (int)($_POST['id'] ?? 0);
            $sets = [];
            foreach (array_keys($data) as $field) $sets[] = "`$field` = ?";
            $params = array_values($data);
            $params[] = $updateId;
            $sql = "UPDATE `$table` SET " . implode(',', $sets) . " WHERE `$idColumn` = ?";
            $pdo->prepare($sql)->execute($params);
            header('Location: crud.php?table=' . urlencode($table));
            exit;
        }
    } catch (Throwable $e) {
        if ($postAction === 'delete') {
            $action = 'list';
            if ($e instanceof PDOException && (string)$e->getCode() === '23000') {
                $error = 'Data tidak bisa dihapus karena masih digunakan oleh data lain. Hapus atau ubah data yang terkait terlebih dahulu.';
            } else {
                $error = 'Data gagal dihapus: ' . $e->getMessage();
            }
        } else {
            $error = $e->getMessage();
            $action = $postAction === 'update' ? 'edit' : 'add';
            $id = (int)($_POST['id'] ?? 0);
        }
    }
}

$record = [];
if ($action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$idColumn` = ? LIMIT 1");
    $stmt->execute([$id]);
    $record = $stmt->fetch() ?: [];
    if (!$record) $action = 'list';
}

include '../includes/header.php';
?>
<h1><?= h($title) ?></h1>
<?php if ($error): ?><p><strong>Gagal:</strong> <?= h($error) ?></p><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><p>Data berhasil dihapus.</p><?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
<p><a href="crud.php?table=<?= urlencode($table) ?>">← Kembali ke data</a></p>
<form method="post">
    <input type="hidden" name="table" value="<?= h($table) ?>">
    <input type="hidden" name="crud_action" value="<?= $action === 'edit' ? 'update' : 'insert' ?>">
    <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?= (int)$id ?>"><?php endif; ?>
    <?php foreach ($editableColumns as $c):
        $field = $c['Field'];
        $type = strtolower($c['Type']);
        $value = $_POST[$field] ?? ($record[$field] ?? '');
        $required = ($c['Null'] === 'NO' && $c['Default'] === null && ($c['Extra'] ?? '') !== 'auto_increment');
        ?>
        <p>
            <label><strong><?= h(labelField($field)) ?></strong></label><br>
            <?php if (preg_match('/^enum\(/', $type)):
                preg_match_all("/'([^']*)'/", $type, $matches);
            ?>
                <select name="<?= h($field) ?>" <?= $required ? 'required' : '' ?>>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($matches[1] as $option): ?><option value="<?= h($option) ?>" <?= (string)$value === (string)$option ? 'selected' : '' ?>><?= h($option) ?></option><?php endforeach; ?>
                </select>
            <?php elseif (strpos($type, 'tinyint(1)') === 0): ?>
                <input type="checkbox" name="<?= h($field) ?>" value="1" <?= (string)$value === '1' ? 'checked' : '' ?>> Aktif / Ya
            <?php elseif (strpos($type, 'text') !== false): ?>
                <textarea name="<?= h($field) ?>" rows="4" <?= $required ? 'required' : '' ?>><?= h($value) ?></textarea>
            <?php elseif (strpos($type, 'date') === 0 && strpos($type, 'datetime') !== 0): ?>
                <input type="date" name="<?= h($field) ?>" value="<?= h($value) ?>" <?= $required ? 'required' : '' ?>>
            <?php elseif (strpos($type, 'datetime') === 0 || strpos($type, 'timestamp') === 0): ?>
                <input type="datetime-local" name="<?= h($field) ?>" value="<?= h($value ? date('Y-m-d\\TH:i', strtotime($value)) : '') ?>" <?= $required ? 'required' : '' ?>>
            <?php elseif (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false || strpos($type, 'float') !== false): ?>
                <input type="number" name="<?= h($field) ?>" value="<?= h($value) ?>" <?= $required ? 'required' : '' ?>>
            <?php elseif (strpos($field, 'email') !== false): ?>
                <input type="email" name="<?= h($field) ?>" value="<?= h($value) ?>" <?= $required ? 'required' : '' ?>>
            <?php else: ?>
                <input type="text" name="<?= h($field) ?>" value="<?= h($value) ?>" <?= $required ? 'required' : '' ?>>
            <?php endif; ?>
        </p>
    <?php endforeach; ?>
    <button type="submit"><?= $action === 'edit' ? 'Update Data' : 'Simpan Data' ?></button>
</form>

<?php else: ?>
<p><a href="crud.php?table=<?= urlencode($table) ?>&action=add">+ Tambah Data</a></p>
<table border="1" cellpadding="6" cellspacing="0">
<tr>
    <th>No</th>
    <?php foreach ($columns as $c): ?><th><?= h(labelField($c['Field'])) ?></th><?php endforeach; ?>
    <th>Aksi</th>
</tr>
<?php $rows = $pdo->query("SELECT * FROM `$table` ORDER BY `$idColumn` DESC")->fetchAll(); foreach ($rows as $i => $row): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <?php foreach ($columns as $c): $field = $c['Field']; ?><td><?= h($row[$field] ?? '') ?></td><?php endforeach; ?>
    <td>
        <a href="crud.php?table=<?= urlencode($table) ?>&action=edit&id=<?= (int)$row[$idColumn] ?>">Edit</a>
        <form method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
            <input type="hidden" name="table" value="<?= h($table) ?>">
            <input type="hidden" name="crud_action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$row[$idColumn] ?>">
            <button type="submit">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
