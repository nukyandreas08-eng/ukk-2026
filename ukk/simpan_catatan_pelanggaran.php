<?php
session_start();require_once 'includes/cek_session.php';require_once 'config/koneksi.php';$embed=(($_POST['embed']??'')==='1');function backCat($e){header('Location: catatan_pelanggaran.php'.($e?'?embed=1':''));exit;}if($_SERVER['REQUEST_METHOD']!=='POST')backCat($embed);
$sid=(int)($_POST['siswa_id']??0);$pid=(int)($_POST['pelanggaran_id']??0);$tanggal=trim($_POST['tanggal']??'');$ket=trim($_POST['keterangan']??'');$tindakan='';if(!$sid||!$pid||!$tanggal||!$ket){$_SESSION['pesan']='Siswa, jenis pelanggaran, tanggal, dan keterangan wajib diisi.';backCat($embed);}
$st=mysqli_prepare($koneksi,"SELECT s.id,s.nama,ks.tahun_ajaran_id,ks.kelas_id,k.nama nama_kelas FROM t_siswa s LEFT JOIN t_kelas_siswa ks ON ks.siswa_id=s.id AND ks.status_aktif=1 LEFT JOIN t_kelas k ON k.id=ks.kelas_id WHERE s.id=? AND s.status_aktif=1 ORDER BY ks.id DESC LIMIT 1");mysqli_stmt_bind_param($st,'i',$sid);mysqli_stmt_execute($st);$s=mysqli_fetch_assoc(mysqli_stmt_get_result($st));mysqli_stmt_close($st);
// Validasi sisi server: wali kelas tidak boleh mengirim catatan untuk kelas lain.
if ($s && hak_role_efektif() === HAK_WALI_KELAS) {
    $uid = (int)($_SESSION['id'] ?? 0);
    $cekWali = mysqli_prepare($koneksi, "SELECT 1 FROM t_wali_kelas wk INNER JOIN t_guru g ON g.id=wk.guru_id WHERE g.user_id=? AND wk.status_aktif=1 AND wk.kelas_id=? AND (wk.tahun_ajaran_id=? OR wk.tahun_ajaran_id IS NULL) LIMIT 1");
    $tahunSiswa = (int)($s['tahun_ajaran_id'] ?? 0);
    $kelasSiswa = (int)($s['kelas_id'] ?? 0);
    mysqli_stmt_bind_param($cekWali, 'iii', $uid, $kelasSiswa, $tahunSiswa);
    mysqli_stmt_execute($cekWali);
    $bolehCatat = mysqli_num_rows(mysqli_stmt_get_result($cekWali)) > 0;
    mysqli_stmt_close($cekWali);
    if (!$bolehCatat) {
        $_SESSION['pesan'] = 'Anda hanya dapat mencatat pelanggaran siswa di kelas perwalian Anda.';
        backCat($embed);
    }
}
$st=mysqli_prepare($koneksi,"SELECT id,nama,poin,pelanggaran_kategori_id FROM t_pelanggaran WHERE id=? AND status_aktif=1 LIMIT 1");mysqli_stmt_bind_param($st,'i',$pid);mysqli_stmt_execute($st);$p=mysqli_fetch_assoc(mysqli_stmt_get_result($st));mysqli_stmt_close($st);if(!$s||!$p){$_SESSION['pesan']='Siswa atau jenis pelanggaran tidak ditemukan / tidak aktif.';backCat($embed);}
$guruId=null;$namaGuru=trim($_SESSION['name']??'Guru');$uid=(int)($_SESSION['id']??0);$st=mysqli_prepare($koneksi,"SELECT id,nama FROM t_guru WHERE user_id=? AND status_aktif=1 LIMIT 1");mysqli_stmt_bind_param($st,'i',$uid);mysqli_stmt_execute($st);$g=mysqli_fetch_assoc(mysqli_stmt_get_result($st));mysqli_stmt_close($st);if($g){$guruId=(int)$g['id'];if($g['nama'])$namaGuru=$g['nama'];} elseif (strtolower($_SESSION['role'] ?? '') === 'guru') { $_SESSION['pesan']='Akun guru belum terhubung dengan data guru. Hubungi administrator.'; backCat($embed); }
$tahun=isset($s['tahun_ajaran_id'])?(int)$s['tahun_ajaran_id']:null;$kelas=isset($s['kelas_id'])?(int)$s['kelas_id']:null;$namaKelas=$s['nama_kelas']?:'Belum ditempatkan';$namaSiswa=$s['nama'];$namaPelanggaran=$p['nama'];$kategori=(int)$p['pelanggaran_kategori_id'];$poin=(int)$p['poin'];$status='Belum ditindaklanjuti';
$st=mysqli_prepare($koneksi,"INSERT INTO t_pelanggaran_siswa (tahun_ajaran_id,siswa_id,nama_siswa,kelas_id,nama_kelas,pelanggaran_id,nama_pelanggaran,pelanggaran_kategori_id,guru_id,nama_guru,tanggal,keterangan,poin,tindakan,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");mysqli_stmt_bind_param($st,'iisisisiisssiss',$tahun,$sid,$namaSiswa,$kelas,$namaKelas,$pid,$namaPelanggaran,$kategori,$guruId,$namaGuru,$tanggal,$ket,$poin,$tindakan,$status);if(mysqli_stmt_execute($st))$_SESSION['pesan']='Pelanggaran siswa berhasil dicatat.';else $_SESSION['pesan']='Gagal menyimpan pelanggaran: '.mysqli_stmt_error($st);mysqli_stmt_close($st);backCat($embed);
