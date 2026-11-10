-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Nov 2026 pada 09.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'NIP20260001', 'Wawan Kurniawan, S.Pd.I', 'WawanKurniawan@gmail.com', 1, 55, '2025-12-31 18:00:00', '2025-12-31 18:00:00'),
(2, 'NIP20260002', 'Ai Nuryaidah, S.Pd', 'AiNuryaidah@gmail.com', 1, 56, '2026-02-01 18:00:00', '2026-02-01 18:00:00'),
(3, 'NIP20260003', 'Maya Permata Endah, S.Pd', 'MayaPermataEndah@gmail.com', 1, 57, '2026-03-02 18:00:00', '2026-03-02 18:00:00'),
(4, 'NIP20260004', 'Dody Jaenudin, S.Pd', 'DodyJaenudin@gmail.com', 1, 58, '2026-04-03 18:00:00', '2026-04-03 18:00:00'),
(5, 'NIP20260005', 'Dedi Kardiman, S.Pd', 'DediKardiman@gmail.com', 1, 59, '2026-05-04 18:00:00', '2026-05-04 18:00:00'),
(6, 'NIP20260006', 'Uca Musa Yusenda, S.Pd', 'UcaMusaYusenda@gmail.com', 1, 60, '2026-06-05 18:00:00', '2026-06-05 18:00:00'),
(7, 'NIP20260007', 'Drs. Ahmad Nur Mukhlis', 'AhmadNurMukhlis@gmail.com', 1, 61, '2026-07-06 18:00:00', '2026-07-06 18:00:00'),
(8, 'NIP20260008', 'Drs. Heri', 'Heri@gmail.com', 1, 62, '2026-08-07 18:00:00', '2026-11-09 19:43:11'),
(9, 'NIP20260009', 'Asep Bastaman', 'AsepBastaman@gmail.com', 1, 63, '2026-09-08 18:00:00', '2026-09-08 18:00:00'),
(10, 'NIP20260010', 'H. Encep Maemun, S.T., M.Pd', 'EncepMaemun@gmail.com', 1, 64, '2026-10-09 18:00:00', '2026-10-09 18:00:00'),
(11, 'NIP20260011', 'Opik', 'Opik@gmail.com', 1, 65, '2026-11-10 18:00:00', '2026-11-10 18:00:00'),
(12, 'NIP20260012', 'Ida Farida, S.Pd', 'IdaFarida@gmail.com', 1, 66, '2026-12-11 18:00:00', '2026-12-11 18:00:00'),
(13, 'NIP20260013', 'Idah Mahmudah, S.Pd', 'IdahMahmudah@gmail.com', 1, 67, '2026-01-12 18:00:00', '2026-01-12 18:00:00'),
(14, 'NIP20260014', 'Drs. Muhamad Ali', 'MuhamadAli@gmail.com', 1, 68, '2026-02-13 18:00:00', '2026-02-13 18:00:00'),
(15, 'NIP20260015', 'Helmi Ali Akbar, S.Pd.I', 'HelmiAliAkbar@gmail.com', 1, 69, '2026-03-14 18:00:00', '2026-03-14 18:00:00'),
(16, 'NIP20260016', 'Syeni Suswarini, S.Pd', 'SyeniSuswarini@gmail.com', 1, 70, '2026-04-15 18:00:00', '2026-04-15 18:00:00'),
(17, 'NIP20260017', 'Wiwid Yulianti, S.Pd', 'WiwidYulianti@gmail.com', 1, 71, '2026-05-16 18:00:00', '2026-05-16 18:00:00'),
(18, 'NIP20260018', 'Ansor Muhajir, S.T', 'AnsorMuhajir@gmail.com', 1, 72, '2026-06-17 18:00:00', '2026-06-17 18:00:00'),
(19, 'NIP20260019', 'Ogi Gaosun Gais, S.Pd.I', 'OgiGaosunGais@gmail.com', 1, 73, '2026-07-18 18:00:00', '2026-07-18 18:00:00'),
(20, 'NIP20260020', 'Sandi Mulyana, S.Pd', 'SandiMulyana@gmail.com', 1, 74, '2026-08-19 18:00:00', '2026-08-19 18:00:00'),
(21, 'NIP20260021', 'Erna Suryani, S.Pd', 'ErnaSuryani@gmail.com', 1, 75, '2026-09-20 18:00:00', '2026-09-20 18:00:00'),
(22, 'NIP20260022', 'Ai Wahidah, S.Ag', 'AiWahidah@gmail.com', 1, 76, '2026-10-21 18:00:00', '2026-10-21 18:00:00'),
(23, 'NIP20260023', 'Chandra Aditya Yusup, S.Pd', 'ChandraAdityaYusup@gmail.com', 1, 77, '2026-11-22 18:00:00', '2026-11-22 18:00:00'),
(24, 'NIP20260024', 'Arif Kusmawan, S.Pd', 'ArifKusmawan@gmail.com', 1, 78, '2026-12-23 18:00:00', '2026-12-23 18:00:00'),
(25, 'NIP20260025', 'Asep Taufiq Hidayat, S.Kom', 'AsepTaufiqHidayat@gmail.com', 1, 79, '2026-01-24 18:00:00', '2026-01-24 18:00:00'),
(26, 'NIP20260026', 'Rendy Ichsan Kusumah, S.T', 'RendyIchsanKusumah@gmail.com', 1, 80, '2026-02-25 18:00:00', '2026-02-25 18:00:00'),
(27, 'NIP20260027', 'Feri Rahmawan, S.Pd', 'FeriRahmawan@gmail.com', 1, 81, '2026-03-26 18:00:00', '2026-03-26 18:00:00'),
(29, 'NIP20260029', 'Yaneu Nurdian, S.Pd, M.Pd', 'YaneuNurdian@gmail.com', 1, 83, '2026-04-30 18:00:00', '2026-04-30 18:00:00'),
(30, 'NIP20260030', 'Ucu Muhammad Khoer N, S.Pd', 'UcuMuhammadKhoerN@gmail.com', 1, 84, '2026-06-01 18:00:00', '2026-06-01 18:00:00'),
(31, 'NIP20260031', 'Mira Dwi Rahmawati, S.Ag', 'MiraDwiRahmawati@gmail.com', 1, 85, '2026-07-02 18:00:00', '2026-07-02 18:00:00'),
(32, 'NIP20260032', 'Aldi Maulana, S.Pd', 'aldimaulana@gmail.com', 1, 3, '2026-08-03 18:00:00', '2026-11-09 20:00:21'),
(33, 'NIP20260033', 'Eli Rosliah, S.Pd', 'EliRosliah@gmail.com', 1, 86, '2026-09-04 18:00:00', '2026-09-04 18:00:00'),
(34, 'NIP20260034', 'Isni Nur Fitriani, S.Pd', 'IsniNurFitriani@gmail.com', 1, 87, '2026-10-05 18:00:00', '2026-10-05 18:00:00'),
(35, 'NIP20260035', 'Aanisah Putri Nur Aini, S.Pd', 'AanisahPutriNurAini@gmail.com', 1, 88, '2026-11-06 18:00:00', '2026-11-06 18:00:00'),
(36, 'NIP20260036', 'Yusi Luthfiani, S.Sos.I', 'YusiLuthfiani@gmail.com', 1, 89, '2026-12-07 18:00:00', '2026-12-07 18:00:00'),
(37, 'NIP20260037', 'Lilis Rustandy Mawardi, S.Pd.,', 'LilisRustandyMawardi@gmail.com', 1, 90, '2026-01-08 18:00:00', '2026-01-08 18:00:00'),
(38, 'NIP20260038', 'Yusrin Noer Sabrina, S.Pd', 'YusrinNoerSabrina@gmail.com', 1, 91, '2026-02-09 18:00:00', '2026-02-09 18:00:00'),
(39, 'NIP20260039', 'Senny Indayani, S.Pd', 'SennyIndayani@gmail.com', 1, 39, '2026-03-10 18:00:00', '2026-03-10 18:00:00'),
(40, 'NIP20260040', 'Ilma Amalia, S.Pd', 'IlmaAmalia@gmail.com', 1, 40, '2026-04-11 18:00:00', '2026-04-11 18:00:00'),
(41, 'NIP20260041', 'Alvira Sofwatunnisa, S.Pd', 'AlviraSofwatunnisa@gmail.com', 1, 41, '2026-05-12 18:00:00', '2026-05-12 18:00:00'),
(42, 'NIP20260042', 'Risna Andriyani, S.Pd', 'RisnaAndriyani@gmail.com', 1, 42, '2026-06-13 18:00:00', '2026-06-13 18:00:00'),
(43, 'NIP20260043', 'Dra. Tintin Noerhayati', 'TintinNoerhayati@gmail.com', 1, 43, '2026-07-14 18:00:00', '2026-07-14 18:00:00'),
(44, 'NIP20260044', 'Rizkia Fahira, S.Pd', 'RizkiaFahira@gmail.com', 1, 44, '2026-08-15 18:00:00', '2026-08-15 18:00:00'),
(45, 'NIP20260045', 'Frasetio', 'Frasetio@gmail.com', 1, 45, '2026-09-16 18:00:00', '2026-09-16 18:00:00'),
(46, 'NIP20260046', 'Ratnaningsih Hatil Aeni, S.Pd', 'RatnaningsihHatilAeni@gmail.com', 1, 46, '2026-10-17 18:00:00', '2026-10-17 18:00:00'),
(47, 'NIP20260047', 'Dheny Darajatul Hasani, S.Pd', 'DhenyDarajatulHasani@gmail.com', 1, 47, '2026-11-18 18:00:00', '2026-11-18 18:00:00'),
(48, 'NIP20260048', 'Fahrullah, S.Pd', 'Fahrullah@gmail.com', 1, 48, '2026-12-19 18:00:00', '2026-12-19 18:00:00'),
(49, 'NIP20260049', 'Aan Andriana, S.T', 'amdmin@gmail.com', 1, 49, '2026-01-20 18:00:00', '2026-11-09 20:13:30'),
(50, 'NIP20260050', 'Fitri Fauziah, S.T', 'FitriFauziah@gmail.com', 1, 50, '2026-02-21 18:00:00', '2026-02-21 18:00:00'),
(51, 'NIP20260051', 'Nida Aulia, S.Pd', 'NidaAulia@gmail.com', 1, 51, '2026-10-21 17:45:15', '2026-10-21 17:45:15'),
(52, 'NIP20260052', 'Sri Nurrizki Putri Krisnadi, S', 'SriNurrizkiPutriKrisnad@gmail.com', 1, 52, '2026-10-21 18:00:17', '2026-10-21 18:00:17'),
(53, 'NIP20260053', 'Riesy Putri Navia, S.Kom', 'RiesyPutriNavia@gmail.com', 1, 0, '2026-11-04 19:15:47', '2026-11-04 19:15:47'),
(54, 'NIP20260054', 'Rurry Febriani Maryadi, S.Pd', 'RurryFebrianiMaryadi@gmail.com', 1, 0, '2026-11-04 19:15:47', '2026-11-04 19:15:47'),
(55, 'NIP20260055', 'Irvan Rizal, S.Pd', 'IrvanRizal@gmail.com', 1, 54, '2026-11-04 19:19:21', '2026-11-04 19:19:21'),
(58, 'NIP1234567', 'ALPA', 'mcok30324@gmail.com', 1, 94, '2026-11-09 20:14:13', '2026-11-09 20:14:13'),
(59, 'NIP12345678', 'Ari Sandriana, S.Kom', 'AriSandriana@gmail.com', 1, 95, '2026-11-09 20:29:17', '2026-11-09 20:29:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `tahun_ajaran_id`, `status_aktif`, `created_at`, `updated_at`) VALUES
(11, 'XII RPL 1', 'XII', 'RPL', 25, 1, '2026-09-27 23:01:44', '2026-11-03 23:17:13'),
(26, 'XII RPL 2', 'XII', 'RPL', 25, 1, '2026-09-27 23:01:44', '2026-09-27 23:01:44'),
(75, 'RPL 1', 'XI', 'RPL', 25, 1, '2026-07-12 18:00:00', '2026-07-12 18:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `kode` varchar(30) DEFAULT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `poin` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'PG-001', 'Terlambat datang ke sekolah #001', 2, 'Deskripsi pelanggaran nomor 1.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 2, 'PG-002', 'Tidak memakai atribut lengkap #002', 3, 'Deskripsi pelanggaran nomor 2.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, 3, 'PG-003', 'Tidak memakai seragam sesuai ketentuan #003', 4, 'Deskripsi pelanggaran nomor 3.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, 4, 'PG-004', 'Meninggalkan kelas tanpa izin #004', 5, 'Deskripsi pelanggaran nomor 4.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, 5, 'PG-005', 'Tidak mengerjakan tugas #005', 6, 'Deskripsi pelanggaran nomor 5.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, 6, 'PG-006', 'Rambut tidak sesuai aturan #006', 7, 'Deskripsi pelanggaran nomor 6.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, 7, 'PG-007', 'Membawa barang terlarang #007', 8, 'Deskripsi pelanggaran nomor 7.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 8, 'PG-008', 'Berkata tidak sopan #008', 9, 'Deskripsi pelanggaran nomor 8.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, 9, 'PG-009', 'Membuang sampah sembarangan #009', 10, 'Deskripsi pelanggaran nomor 9.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, 10, 'PG-010', 'Tidak mengikuti upacara #010', 1, 'Deskripsi pelanggaran nomor 10.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, 11, 'PG-011', 'Terlambat datang ke sekolah #011', 2, 'Deskripsi pelanggaran nomor 11.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, 12, 'PG-012', 'Tidak memakai atribut lengkap #012', 3, 'Deskripsi pelanggaran nomor 12.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, 13, 'PG-013', 'Tidak memakai seragam sesuai ketentuan #013', 4, 'Deskripsi pelanggaran nomor 13.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, 14, 'PG-014', 'Meninggalkan kelas tanpa izin #014', 5, 'Deskripsi pelanggaran nomor 14.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, 15, 'PG-015', 'Tidak mengerjakan tugas #015', 6, 'Deskripsi pelanggaran nomor 15.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, 16, 'PG-016', 'Rambut tidak sesuai aturan #016', 7, 'Deskripsi pelanggaran nomor 16.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, 17, 'PG-017', 'Membawa barang terlarang #017', 8, 'Deskripsi pelanggaran nomor 17.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, 18, 'PG-018', 'Berkata tidak sopan #018', 9, 'Deskripsi pelanggaran nomor 18.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, 19, 'PG-019', 'Membuang sampah sembarangan #019', 10, 'Deskripsi pelanggaran nomor 19.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 20, 'PG-020', 'Tidak mengikuti upacara #020', 1, 'Deskripsi pelanggaran nomor 20.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, 21, 'PG-021', 'Terlambat datang ke sekolah #021', 2, 'Deskripsi pelanggaran nomor 21.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, 22, 'PG-022', 'Tidak memakai atribut lengkap #022', 3, 'Deskripsi pelanggaran nomor 22.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, 23, 'PG-023', 'Tidak memakai seragam sesuai ketentuan #023', 4, 'Deskripsi pelanggaran nomor 23.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, 24, 'PG-024', 'Meninggalkan kelas tanpa izin #024', 5, 'Deskripsi pelanggaran nomor 24.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, 25, 'PG-025', 'Tidak mengerjakan tugas #025', 6, 'Deskripsi pelanggaran nomor 25.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, 26, 'PG-026', 'Rambut tidak sesuai aturan #026', 7, 'Deskripsi pelanggaran nomor 26.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, 27, 'PG-027', 'Membawa barang terlarang #027', 8, 'Deskripsi pelanggaran nomor 27.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, 28, 'PG-028', 'Berkata tidak sopan #028', 9, 'Deskripsi pelanggaran nomor 28.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, 29, 'PG-029', 'Membuang sampah sembarangan #029', 10, 'Deskripsi pelanggaran nomor 29.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, 30, 'PG-030', 'Tidak mengikuti upacara #030', 1, 'Deskripsi pelanggaran nomor 30.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, 31, 'PG-031', 'Terlambat datang ke sekolah #031', 2, 'Deskripsi pelanggaran nomor 31.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, 32, 'PG-032', 'Tidak memakai atribut lengkap #032', 3, 'Deskripsi pelanggaran nomor 32.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 33, 'PG-033', 'Tidak memakai seragam sesuai ketentuan #033', 4, 'Deskripsi pelanggaran nomor 33.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, 34, 'PG-034', 'Meninggalkan kelas tanpa izin #034', 5, 'Deskripsi pelanggaran nomor 34.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, 35, 'PG-035', 'Tidak mengerjakan tugas #035', 6, 'Deskripsi pelanggaran nomor 35.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, 36, 'PG-036', 'Rambut tidak sesuai aturan #036', 7, 'Deskripsi pelanggaran nomor 36.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, 37, 'PG-037', 'Membawa barang terlarang #037', 8, 'Deskripsi pelanggaran nomor 37.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, 38, 'PG-038', 'Berkata tidak sopan #038', 9, 'Deskripsi pelanggaran nomor 38.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, 39, 'PG-039', 'Membuang sampah sembarangan #039', 10, 'Deskripsi pelanggaran nomor 39.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, 40, 'PG-040', 'Tidak mengikuti upacara #040', 1, 'Deskripsi pelanggaran nomor 40.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, 41, 'PG-041', 'Terlambat datang ke sekolah #041', 2, 'Deskripsi pelanggaran nomor 41.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, 42, 'PG-042', 'Tidak memakai atribut lengkap #042', 3, 'Deskripsi pelanggaran nomor 42.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, 43, 'PG-043', 'Tidak memakai seragam sesuai ketentuan #043', 4, 'Deskripsi pelanggaran nomor 43.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, 44, 'PG-044', 'Meninggalkan kelas tanpa izin #044', 5, 'Deskripsi pelanggaran nomor 44.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, 45, 'PG-045', 'Tidak mengerjakan tugas #045', 6, 'Deskripsi pelanggaran nomor 45.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, 46, 'PG-046', 'Rambut tidak sesuai aturan #046', 7, 'Deskripsi pelanggaran nomor 46.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, 47, 'PG-047', 'Membawa barang terlarang #047', 8, 'Deskripsi pelanggaran nomor 47.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, 48, 'PG-048', 'Berkata tidak sopan #048', 9, 'Deskripsi pelanggaran nomor 48.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, 49, 'PG-049', 'Membuang sampah sembarangan #049', 10, 'Deskripsi pelanggaran nomor 49.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, 50, 'PG-050', 'Tidak mengikuti upacara #050', 1, 'Deskripsi pelanggaran nomor 50.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, 51, 'PG-051', 'Terlambat datang ke sekolah #051', 2, 'Deskripsi pelanggaran nomor 51.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, 52, 'PG-052', 'Tidak memakai atribut lengkap #052', 3, 'Deskripsi pelanggaran nomor 52.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, 53, 'PG-053', 'Tidak memakai seragam sesuai ketentuan #053', 4, 'Deskripsi pelanggaran nomor 53.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, 54, 'PG-054', 'Meninggalkan kelas tanpa izin #054', 5, 'Deskripsi pelanggaran nomor 54.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, 55, 'PG-055', 'Tidak mengerjakan tugas #055', 6, 'Deskripsi pelanggaran nomor 55.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, 56, 'PG-056', 'Rambut tidak sesuai aturan #056', 7, 'Deskripsi pelanggaran nomor 56.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, 57, 'PG-057', 'Membawa barang terlarang #057', 8, 'Deskripsi pelanggaran nomor 57.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, 58, 'PG-058', 'Berkata tidak sopan #058', 9, 'Deskripsi pelanggaran nomor 58.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, 59, 'PG-059', 'Membuang sampah sembarangan #059', 10, 'Deskripsi pelanggaran nomor 59.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, 60, 'PG-060', 'Tidak mengikuti upacara #060', 1, 'Deskripsi pelanggaran nomor 60.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, 61, 'PG-061', 'Terlambat datang ke sekolah #061', 2, 'Deskripsi pelanggaran nomor 61.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, 62, 'PG-062', 'Tidak memakai atribut lengkap #062', 3, 'Deskripsi pelanggaran nomor 62.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, 63, 'PG-063', 'Tidak memakai seragam sesuai ketentuan #063', 4, 'Deskripsi pelanggaran nomor 63.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, 64, 'PG-064', 'Meninggalkan kelas tanpa izin #064', 5, 'Deskripsi pelanggaran nomor 64.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, 65, 'PG-065', 'Tidak mengerjakan tugas #065', 6, 'Deskripsi pelanggaran nomor 65.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, 66, 'PG-066', 'Rambut tidak sesuai aturan #066', 7, 'Deskripsi pelanggaran nomor 66.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, 67, 'PG-067', 'Membawa barang terlarang #067', 8, 'Deskripsi pelanggaran nomor 67.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, 68, 'PG-068', 'Berkata tidak sopan #068', 9, 'Deskripsi pelanggaran nomor 68.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, 69, 'PG-069', 'Membuang sampah sembarangan #069', 10, 'Deskripsi pelanggaran nomor 69.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, 70, 'PG-070', 'Tidak mengikuti upacara #070', 1, 'Deskripsi pelanggaran nomor 70.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, 71, 'PG-071', 'Terlambat datang ke sekolah #071', 2, 'Deskripsi pelanggaran nomor 71.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, 72, 'PG-072', 'Tidak memakai atribut lengkap #072', 3, 'Deskripsi pelanggaran nomor 72.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, 73, 'PG-073', 'Tidak memakai seragam sesuai ketentuan #073', 4, 'Deskripsi pelanggaran nomor 73.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, 74, 'PG-074', 'Meninggalkan kelas tanpa izin #074', 5, 'Deskripsi pelanggaran nomor 74.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, 75, 'PG-075', 'Tidak mengerjakan tugas #075', 6, 'Deskripsi pelanggaran nomor 75.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, 76, 'PG-076', 'Rambut tidak sesuai aturan #076', 7, 'Deskripsi pelanggaran nomor 76.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, 77, 'PG-077', 'Membawa barang terlarang #077', 8, 'Deskripsi pelanggaran nomor 77.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, 78, 'PG-078', 'Berkata tidak sopan #078', 9, 'Deskripsi pelanggaran nomor 78.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, 79, 'PG-079', 'Membuang sampah sembarangan #079', 10, 'Deskripsi pelanggaran nomor 79.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, 80, 'PG-080', 'Tidak mengikuti upacara #080', 1, 'Deskripsi pelanggaran nomor 80.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, 81, 'PG-081', 'Terlambat datang ke sekolah #081', 2, 'Deskripsi pelanggaran nomor 81.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, 82, 'PG-082', 'Tidak memakai atribut lengkap #082', 3, 'Deskripsi pelanggaran nomor 82.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, 83, 'PG-083', 'Tidak memakai seragam sesuai ketentuan #083', 4, 'Deskripsi pelanggaran nomor 83.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, 84, 'PG-084', 'Meninggalkan kelas tanpa izin #084', 5, 'Deskripsi pelanggaran nomor 84.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, 85, 'PG-085', 'Tidak mengerjakan tugas #085', 6, 'Deskripsi pelanggaran nomor 85.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, 86, 'PG-086', 'Rambut tidak sesuai aturan #086', 7, 'Deskripsi pelanggaran nomor 86.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, 87, 'PG-087', 'Membawa barang terlarang #087', 8, 'Deskripsi pelanggaran nomor 87.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, 88, 'PG-088', 'Berkata tidak sopan #088', 9, 'Deskripsi pelanggaran nomor 88.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, 89, 'PG-089', 'Membuang sampah sembarangan #089', 10, 'Deskripsi pelanggaran nomor 89.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, 90, 'PG-090', 'Tidak mengikuti upacara #090', 1, 'Deskripsi pelanggaran nomor 90.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, 91, 'PG-091', 'Terlambat datang ke sekolah #091', 2, 'Deskripsi pelanggaran nomor 91.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, 92, 'PG-092', 'Tidak memakai atribut lengkap #092', 3, 'Deskripsi pelanggaran nomor 92.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, 93, 'PG-093', 'Tidak memakai seragam sesuai ketentuan #093', 4, 'Deskripsi pelanggaran nomor 93.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, 94, 'PG-094', 'Meninggalkan kelas tanpa izin #094', 5, 'Deskripsi pelanggaran nomor 94.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, 95, 'PG-095', 'Tidak mengerjakan tugas #095', 6, 'Deskripsi pelanggaran nomor 95.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, 96, 'PG-096', 'Rambut tidak sesuai aturan #096', 7, 'Deskripsi pelanggaran nomor 96.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, 97, 'PG-097', 'Membawa barang terlarang #097', 8, 'Deskripsi pelanggaran nomor 97.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, 98, 'PG-098', 'Berkata tidak sopan #098', 9, 'Deskripsi pelanggaran nomor 98.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, 99, 'PG-099', 'Membuang sampah sembarangan #099', 10, 'Deskripsi pelanggaran nomor 99.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, 100, 'PG-100', 'Tidak mengikuti upacara #100', 1, 'Deskripsi pelanggaran nomor 100.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan 1', 'Kategori pelanggaran nomor 1', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 'Kerapian 1', 'Kategori pelanggaran nomor 2', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, 'Kehadiran 1', 'Kategori pelanggaran nomor 3', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, 'Ketertiban 1', 'Kategori pelanggaran nomor 4', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, 'Perlengkapan 1', 'Kategori pelanggaran nomor 5', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, 'Sikap 1', 'Kategori pelanggaran nomor 6', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, 'Kebersihan 1', 'Kategori pelanggaran nomor 7', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 'Keamanan 1', 'Kategori pelanggaran nomor 8', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, 'Akademik 1', 'Kategori pelanggaran nomor 9', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, 'Lingkungan 1', 'Kategori pelanggaran nomor 10', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, 'Kedisiplinan 2', 'Kategori pelanggaran nomor 11', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, 'Kerapian 2', 'Kategori pelanggaran nomor 12', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, 'Kehadiran 2', 'Kategori pelanggaran nomor 13', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, 'Ketertiban 2', 'Kategori pelanggaran nomor 14', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, 'Perlengkapan 2', 'Kategori pelanggaran nomor 15', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, 'Sikap 2', 'Kategori pelanggaran nomor 16', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, 'Kebersihan 2', 'Kategori pelanggaran nomor 17', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, 'Keamanan 2', 'Kategori pelanggaran nomor 18', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, 'Akademik 2', 'Kategori pelanggaran nomor 19', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 'Lingkungan 2', 'Kategori pelanggaran nomor 20', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, 'Kedisiplinan 3', 'Kategori pelanggaran nomor 21', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, 'Kerapian 3', 'Kategori pelanggaran nomor 22', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, 'Kehadiran 3', 'Kategori pelanggaran nomor 23', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, 'Ketertiban 3', 'Kategori pelanggaran nomor 24', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, 'Perlengkapan 3', 'Kategori pelanggaran nomor 25', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, 'Sikap 3', 'Kategori pelanggaran nomor 26', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, 'Kebersihan 3', 'Kategori pelanggaran nomor 27', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, 'Keamanan 3', 'Kategori pelanggaran nomor 28', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, 'Akademik 3', 'Kategori pelanggaran nomor 29', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, 'Lingkungan 3', 'Kategori pelanggaran nomor 30', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, 'Kedisiplinan 4', 'Kategori pelanggaran nomor 31', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, 'Kerapian 4', 'Kategori pelanggaran nomor 32', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 'Kehadiran 4', 'Kategori pelanggaran nomor 33', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, 'Ketertiban 4', 'Kategori pelanggaran nomor 34', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, 'Perlengkapan 4', 'Kategori pelanggaran nomor 35', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, 'Sikap 4', 'Kategori pelanggaran nomor 36', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, 'Kebersihan 4', 'Kategori pelanggaran nomor 37', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, 'Keamanan 4', 'Kategori pelanggaran nomor 38', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, 'Akademik 4', 'Kategori pelanggaran nomor 39', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, 'Lingkungan 4', 'Kategori pelanggaran nomor 40', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, 'Kedisiplinan 5', 'Kategori pelanggaran nomor 41', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, 'Kerapian 5', 'Kategori pelanggaran nomor 42', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, 'Kehadiran 5', 'Kategori pelanggaran nomor 43', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, 'Ketertiban 5', 'Kategori pelanggaran nomor 44', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, 'Perlengkapan 5', 'Kategori pelanggaran nomor 45', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, 'Sikap 5', 'Kategori pelanggaran nomor 46', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, 'Kebersihan 5', 'Kategori pelanggaran nomor 47', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, 'Keamanan 5', 'Kategori pelanggaran nomor 48', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, 'Akademik 5', 'Kategori pelanggaran nomor 49', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, 'Lingkungan 5', 'Kategori pelanggaran nomor 50', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, 'Kedisiplinan 6', 'Kategori pelanggaran nomor 51', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, 'Kerapian 6', 'Kategori pelanggaran nomor 52', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, 'Kehadiran 6', 'Kategori pelanggaran nomor 53', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, 'Ketertiban 6', 'Kategori pelanggaran nomor 54', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, 'Perlengkapan 6', 'Kategori pelanggaran nomor 55', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, 'Sikap 6', 'Kategori pelanggaran nomor 56', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, 'Kebersihan 6', 'Kategori pelanggaran nomor 57', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, 'Keamanan 6', 'Kategori pelanggaran nomor 58', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, 'Akademik 6', 'Kategori pelanggaran nomor 59', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, 'Lingkungan 6', 'Kategori pelanggaran nomor 60', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, 'Kedisiplinan 7', 'Kategori pelanggaran nomor 61', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, 'Kerapian 7', 'Kategori pelanggaran nomor 62', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, 'Kehadiran 7', 'Kategori pelanggaran nomor 63', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, 'Ketertiban 7', 'Kategori pelanggaran nomor 64', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, 'Perlengkapan 7', 'Kategori pelanggaran nomor 65', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, 'Sikap 7', 'Kategori pelanggaran nomor 66', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, 'Kebersihan 7', 'Kategori pelanggaran nomor 67', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, 'Keamanan 7', 'Kategori pelanggaran nomor 68', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, 'Akademik 7', 'Kategori pelanggaran nomor 69', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, 'Lingkungan 7', 'Kategori pelanggaran nomor 70', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, 'Kedisiplinan 8', 'Kategori pelanggaran nomor 71', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, 'Kerapian 8', 'Kategori pelanggaran nomor 72', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, 'Kehadiran 8', 'Kategori pelanggaran nomor 73', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, 'Ketertiban 8', 'Kategori pelanggaran nomor 74', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, 'Perlengkapan 8', 'Kategori pelanggaran nomor 75', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, 'Sikap 8', 'Kategori pelanggaran nomor 76', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, 'Kebersihan 8', 'Kategori pelanggaran nomor 77', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, 'Keamanan 8', 'Kategori pelanggaran nomor 78', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, 'Akademik 8', 'Kategori pelanggaran nomor 79', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, 'Lingkungan 8', 'Kategori pelanggaran nomor 80', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, 'Kedisiplinan 9', 'Kategori pelanggaran nomor 81', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, 'Kerapian 9', 'Kategori pelanggaran nomor 82', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, 'Kehadiran 9', 'Kategori pelanggaran nomor 83', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, 'Ketertiban 9', 'Kategori pelanggaran nomor 84', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, 'Perlengkapan 9', 'Kategori pelanggaran nomor 85', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, 'Sikap 9', 'Kategori pelanggaran nomor 86', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, 'Kebersihan 9', 'Kategori pelanggaran nomor 87', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, 'Keamanan 9', 'Kategori pelanggaran nomor 88', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, 'Akademik 9', 'Kategori pelanggaran nomor 89', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, 'Lingkungan 9', 'Kategori pelanggaran nomor 90', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, 'Kedisiplinan 10', 'Kategori pelanggaran nomor 91', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, 'Kerapian 10', 'Kategori pelanggaran nomor 92', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, 'Kehadiran 10', 'Kategori pelanggaran nomor 93', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, 'Ketertiban 10', 'Kategori pelanggaran nomor 94', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, 'Perlengkapan 10', 'Kategori pelanggaran nomor 95', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, 'Sikap 10', 'Kategori pelanggaran nomor 96', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, 'Kebersihan 10', 'Kategori pelanggaran nomor 97', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, 'Keamanan 10', 'Kategori pelanggaran nomor 98', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, 'Akademik 10', 'Kategori pelanggaran nomor 99', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, 'Lingkungan 10', 'Kategori pelanggaran nomor 100', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) DEFAULT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text NOT NULL,
  `poin` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '102410544', '0000000001', 'Adittia Nur Lantip', 'L', '2010-01-15', 'Jl. Pendidikan No. 1, Tasikmalaya', 1, '2025-12-31 18:00:00', '2026-11-09 17:59:48'),
(2, '102410545', '0000000002', 'Agnia Rosya Noerillah', 'P', '2010-02-20', 'Jl. Pendidikan No. 2, Tasikmalaya', 1, '2026-02-01 18:00:00', '2026-02-01 18:00:00'),
(3, '102410546', '0000000003', 'Anisa Amirulla', 'P', '2010-03-12', 'Jl. Pendidikan No. 3, Tasikmalaya', 1, '2026-03-02 18:00:00', '2026-03-02 18:00:00'),
(4, '102410547', '0000000004', 'Aradea Kandi', 'L', '2010-04-25', 'Jl. Pendidikan No. 4, Tasikmalaya', 1, '2026-04-03 18:00:00', '2026-04-03 18:00:00'),
(5, '102410548', '0000000005', 'Arfa Diesa Okmaleliza', 'P', '2010-05-18', 'Jl. Pendidikan No. 5, Tasikmalaya', 1, '2026-05-04 18:00:00', '2026-05-04 18:00:00'),
(6, '102410549', '0000000006', 'Fitri Nur Syami', 'P', '2010-06-07', 'Jl. Pendidikan No. 6, Tasikmalaya', 1, '2026-06-05 18:00:00', '2026-06-05 18:00:00'),
(7, '102410550', '0000000007', 'Junita Putri Nur Rahman', 'P', '2010-07-22', 'Jl. Pendidikan No. 7, Tasikmalaya', 1, '2026-07-06 18:00:00', '2026-07-06 18:00:00'),
(8, '102410551', '0000000008', 'Mohammad Atalarik Ali Syahbana', 'L', '2010-08-14', 'Jl. Pendidikan No. 8, Tasikmalaya', 1, '2026-08-07 18:00:00', '2026-08-07 18:00:00'),
(9, '102410552', '0000000009', 'Muhammad Fahril Fahrurozi', 'L', '2010-09-30', 'Jl. Pendidikan No. 9, Tasikmalaya', 1, '2026-09-08 18:00:00', '2026-09-08 18:00:00'),
(10, '102410750', '0000000010', 'Muhammad Adnan Syaeful Islami', 'L', '2010-10-11', 'Jl. Pendidikan No. 10, Tasikmalaya', 1, '2026-10-09 18:00:00', '2026-10-09 18:00:00'),
(11, '102410553', '0000000011', 'Muhammad Zahru Aqila Fikri', 'L', '2010-11-05', 'Jl. Pendidikan No. 11, Tasikmalaya', 1, '2026-11-10 18:00:00', '2026-11-10 18:00:00'),
(12, '102410554', '0000000012', 'Nadia Zulfa Soraya', 'P', '2010-12-19', 'Jl. Pendidikan No. 12, Tasikmalaya', 1, '2026-12-11 18:00:00', '2026-12-11 18:00:00'),
(13, '102410555', '0000000013', 'Nazril Ilham', 'L', '2011-01-08', 'Jl. Pendidikan No. 13, Tasikmalaya', 1, '2026-01-12 18:00:00', '2026-01-12 18:00:00'),
(14, '102410556', '0000000014', 'Puspita Dewi', 'P', '2011-02-17', 'Jl. Pendidikan No. 14, Tasikmalaya', 1, '2026-02-13 18:00:00', '2026-02-13 18:00:00'),
(15, '102410558', '0000000015', 'Rakha Muhammad Naykal', 'L', '2011-03-29', 'Jl. Pendidikan No. 15, Tasikmalaya', 1, '2026-03-14 18:00:00', '2026-03-14 18:00:00'),
(16, '102410559', '0000000016', 'Rani Melani', 'P', '2011-04-13', 'Jl. Pendidikan No. 16, Tasikmalaya', 1, '2026-04-15 18:00:00', '2026-04-15 18:00:00'),
(17, '102410560', '0000000017', 'Reihan Muzaki', 'L', '2011-05-21', 'Jl. Pendidikan No. 17, Tasikmalaya', 1, '2026-05-16 18:00:00', '2026-05-16 18:00:00'),
(18, '102410561', '0000000018', 'Resa Juliansah', 'L', '2011-06-09', 'Jl. Pendidikan No. 18, Tasikmalaya', 1, '2026-06-17 18:00:00', '2026-06-17 18:00:00'),
(19, '102410562', '0000000019', 'Rizki Permata Sugianto', 'L', '2011-07-27', 'Jl. Pendidikan No. 19, Tasikmalaya', 1, '2026-07-18 18:00:00', '2026-07-18 18:00:00'),
(20, '102410563', '0000000020', 'Sahira Firaj', 'P', '2011-08-16', 'Jl. Pendidikan No. 20, Tasikmalaya', 1, '2026-08-19 18:00:00', '2026-08-19 18:00:00'),
(21, '102410564', '0000000021', 'Sendi Mardian', 'L', '2011-09-04', 'Jl. Pendidikan No. 21, Tasikmalaya', 1, '2026-09-20 18:00:00', '0000-00-00 00:00:00'),
(22, '102410755', '0000000022', 'Shahaidan Nizzar', 'L', '2011-10-23', 'Jl. Pendidikan No. 22, Tasikmalaya', 1, '2026-10-21 18:00:00', '2026-10-21 18:00:00'),
(23, '102410565', '0000000023', 'Sherly Satriani', 'P', '2011-11-14', 'Jl. Pendidikan No. 23, Tasikmalaya', 1, '2026-11-22 18:00:00', '2026-11-22 18:00:00'),
(24, '102410566', '0000000024', 'Sofinurjanah', 'P', '2011-12-02', 'Jl. Pendidikan No. 24, Tasikmalaya', 1, '2026-12-23 18:00:00', '2026-12-23 18:00:00'),
(25, '102410567', '0000000025', 'Suraiya Salsabila Azzahira', 'P', '2010-01-28', 'Jl. Pendidikan No. 25, Tasikmalaya', 1, '2026-01-24 18:00:00', '2026-01-24 18:00:00'),
(26, '102410568', '0000000026', 'Toni Antonio', 'L', '2010-02-11', 'Jl. Pendidikan No. 26, Tasikmalaya', 1, '2026-02-25 18:00:00', '2026-02-25 18:00:00'),
(83, '102611149', '0000000027', 'Abdul Malik Fajar', 'L', '2009-01-12', 'Jl. Contoh Melati No. 1, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(84, '102611150', '0000000028', 'Agin Ginanjar Saputra', 'L', '2009-03-21', 'Jl. Contoh Mawar No. 2, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(85, '102611151', '0000000029', 'Algi Athari', 'L', '2009-05-14', 'Jl. Contoh Anggrek No. 3, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(86, '102611152', '0000000030', 'Arystya Apriliandi Franki', 'L', '2009-07-09', 'Jl. Contoh Kenanga No. 4, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(87, '102611153', '0000000031', 'Billah Abdillah', 'L', '2009-02-18', 'Jl. Contoh Dahlia No. 5, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(88, '102611154', '0000000032', 'Dhimas Dwi Ramadhan', 'L', '2009-04-25', 'Jl. Contoh Teratai No. 6, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(89, '102611155', '0000000033', 'Fadil Ramdhani', 'L', '2009-06-11', 'Jl. Contoh Flamboyan No. 7, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(90, '102611478', '0000000034', 'Faraz Fachri Muharram', 'L', '2009-08-17', 'Jl. Contoh Cempaka No. 8, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(91, '102611157', '0000000035', 'Haikal Ilham Suhanda', 'L', '2009-09-23', 'Jl. Contoh Kamboja No. 9, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(92, '102611158', '0000000036', 'Laodra Roba Ahfiana', 'L', '2009-10-05', 'Jl. Contoh Cemara No. 10, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(93, '102611159', '0000000037', 'Lintang Febrian Maulana', 'L', '2009-11-19', 'Jl. Contoh Pinus No. 11, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(94, '102611160', '0000000038', 'Lisan Dzakir Ramadhan', 'L', '2009-12-08', 'Jl. Contoh Merpati No. 12, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(95, '102611161', '0000000039', 'Muhamad Abylah Azam', 'L', '2010-01-16', 'Jl. Contoh Rajawali No. 13, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(96, '102611162', '0000000040', 'Muhamad Algiansyah', 'L', '2010-02-22', 'Jl. Contoh Garuda No. 14, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(97, '102611163', '0000000041', 'Muhamad Azril Yasin', 'L', '2010-03-13', 'Jl. Contoh Merak No. 15, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(98, '102611164', '0000000042', 'Muhamad Itshar', 'L', '2010-04-27', 'Jl. Contoh Elang No. 16, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(99, '102611165', '0000000043', 'Muhamad Nasabandy', 'L', '2010-05-09', 'Jl. Contoh Rajin No. 17, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(100, '102611166', '0000000044', 'Muhammad Adam Efathurrohman', 'L', '2010-06-15', 'Jl. Contoh Sukamaju No. 18, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(101, '102611167', '0000000045', 'Muhammad Dzikri Abdul Jalil', 'L', '2010-07-20', 'Jl. Contoh Sukaraja No. 19, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(102, '102611476', '0000000046', 'Muhammad Rafa Firmansyah', 'L', '2010-08-11', 'Jl. Contoh Sukasari No. 20, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(103, '102611169', '0000000047', 'Muhammad Rizky Hidayatullah', 'L', '2010-09-24', 'Jl. Contoh Cibereum No. 21, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(104, '102611170', '0000000048', 'Nasta Pratama Supriatna', 'L', '2010-10-06', 'Jl. Contoh Cihideung No. 22, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(105, '102611171', '0000000049', 'Raditia Purnama', 'L', '2010-11-18', 'Jl. Contoh Indihiang No. 23, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(106, '102611172', '0000000050', 'Rahmat Suparman', 'L', '2010-12-12', 'Jl. Contoh Kawalu No. 24, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(107, '102611173', '0000000051', 'Rama Manramsyah Gustian Pratama', 'L', '2009-02-05', 'Jl. Contoh Mangkubumi No. 25, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(108, '102611174', '0000000052', 'Rehan Nadi Saputra', 'L', '2009-04-19', 'Jl. Contoh Tamansari No. 26, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(109, '102611175', '0000000053', 'Rof Rof Al Arof Subul Amin', 'L', '2009-06-23', 'Jl. Contoh Singaparna No. 27, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(110, '102611176', '0000000054', 'Sandi Permana', 'L', '2009-08-08', 'Jl. Contoh Manonjaya No. 28, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(111, '102611177', '0000000055', 'Tubagus Rizqi', 'L', '2009-10-14', 'Jl. Contoh Rajapolah No. 29, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(112, '102611178', '0000000056', 'Wildan Nurjaman', 'L', '2009-12-20', 'Jl. Contoh Cisayong No. 30, Tasikmalaya', 1, '2026-11-06 01:18:37', '2026-11-06 01:18:37'),
(113, '102410341', '0000000057', 'Adi Bayu Maulana Putra', 'L', '2008-01-12', 'Jl. Melati, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(114, '102410343', '0000000058', 'Agan Basyri Sa\'ady', 'L', '2008-02-15', 'Jl. Mawar, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(115, '102410344', '0000000059', 'Ali Rahmat Mutaqin', 'L', '2008-03-21', 'Jl. Anggrek, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(116, '102410345', '0000000060', 'Alzi Kusnendi', 'L', '2008-04-09', 'Jl. Kenanga, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(117, '102410346', '0000000061', 'Andika Nugraha', 'L', '2008-05-17', 'Jl. Dahlia, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(118, '102410347', '0000000062', 'Andri Mabrur', 'L', '2008-06-11', 'Jl. Teratai, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(119, '102410349', '0000000063', 'Dafa Ezar Ramadan', 'L', '2008-07-23', 'Jl. Cempaka, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(120, '102410350', '0000000064', 'Defri Muhamad Ramadhan', 'L', '2008-08-14', 'Jl. Cemara, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(121, '102410351', '0000000065', 'Deva Al Fauzan', 'L', '2008-09-05', 'Jl. Merpati, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(122, '102410352', '0000000066', 'Dika Ardiansyah Putra', 'L', '2008-10-19', 'Jl. Rajawali, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(123, '102410353', '0000000067', 'Fadli Muhammad Ramdhan', 'L', '2008-11-08', 'Jl. Garuda, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(124, '102410354', '0000000068', 'Febi Danil Gunawan', 'L', '2008-12-16', 'Jl. Merak, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(125, '102410355', '0000000069', 'Gabriel Alhazmi Adiyasa', 'L', '2008-01-25', 'Jl. Elang, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(126, '102410356', '0000000070', 'Gisna Sufi Ginanjar', 'L', '2008-02-27', 'Jl. Pinus, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(127, '102410358', '0000000071', 'Lutfi Indriawan', 'L', '2008-03-13', 'Jl. Flamboyan, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(128, '102410359', '0000000072', 'Moch Alif Sayyandra Abipraya', 'L', '2008-04-22', 'Jl. Sukamaju, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(129, '102410360', '0000000073', 'Muhamad Al Mu\'min Akbar', 'L', '2008-05-06', 'Jl. Sukaraja, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(130, '102410361', '0000000074', 'Muhamad Rafi Ramdani', 'L', '2008-06-18', 'Jl. Sukasari, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(131, '102410362', '0000000075', 'Muhamad Zulpi Zulkipli', 'L', '2008-07-29', 'Jl. Cibeureum, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(132, '102410364', '0000000076', 'Muhammad Faisal Arrizal', 'L', '2008-08-12', 'Jl. Cihideung, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(133, '102410366', '0000000077', 'Muhammad Rafliyatu Rizqi Nur Rohman', 'L', '2008-09-24', 'Jl. Indihiang, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(134, '102410367', '0000000078', 'Muhammad Ridho Qushoiri', 'L', '2008-10-07', 'Jl. Kawalu, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(135, '102410368', '0000000079', 'Raditia Nugraha', 'L', '2008-11-15', 'Jl. Tamansari, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(136, '102410369', '0000000080', 'Raeval Legya Rachman', 'L', '2008-12-03', 'Jl. Mangkubumi, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(137, '102410370', '0000000081', 'Rafa Azkia', 'L', '2008-01-19', 'Jl. Singaparna, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(138, '102410371', '0000000082', 'Refan Sultansah', 'L', '2008-02-08', 'Jl. Rajapolah, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(139, '102410372', '0000000083', 'Reihan Al Azhar', 'L', '2008-03-26', 'Jl. Cisayong, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(140, '112510757', '0000000084', 'Sani Achmad Fikri', 'L', '2008-04-16', 'Jl. Leuwisari, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(141, '102410373', '0000000085', 'Sendy Ramdhani', 'L', '2008-05-28', 'Jl. Manonjaya, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(142, '102410374', '0000000086', 'Surahman Wahdan Andika', 'L', '2008-06-09', 'Jl. Salawu, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(143, '102410375', '0000000087', 'Tahmid Hidayatulloh', 'L', '2008-07-17', 'Jl. Sukaratu, Tasikmalaya', 1, '2026-11-06 01:30:14', '2026-11-06 01:30:14'),
(144, '102410520', '0091000001', 'Alfin Rahadian', 'L', '2008-01-12', 'Jl. Pendidikan No. 1', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(145, '102410521', '0091000002', 'Alisa Ramadhani', 'P', '2008-02-15', 'Jl. Pendidikan No. 2', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(146, '102410522', '0091000003', 'Alma Aura Livia', 'P', '2008-03-20', 'Jl. Pendidikan No. 3', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(147, '102410523', '0091000004', 'Anis Khoerunnisa Nur Ramadani', 'P', '2008-04-18', 'Jl. Pendidikan No. 4', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(148, '102410524', '0091000005', 'Azmil Amira', 'P', '2008-05-22', 'Jl. Pendidikan No. 5', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(149, '102410525', '0091000006', 'Azril Akbar Fawji Sedillah', 'L', '2008-06-10', 'Jl. Pendidikan No. 6', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(150, '102410527', '0091000007', 'Erlangga Saputra Hidayat', 'L', '2008-07-14', 'Jl. Pendidikan No. 7', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(151, '102410528', '0091000008', 'Hilma Humma Tunnajah', 'P', '2008-08-19', 'Jl. Pendidikan No. 8', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(152, '102410529', '0091000009', 'Ilma Najya', 'P', '2008-09-12', 'Jl. Pendidikan No. 9', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(153, '102410531', '0091000010', 'Julianda Dharma Pangestu', 'L', '2008-10-16', 'Jl. Pendidikan No. 10', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(154, '102410532', '0091000011', 'Kayla Nur Fadilah', 'P', '2008-11-21', 'Jl. Pendidikan No. 11', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(155, '102410533', '0091000012', 'Moza Dina Nabillah', 'P', '2008-12-09', 'Jl. Pendidikan No. 12', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(156, '102410534', '0091000013', 'Muhamad Alfarizi', 'L', '2008-01-25', 'Jl. Pendidikan No. 13', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(157, '102410535', '0091000014', 'Muhamad Fahrin Adam', 'L', '2008-02-27', 'Jl. Pendidikan No. 14', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(158, '102410536', '0091000015', 'Muhamad Zidane Maulidan', 'L', '2008-03-16', 'Jl. Pendidikan No. 15', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(159, '102410537', '0091000016', 'Muhamad Zulfan Nuralfansyah', 'L', '2008-04-24', 'Jl. Pendidikan No. 16', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(160, '102410538', '0091000017', 'Natiya Nur Azkiya', 'P', '2008-05-13', 'Jl. Pendidikan No. 17', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(161, '102410539', '0091000018', 'Novita Puspitasari', 'P', '2008-06-29', 'Jl. Pendidikan No. 18', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(162, '102410540', '0091000019', 'Nucky Andreas Febrian', 'L', '2008-07-08', 'Jl. Pendidikan No. 19', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(163, '102410543', '0091000020', 'Raditya Jeti Prasetya', 'L', '2008-08-26', 'Jl. Pendidikan No. 20', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(164, '102410741', '0091000021', 'Rangga Ramadiyanto Nurhakim', 'L', '2008-09-17', 'Jl. Pendidikan No. 21', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(165, '102410541', '0091000022', 'Risqi Muhamad Ihwani', 'L', '2008-10-23', 'Jl. Pendidikan No. 22', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(166, '102410542', '0091000023', 'Salwa Sabilatul Barakah', 'P', '2008-11-11', 'Jl. Pendidikan No. 23', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08'),
(167, '102410753', '0091000024', 'Tazkia Zahra', 'P', '2008-12-20', 'Jl. Pendidikan No. 24', 1, '2026-11-06 01:57:08', '2026-11-06 01:57:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(29, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, '2028/2029', '2028-07-01', '2029-06-30', 0, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, '2028/2029', '2028-07-01', '2029-06-30', 0, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, '2028/2029', '2028-07-01', '2029-06-30', 0, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, '2028/2029', '2028-07-01', '2029-06-30', 0, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, '2029/2030', '2029-07-01', '2030-06-30', 0, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, '2029/2030', '2029-07-01', '2030-06-30', 0, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, '2029/2030', '2029-07-01', '2030-06-30', 0, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, '2029/2030', '2029-07-01', '2030-06-30', 0, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, '2030/2031', '2030-07-01', '2031-06-30', 0, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, '2030/2031', '2030-07-01', '2031-06-30', 0, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, '2030/2031', '2030-07-01', '2031-06-30', 0, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, '2030/2031', '2030-07-01', '2031-06-30', 0, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, '2031/2032', '2031-07-01', '2032-06-30', 0, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, '2031/2032', '2031-07-01', '2032-06-30', 0, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, '2031/2032', '2031-07-01', '2032-06-30', 0, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, '2031/2032', '2031-07-01', '2032-06-30', 0, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, '2032/2033', '2032-07-01', '2033-06-30', 0, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, '2032/2033', '2032-07-01', '2033-06-30', 0, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, '2032/2033', '2032-07-01', '2033-06-30', 0, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, '2032/2033', '2032-07-01', '2033-06-30', 0, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, '2033/2034', '2033-07-01', '2034-06-30', 0, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, '2033/2034', '2033-07-01', '2034-06-30', 0, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, '2033/2034', '2033-07-01', '2034-06-30', 0, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, '2033/2034', '2033-07-01', '2034-06-30', 0, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, '2034/2035', '2034-07-01', '2035-06-30', 0, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, '2034/2035', '2034-07-01', '2035-06-30', 0, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, '2034/2035', '2034-07-01', '2035-06-30', 0, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, '2034/2035', '2034-07-01', '2035-06-30', 0, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, '2035/2036', '2035-07-01', '2036-06-30', 0, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, '2035/2036', '2035-07-01', '2036-06-30', 0, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, '2035/2036', '2035-07-01', '2036-06-30', 0, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, '2035/2036', '2035-07-01', '2036-06-30', 0, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, '2036/2037', '2036-07-01', '2037-06-30', 0, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, '2036/2037', '2036-07-01', '2037-06-30', 0, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, '2036/2037', '2036-07-01', '2037-06-30', 0, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, '2036/2037', '2036-07-01', '2037-06-30', 0, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, '2037/2038', '2037-07-01', '2038-06-30', 0, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, '2037/2038', '2037-07-01', '2038-06-30', 0, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, '2037/2038', '2037-07-01', '2038-06-30', 0, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, '2037/2038', '2037-07-01', '2038-06-30', 0, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, '2038/2039', '2038-07-01', '2039-06-30', 0, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, '2038/2039', '2038-07-01', '2039-06-30', 0, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, '2038/2039', '2038-07-01', '2039-06-30', 0, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, '2038/2039', '2038-07-01', '2039-06-30', 0, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, '2039/2040', '2039-07-01', '2040-06-30', 0, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, '2039/2040', '2039-07-01', '2040-06-30', 0, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, '2039/2040', '2039-07-01', '2040-06-30', 0, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, '2039/2040', '2039-07-01', '2040-06-30', 0, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, '2040/2041', '2040-07-01', '2041-06-30', 0, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, '2040/2041', '2040-07-01', '2041-06-30', 0, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, '2040/2041', '2040-07-01', '2041-06-30', 0, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, '2040/2041', '2040-07-01', '2041-06-30', 0, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, '2041/2042', '2041-07-01', '2042-06-30', 0, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, '2041/2042', '2041-07-01', '2042-06-30', 0, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, '2041/2042', '2041-07-01', '2042-06-30', 0, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, '2041/2042', '2041-07-01', '2042-06-30', 0, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, '2042/2043', '2042-07-01', '2043-06-30', 0, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, '2042/2043', '2042-07-01', '2043-06-30', 0, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, '2042/2043', '2042-07-01', '2043-06-30', 0, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, '2042/2043', '2042-07-01', '2043-06-30', 0, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, '2043/2044', '2043-07-01', '2044-06-30', 0, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, '2043/2044', '2043-07-01', '2044-06-30', 0, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, '2043/2044', '2043-07-01', '2044-06-30', 0, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, '2043/2044', '2043-07-01', '2044-06-30', 0, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, '2044/2045', '2044-07-01', '2045-06-30', 0, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, '2044/2045', '2044-07-01', '2045-06-30', 0, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, '2044/2045', '2044-07-01', '2045-06-30', 0, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, '2044/2045', '2044-07-01', '2045-06-30', 0, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(104, 'administrator', 'admin@gmail.com', '2026-10-02 01:14:07', '$2y$10$jBUNIkbpmaWi7C3ZgBeiD.ju6Np/LGvb/roqxrkheA6.TRpteGL1m', '', 'admin', '2026-10-02 01:14:07', '2026-10-02 01:14:07'),
(106, 'guru', 'guru@gmail.com', '2026-10-02 03:22:57', '$2y$10$NJtQ5MEq5d.4PsusaHO9xu3kkxeaR6g1M7S8T6VB900fHauavKh5K', '', 'guru', '2026-10-02 03:22:57', '2026-10-02 03:22:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_t_kelas_tahun_ajaran_id` (`tahun_ajaran_id`);

--
-- Indeks untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`);

--
-- Indeks untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT untuk tabel `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD CONSTRAINT `t_kelas_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_3` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_ibfk_1` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`),
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_4` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_5` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_6` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_3` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
