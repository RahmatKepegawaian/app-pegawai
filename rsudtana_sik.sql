/*
 Navicat Premium Data Transfer

 Source Server         : SERVER
 Source Server Type    : MySQL
 Source Server Version : 50740 (5.7.40-log)
 Source Host           : 192.168.100.10:3306
 Source Schema         : rsudtana_sik

 Target Server Type    : MySQL
 Target Server Version : 50740 (5.7.40-log)
 File Encoding         : 65001

 Date: 27/04/2026 15:12:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cpcb
-- ----------------------------
DROP TABLE IF EXISTS `cpcb`;
CREATE TABLE `cpcb`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for data_ultah_pegawai_temp
-- ----------------------------
DROP TABLE IF EXISTS `data_ultah_pegawai_temp`;
CREATE TABLE `data_ultah_pegawai_temp`  (
  `no` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_pegawai` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_lahir_1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_hp_wa` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log`  (
  `id` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggal` datetime NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tanggal`(`tanggal`) USING BTREE,
  INDEX `idx_log_user_tanggal`(`user`, `tanggal`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for log_success
-- ----------------------------
DROP TABLE IF EXISTS `log_success`;
CREATE TABLE `log_success`  (
  `datetime` datetime NOT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for peminjaman_aula
-- ----------------------------
DROP TABLE IF EXISTS `peminjaman_aula`;
CREATE TABLE `peminjaman_aula`  (
  `id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_peminjam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tanggal_mulai` datetime NULL DEFAULT NULL,
  `tanggal_selesai` datetime NULL DEFAULT NULL,
  `jenis` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for peminjaman_laptops
-- ----------------------------
DROP TABLE IF EXISTS `peminjaman_laptops`;
CREATE TABLE `peminjaman_laptops`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) NULL DEFAULT NULL,
  `nama_peminjam` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `unit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tipe_laptop` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keperluan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tanggal_pinjam` date NULL DEFAULT NULL,
  `tanggal_kembali` date NULL DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for set_shift
-- ----------------------------
DROP TABLE IF EXISTS `set_shift`;
CREATE TABLE `set_shift`  (
  `id_shift` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_shift`) USING BTREE,
  INDEX `set_shift_ibfk1`(`id_unit`) USING BTREE,
  INDEX `set_shift_ibfk2`(`id_absensi`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for set_spj
-- ----------------------------
DROP TABLE IF EXISTS `set_spj`;
CREATE TABLE `set_spj`  (
  `id_spj` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ppk_keuangan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bendahara_pengeluaran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  INDEX `set_spj_ibfk_1`(`ppk_keuangan`) USING BTREE,
  INDEX `set_spj_ibfk_2`(`bendahara_pengeluaran`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `nama_instansi` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `alamat_instansi` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kabupaten` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `propinsi` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kontak` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `aktifkan` enum('Yes','No') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_ppk` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_ppkinhealth` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_ppkkemenkes` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `wallpaper` longblob NULL,
  `logo` longblob NOT NULL,
  PRIMARY KEY (`nama_instansi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for setup
-- ----------------------------
DROP TABLE IF EXISTS `setup`;
CREATE TABLE `setup`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_skpd` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_instansi` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alamat_kop` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tlp` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `website` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_pos` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `logo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `password` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `direktur` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nip_direktur` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tutup_kinerja` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `validasi_pj` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `validasi_kasie` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dispensasi_absensi` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pengumuman` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `slipgaji_receiver_req` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bendahara_pengeluaran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `enable_hitung_absensi_akhirtahun` int(1) NOT NULL DEFAULT 0 COMMENT 'digunakan di akhir tahun, \r\nkarna absensi ditarik di tgl 20 dibulan yg sama. jadi sisa hari nya ga pada alpha',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_absensi_ket_kepegawaian
-- ----------------------------
DROP TABLE IF EXISTS `tm_absensi_ket_kepegawaian`;
CREATE TABLE `tm_absensi_ket_kepegawaian`  (
  `id_keterangan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_keterangan`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_acls
-- ----------------------------
DROP TABLE IF EXISTS `tm_acls`;
CREATE TABLE `tm_acls`  (
  `id_acls` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_acls` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_acls` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_acls`) USING BTREE,
  INDEX `tm_acls_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_add_surtug
-- ----------------------------
DROP TABLE IF EXISTS `tm_add_surtug`;
CREATE TABLE `tm_add_surtug`  (
  `id_add` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_surat` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nip` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `read` int(1) NOT NULL,
  PRIMARY KEY (`id_add`) USING BTREE,
  INDEX `tm_add_surtug_ibfk1`(`id_surat`) USING BTREE,
  INDEX `tm_add_surtug_ibfk2`(`nip`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_apn
-- ----------------------------
DROP TABLE IF EXISTS `tm_apn`;
CREATE TABLE `tm_apn`  (
  `id_apn` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_apn` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_apn` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_apn`) USING BTREE,
  INDEX `tm_apn_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_atls
-- ----------------------------
DROP TABLE IF EXISTS `tm_atls`;
CREATE TABLE `tm_atls`  (
  `id_atls` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_atls` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_atls` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_atls`) USING BTREE,
  INDEX `tm_atcls_tm_pegawai_id_user_ibfk`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_btcls
-- ----------------------------
DROP TABLE IF EXISTS `tm_btcls`;
CREATE TABLE `tm_btcls`  (
  `id_btcls` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_btcls` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_btcls` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_btcls`) USING BTREE,
  INDEX `tm_atcls_tm_pegawai_id_user_ibfk`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_cuti
-- ----------------------------
DROP TABLE IF EXISTS `tm_cuti`;
CREATE TABLE `tm_cuti`  (
  `id_cuti` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_permohonan` datetime NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_shift_ketidakhadiran.id_ketidakhadiran',
  `jumlah_hari` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tahun_cuti` char(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `periode_cuti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat_cuti` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_tlp` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alasan_cuti` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bukti1` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `buk1` mediumblob NULL,
  `mime1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bukti2` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `buk2` mediumblob NULL,
  `mime2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_pengganti` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_pj` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_kasatpel` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_kasie` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_ktu` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user_direktur` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `acc_kepegawaian` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_pengganti` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_pj` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_kasatpel` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_kasie` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_ktu` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `acc_direktur` enum('-','Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `tgl_acc_kepegawaian` datetime NULL DEFAULT NULL,
  `tgl_acc_pengganti` datetime NULL DEFAULT NULL,
  `tgl_acc_pj` datetime NULL DEFAULT NULL,
  `tgl_acc_kasatpel` datetime NULL DEFAULT NULL,
  `tgl_acc_kasie` datetime NULL DEFAULT NULL,
  `tgl_acc_ktu` datetime NULL DEFAULT NULL,
  `tgl_acc_direktur` datetime NULL DEFAULT NULL,
  `no_surti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alasan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `aktif` int(11) NULL DEFAULT 1,
  `updated_at` date NULL DEFAULT NULL,
  PRIMARY KEY (`id_cuti`) USING BTREE,
  INDEX `tm_cuti_ibfk1`(`id_user`) USING BTREE,
  INDEX `tm_cuti_ibfk2`(`id_user_pengganti`) USING BTREE,
  INDEX `tm_cuti_ibfk3`(`id_user_pj`) USING BTREE,
  INDEX `tm_cuti_ibfk4`(`id_user_kasie`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20260460 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_cuti_validasi_log
-- ----------------------------
DROP TABLE IF EXISTS `tm_cuti_validasi_log`;
CREATE TABLE `tm_cuti_validasi_log`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_cuti` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9524 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_cv
-- ----------------------------
DROP TABLE IF EXISTS `tm_cv`;
CREATE TABLE `tm_cv`  (
  `id_cv` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_cv` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cv`) USING BTREE,
  INDEX `tm_str_ibfk_1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_date
-- ----------------------------
DROP TABLE IF EXISTS `tm_date`;
CREATE TABLE `tm_date`  (
  `date` date NULL DEFAULT NULL,
  `day` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_hari_cuti
-- ----------------------------
DROP TABLE IF EXISTS `tm_hari_cuti`;
CREATE TABLE `tm_hari_cuti`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cuti` int(11) NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tm_hari_cuti_ibfk_1`(`id_cuti`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 87512590 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tm_hari_kerja
-- ----------------------------
DROP TABLE IF EXISTS `tm_hari_kerja`;
CREATE TABLE `tm_hari_kerja`  (
  `id_hari_kerja` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bulan` date NULL DEFAULT NULL,
  `hari` int(2) NULL DEFAULT NULL,
  PRIMARY KEY (`id_hari_kerja`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_hari_kuota_cuti
-- ----------------------------
DROP TABLE IF EXISTS `tm_hari_kuota_cuti`;
CREATE TABLE `tm_hari_kuota_cuti`  (
  `id_kuota` int(255) NOT NULL AUTO_INCREMENT,
  `tanggal` date NULL DEFAULT NULL COMMENT 'tm_hari_raya atau tm_hari_libur',
  `hari` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_shift_ketidakhadiran.id_ketidakhadiran',
  `kuota` int(3) NULL DEFAULT NULL,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kuota`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_hari_libur
-- ----------------------------
DROP TABLE IF EXISTS `tm_hari_libur`;
CREATE TABLE `tm_hari_libur`  (
  `id_hari_libur` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal` date NOT NULL,
  `cuti_bersama` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hari_libur`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_hari_raya
-- ----------------------------
DROP TABLE IF EXISTS `tm_hari_raya`;
CREATE TABLE `tm_hari_raya`  (
  `id_hari_raya` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hari_raya`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_honor_shift
-- ----------------------------
DROP TABLE IF EXISTS `tm_honor_shift`;
CREATE TABLE `tm_honor_shift`  (
  `id_petugas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `petugas` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hks` int(11) NOT NULL,
  `hkm` int(11) NOT NULL,
  `hlp` int(15) NOT NULL,
  `hls` int(15) NOT NULL,
  `hlm` int(15) NOT NULL,
  `hrp` int(15) NOT NULL,
  `hrs` int(15) NOT NULL,
  `hrm` int(15) NOT NULL,
  PRIMARY KEY (`id_petugas`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_ijazah
-- ----------------------------
DROP TABLE IF EXISTS `tm_ijazah`;
CREATE TABLE `tm_ijazah`  (
  `id_riwayat_pend` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_ijazah` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_riwayat_pend`) USING BTREE,
  INDEX `tm_ijazah_ibfk2`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_izin_belajar
-- ----------------------------
DROP TABLE IF EXISTS `tm_izin_belajar`;
CREATE TABLE `tm_izin_belajar`  (
  `id_belajar` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_univ` varchar(85) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alamat_univ` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pendidikan_sebelum` enum('S3','S2','S1','DIV','DIII','SMA') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pendidikan_sesudah` enum('S3','S2','S1','DIV','DIII','SMA') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jurusan` varchar(85) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `akreditasi` enum('A','B','C','D') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis_peningkatan` enum('Tugas Belajar','Penyesuaian Ijazah','Peningkatan Pendidikan','Ujian Dinas') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_izin` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal_izin_belajar` date NOT NULL,
  PRIMARY KEY (`id_belajar`) USING BTREE,
  INDEX `tm_izin_belajar_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jabatan
-- ----------------------------
DROP TABLE IF EXISTS `tm_jabatan`;
CREATE TABLE `tm_jabatan`  (
  `id_jabatan` int(255) NOT NULL AUTO_INCREMENT,
  `nm_jabatan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_jabatan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_detail
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_detail`;
CREATE TABLE `tm_jadwalpegawai_absensi_detail`  (
  `id_jadwalkerja_absensi` int(255) NOT NULL AUTO_INCREMENT,
  `id_jadwalpegawai_absensi_rekap_session` int(255) NOT NULL COMMENT 'tm_jadwalpegawai_absensi_rekap_session.id_jadwalpegawai_absensi_rekap_session',
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_unit.id_unit / direferensikan dengan tm_jadwalpegawai_shift_m',
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_pegawai',
  `date` date NOT NULL,
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_absensi_tipe` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `shift_aktif` int(1) NULL DEFAULT NULL COMMENT '0 : (shift tidak aktif). Join ke tm_shift_ketidakharian\r\n1 : (shift aktif). Join ke tm_shift\r\n2 : (shift aktif). Ga join ke tm_shift. Biasanya jadwal dr spesialis',
  `jam_masuk_absensi_aktif` time NULL DEFAULT NULL,
  `jam_pulang_absensi_aktif` time NULL DEFAULT NULL,
  `absensi_masuk` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  `absensi_pulang` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterlambatan` int(3) NOT NULL DEFAULT 0,
  `pulang_cepat` int(3) NOT NULL DEFAULT 0,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `accepted` int(1) NOT NULL DEFAULT 0 COMMENT '0 : not yet, 1 : yes',
  `keterangan_keterlambatan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '' COMMENT 'tm_absensi_ket_kepegawaian.id_keterangan',
  `keterangan_pulangcepat` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '' COMMENT 'tm_absensi_ket_kepegawaian.id_keterangan',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_absensi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 871241 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_detail_daterange
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_detail_daterange`;
CREATE TABLE `tm_jadwalpegawai_absensi_detail_daterange`  (
  `id_jadwalkerja_absensi` int(255) NOT NULL AUTO_INCREMENT,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'unit yg memakai baru spesialis parttime',
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_pegawai',
  `date` date NOT NULL,
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_absensi_tipe` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `shift_aktif` int(1) NOT NULL COMMENT '0 : (shift tidak aktif). Join ke tm_shift_ketidakharian\r\n1 : (shift aktif). Join ke tm_shift\r\n2 : (shift aktif). Ga join ke tm_shift. Biasanya jadwal dr spesialis',
  `jam_masuk_absensi_aktif` time NOT NULL,
  `jam_pulang_absensi_aktif` time NOT NULL,
  `absensi_masuk` datetime NOT NULL,
  `absensi_pulang` datetime NOT NULL,
  `keterlambatan` int(3) NOT NULL DEFAULT 0,
  `pulang_cepat` int(3) NOT NULL DEFAULT 0,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `accepted` int(1) NOT NULL DEFAULT 0 COMMENT '0 : not yet, 1 : yes',
  `keterangan_keterlambatan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_absensi_ket_kepegawaian.id_keterangan',
  `keterangan_pulangcepat` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_absensi_ket_kepegawaian.id_keterangan',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_absensi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_plgcpt_log
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_plgcpt_log`;
CREATE TABLE `tm_jadwalpegawai_absensi_plgcpt_log`  (
  `id_jadwalkerja_absensi_telat_log` int(255) NOT NULL AUTO_INCREMENT,
  `id_jadwalkerja_absensi` int(255) NOT NULL COMMENT 'tm_jadwalpegawai_absensi_detail.id_jadwalpegawai_absensi',
  `plng_cepat_old` int(3) NULL DEFAULT NULL,
  `plng_cepat_new` int(3) NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_absensi_telat_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1430 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_rekap
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_rekap`;
CREATE TABLE `tm_jadwalpegawai_absensi_rekap`  (
  `id_jadwalpegawai_absensi_rekap` int(255) NOT NULL AUTO_INCREMENT,
  `id_jadwalpegawai_absensi_rekap_session` int(255) NOT NULL COMMENT 'tm_jadwalpegawai_absensi_rekap_session.id_jadwalpegawai_absensi_rekap_session',
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user / user kepegawaian yg merekapitulasi dan menedit rekap ini',
  `k_jml_alpha` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah alpla u pengurangan',
  `k_jml_sakit_1hari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah sakit < 2 hari u pengurangan',
  `k_jml_sakit_2hari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah sakit > 2 hari u pengurangan',
  `k_jml_izin` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin u pengurangan',
  `k_jml_cuti_sakit` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti sakit u pengurangan',
  `k_jml_cuti_alsnpenting` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti alasan penting u pengurangan',
  `k_jml_cuti_prslnan` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti persalinan u pengurangan',
  `k_jml_izin_sethari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin setengah hari u pengurangan',
  `k_jml_meninggal` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah hari meninggal u pengurangan',
  `k_jml_telat` int(4) NOT NULL DEFAULT 0 COMMENT '(menit) jumlah telat u pengurangan',
  `k_jml_plng_cepat` int(4) NOT NULL DEFAULT 0 COMMENT '(menit) jumlah pulang cepat u pengurangan',
  `t_jml_cuti_sakit` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti sakit u penambahan',
  `t_jml_cuti_alsnpenting` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti alasan penting u penambahan',
  `t_jml_cuti_thnan` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti tahunan u penambahan',
  `t_jml_diklat` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah hari diklat u penambahan',
  `t_jml_spd` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah spd u penambahan',
  `t_jml_haji` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin haji u penambahan',
  `s_jml_hks` int(2) NOT NULL DEFAULT 0,
  `s_jml_hkm` int(2) NOT NULL DEFAULT 0,
  `s_jml_hlp` int(2) NOT NULL DEFAULT 0,
  `s_jml_hls` int(2) NOT NULL DEFAULT 0,
  `s_jml_hlm` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrp` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrs` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrm` int(2) NOT NULL DEFAULT 0,
  `s_jml_ns` int(2) NOT NULL DEFAULT 0,
  `jml_hari_kerja` int(2) NULL DEFAULT 0,
  `jml_menit_kerja` int(6) NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalpegawai_absensi_rekap`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35954 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_rekap_copy1
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_rekap_copy1`;
CREATE TABLE `tm_jadwalpegawai_absensi_rekap_copy1`  (
  `id_jadwalpegawai_absensi_rekap` int(255) NOT NULL AUTO_INCREMENT,
  `id_jadwalpegawai_absensi_rekap_session` int(255) NOT NULL COMMENT 'tm_jadwalpegawai_absensi_rekap_session.id_jadwalpegawai_absensi_rekap_session',
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user / user kepegawaian yg merekapitulasi dan menedit rekap ini',
  `k_jml_alpha` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah alpla u pengurangan',
  `k_jml_sakit_1hari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah sakit < 2 hari u pengurangan',
  `k_jml_sakit_2hari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah sakit > 2 hari u pengurangan',
  `k_jml_izin` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin u pengurangan',
  `k_jml_cuti_sakit` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti sakit u pengurangan',
  `k_jml_cuti_alsnpenting` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti alasan penting u pengurangan',
  `k_jml_cuti_prslnan` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti persalinan u pengurangan',
  `k_jml_izin_sethari` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin setengah hari u pengurangan',
  `k_jml_meninggal` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah hari meninggal u pengurangan',
  `k_jml_telat` int(4) NOT NULL DEFAULT 0 COMMENT '(menit) jumlah telat u pengurangan',
  `k_jml_plng_cepat` int(4) NOT NULL DEFAULT 0 COMMENT '(menit) jumlah pulang cepat u pengurangan',
  `t_jml_cuti_sakit` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti sakit u penambahan',
  `t_jml_cuti_alsnpenting` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti alasan penting u penambahan',
  `t_jml_cuti_thnan` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah cuti tahunan u penambahan',
  `t_jml_diklat` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah hari diklat u penambahan',
  `t_jml_spd` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah spd u penambahan',
  `t_jml_haji` int(2) NOT NULL DEFAULT 0 COMMENT '(hari) jumlah izin haji u penambahan',
  `s_jml_hks` int(2) NOT NULL DEFAULT 0,
  `s_jml_hkm` int(2) NOT NULL DEFAULT 0,
  `s_jml_hlp` int(2) NOT NULL DEFAULT 0,
  `s_jml_hls` int(2) NOT NULL DEFAULT 0,
  `s_jml_hlm` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrp` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrs` int(2) NOT NULL DEFAULT 0,
  `s_jml_hrm` int(2) NOT NULL DEFAULT 0,
  `s_jml_ns` int(2) NOT NULL DEFAULT 0,
  `jml_hari_kerja` int(2) NULL DEFAULT 0,
  `jml_menit_kerja` int(6) NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalpegawai_absensi_rekap`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35472 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_rekap_daterange
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_rekap_daterange`;
CREATE TABLE `tm_jadwalpegawai_absensi_rekap_daterange`  (
  `id_jadwalpegawai_absensi_rekap` int(255) NOT NULL AUTO_INCREMENT,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'unit yang baru memakai spesialis parttime',
  `date_from` date NOT NULL COMMENT 'mulai perhitungan absensi',
  `date_to` date NOT NULL COMMENT 'akhir_perhitungan absensi',
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user / user kepegawaian yg merekapitulasi dan menedit rekap ini',
  `k_jml_alpha` int(2) NOT NULL COMMENT '(hari) jumlah alpla u pengurangan',
  `k_jml_sakit_1hari` int(2) NOT NULL COMMENT '(hari) jumlah sakit < 2 hari u pengurangan',
  `k_jml_sakit_2hari` int(2) NOT NULL COMMENT '(hari) jumlah sakit > 2 hari u pengurangan',
  `k_jml_izin` int(2) NOT NULL COMMENT '(hari) jumlah izin u pengurangan',
  `k_jml_cuti_sakit` int(2) NOT NULL COMMENT '(hari) jumlah cuti sakit u pengurangan',
  `k_jml_cuti_alsnpenting` int(2) NOT NULL COMMENT '(hari) jumlah cuti alasan penting u pengurangan',
  `k_jml_cuti_prslnan` int(2) NOT NULL COMMENT '(hari) jumlah cuti persalinan u pengurangan',
  `k_jml_izin_sethari` int(2) NOT NULL COMMENT '(hari) jumlah izin setengah hari u pengurangan',
  `k_jml_meninggal` int(2) NOT NULL COMMENT '(hari) jumlah hari meninggal u pengurangan',
  `k_jml_telat` int(4) NOT NULL COMMENT '(menit) jumlah telat u pengurangan',
  `k_jml_plng_cepat` int(4) NOT NULL COMMENT '(menit) jumlah pulang cepat u pengurangan',
  `t_jml_cuti_sakit` int(2) NOT NULL COMMENT '(hari) jumlah cuti sakit u penambahan',
  `t_jml_cuti_alsnpenting` int(2) NOT NULL COMMENT '(hari) jumlah cuti alasan penting u penambahan',
  `t_jml_cuti_thnan` int(2) NOT NULL COMMENT '(hari) jumlah cuti tahunan u penambahan',
  `t_jml_diklat` int(2) NOT NULL COMMENT '(hari) jumlah hari diklat u penambahan',
  `t_jml_spd` int(2) NOT NULL COMMENT '(hari) jumlah spd u penambahan',
  `t_jml_haji` int(2) NOT NULL COMMENT '(hari) jumlah izin haji u penambahan',
  `s_jml_hks` int(2) NOT NULL,
  `s_jml_hkm` int(2) NOT NULL,
  `s_jml_hlp` int(2) NOT NULL,
  `s_jml_hls` int(2) NOT NULL,
  `s_jml_hlm` int(2) NOT NULL,
  `s_jml_hrp` int(2) NOT NULL,
  `s_jml_hrs` int(2) NOT NULL,
  `s_jml_hrm` int(2) NOT NULL,
  `s_jml_ns` int(2) NOT NULL,
  `jml_hari_kerja` int(2) NOT NULL,
  `jml_menit_kerja` int(6) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalpegawai_absensi_rekap`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17726 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_session
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_session`;
CREATE TABLE `tm_jadwalpegawai_absensi_session`  (
  `id_jadwalpegawai_absensi_rekap_session` int(255) NOT NULL AUTO_INCREMENT,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `id_kepegawaian_generated` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user',
  `id_kepegawaian_accepted` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user',
  `accepted` int(1) NOT NULL DEFAULT 0 COMMENT '0 : not yet, 1 : yes',
  `timestamp_generated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timestamp_accepted` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_jadwalpegawai_absensi_rekap_session`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 837 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_absensi_telat_log
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_absensi_telat_log`;
CREATE TABLE `tm_jadwalpegawai_absensi_telat_log`  (
  `id_jadwalkerja_absensi_telat_log` int(255) NOT NULL AUTO_INCREMENT,
  `id_jadwalkerja_absensi` int(255) NOT NULL COMMENT 'tm_jadwalpegawai_absensi_detail.id_jadwalpegawai_absensi',
  `keterlambatan_old` int(3) NULL DEFAULT NULL,
  `keterlambatan_new` int(3) NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_absensi_telat_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1213 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_shift_log
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_shift_log`;
CREATE TABLE `tm_jadwalpegawai_shift_log`  (
  `id_jadwalkerja_shift_log` int(255) NOT NULL AUTO_INCREMENT,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'array data dinamis',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_shift_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18580 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_shift_m
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_shift_m`;
CREATE TABLE `tm_jadwalpegawai_shift_m`  (
  `id_jadwalkerja_shift` int(255) NOT NULL AUTO_INCREMENT,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalkerja_shift.id_unit = tm_unit.id_unit',
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `id_penanggung_jawab` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalpegawai_shift_m_pj = tm_pegawai.id_user',
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalpegawai_shift_m.id_user = tm_pegawai.id_user',
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_jadwalpegawai_shift_m.id_absensi = tm_shift.id_absensi',
  `id_absensi_tipe` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_shift_tipe.id_absensi_tipe',
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_jadwalpegawai_shift_m.id_ketidakhadiran = tm_shift_ketidakhadiran.id_ketidakhadiran',
  `date` date NULL DEFAULT NULL,
  `submitted` int(1) NOT NULL DEFAULT 0 COMMENT '0 : not yet, 1 : yes',
  `cpcb` enum('belum dipakai','dipakai') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `editable` int(1) NOT NULL DEFAULT 1 COMMENT '0 : restricted, 1 : allowed ',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_shift`) USING BTREE,
  INDEX `id_absensi`(`id_absensi`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 373384 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_shift_m_log
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_shift_m_log`;
CREATE TABLE `tm_jadwalpegawai_shift_m_log`  (
  `id_jadwalkerja_shift_m_log` int(255) NOT NULL AUTO_INCREMENT,
  `tipe_log` int(1) NOT NULL COMMENT '1 : insert, 2 : update, 3 : delete',
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_pj` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_absensi_tipe` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `submitted` int(1) NULL DEFAULT NULL,
  `editable` int(1) NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jadwalkerja_shift_m_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1373329 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_shift_notification
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_shift_notification`;
CREATE TABLE `tm_jadwalpegawai_shift_notification`  (
  `id_jadwalkerja_shift_notification` int(255) NOT NULL AUTO_INCREMENT,
  `id_user_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `valid` int(1) NOT NULL DEFAULT 1 COMMENT '1 : true, 0 : false',
  `read_status` int(1) NOT NULL DEFAULT 0 COMMENT '1 : true, 0 : false',
  `data` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `click_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_jadwalkerja_shift_notification`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1258 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_jadwalpegawai_shift_validation
-- ----------------------------
DROP TABLE IF EXISTS `tm_jadwalpegawai_shift_validation`;
CREATE TABLE `tm_jadwalpegawai_shift_validation`  (
  `id_jadwalkerja_shift_validation` int(10) NOT NULL AUTO_INCREMENT,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalpegawai_shift_validation.id_unit = tm_jadwalpegawai_shift.id_unit',
  `month` int(2) NOT NULL COMMENT 'tm_jadwalpegawai_shift_validation.month = tm_jadwalpegawai_shift.month',
  `year` int(4) NOT NULL COMMENT 'tm_jadwalpegawai_shift_validation.year = tm_jadwalpegawai_shift.year',
  `id_user_sender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalpegawai_shift_validation.id_user_sender = tm_pegawai.id_user',
  `id_user_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_jadwalpegawai_shift_validation.id_user_receiver = tm_pegawai.id_user',
  `answered` int(11) NOT NULL DEFAULT 0 COMMENT '0 : belum dijawab, 1 : diterima, 2 : direvisi',
  `notes` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-' COMMENT 'opsional',
  `timestamp_request` datetime NULL DEFAULT NULL,
  `timestamp_read` datetime NULL DEFAULT NULL,
  `timestamp_answer` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_jadwalkerja_shift_validation`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2980 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_kasatpel
-- ----------------------------
DROP TABLE IF EXISTS `tm_kasatpel`;
CREATE TABLE `tm_kasatpel`  (
  `id_kasatpel` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_kasatpel` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_kasatpel`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_kedisiplinan
-- ----------------------------
DROP TABLE IF EXISTS `tm_kedisiplinan`;
CREATE TABLE `tm_kedisiplinan`  (
  `id_disiplin` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `d_diri` float(2, 0) NOT NULL,
  `d_penampilan` float(2, 0) NOT NULL,
  `d_seragam` float(2, 0) NOT NULL,
  `d_alat` float(2, 0) NOT NULL,
  `d_ruangan` float(2, 0) NOT NULL,
  `d_sarana` float(2, 0) NOT NULL,
  `date_d` date NOT NULL,
  PRIMARY KEY (`id_disiplin`) USING BTREE,
  INDEX `tm_kedisiplinan_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_keluarga
-- ----------------------------
DROP TABLE IF EXISTS `tm_keluarga`;
CREATE TABLE `tm_keluarga`  (
  `id_fams` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nik` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_keluarga` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hubungan` enum('ibu','ayah','anak','suami','istri') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_lahir` date NULL DEFAULT NULL,
  `jk` enum('P','L') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pendidikan` enum('S3','S2','SI / DIV','DIII','SMA/SLTA/SMK/STM/MTA','SMP/SLTP/MTS','SD/MI','TK','-') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_fams`) USING BTREE,
  INDEX `tm_keluarga_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_kk
-- ----------------------------
DROP TABLE IF EXISTS `tm_kk`;
CREATE TABLE `tm_kk`  (
  `no_kk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_kk` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no_kk`) USING BTREE,
  INDEX `tm_kk_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_komite
-- ----------------------------
DROP TABLE IF EXISTS `tm_komite`;
CREATE TABLE `tm_komite`  (
  `id_komite` int(255) NOT NULL AUTO_INCREMENT,
  `nm_komite` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `active_status` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '0 : Tidak Aktif, 1 : Aktif',
  `full_time` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '0 : Komite Tidak Full Time, 1 : Komite Full Time',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_komite`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_kompetensi
-- ----------------------------
DROP TABLE IF EXISTS `tm_kompetensi`;
CREATE TABLE `tm_kompetensi`  (
  `id_kompetensi` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `menganalisa1` float(2, 0) NOT NULL,
  `menganalisa2` float(2, 0) NOT NULL,
  `komunikasi1` float(2, 0) NOT NULL,
  `komunikasi2` float(2, 0) NOT NULL,
  `kerjasama1` float(2, 0) NOT NULL,
  `kerjasama2` float(2, 0) NOT NULL,
  `kecerdasan1` float(2, 0) NOT NULL,
  `kecerdasan2` float(2, 0) NOT NULL,
  `kecerdasan3` float(2, 0) NOT NULL,
  `fokus1` float(2, 0) NOT NULL,
  `fokus2` float(2, 0) NOT NULL,
  `fokus3` float(2, 0) NOT NULL,
  `tanggung1` float(2, 0) NOT NULL,
  `tanggung2` float(2, 0) NOT NULL,
  `tanggung3` float(2, 0) NOT NULL,
  `tanggung4` float(2, 0) NOT NULL,
  `orientasi_k1` float(2, 0) NOT NULL,
  `orientasi_k2` float(2, 0) NOT NULL,
  `inisiatif1` float(2, 0) NOT NULL,
  `inisiatif2` float(2, 0) NOT NULL,
  `disiplin1` float(2, 0) NOT NULL,
  `disiplin2` float(2, 0) NOT NULL,
  `disiplin3` float(2, 0) NOT NULL,
  `orientasi_p1` float(2, 0) NOT NULL,
  `orientasi_p2` float(2, 0) NOT NULL,
  `orientasi_p3` float(2, 0) NOT NULL,
  `date_kompetensi` date NOT NULL,
  PRIMARY KEY (`id_kompetensi`) USING BTREE,
  INDEX `tm_kompetensi_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_ktp
-- ----------------------------
DROP TABLE IF EXISTS `tm_ktp`;
CREATE TABLE `tm_ktp`  (
  `no_nik` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_ktp` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no_nik`) USING BTREE,
  INDEX `tm_ktp_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_kuota_cuti
-- ----------------------------
DROP TABLE IF EXISTS `tm_kuota_cuti`;
CREATE TABLE `tm_kuota_cuti`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kuota_cuti` int(2) NOT NULL COMMENT 'kuota cuti yang diberikan',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'id_kepegawaian yang menginput cuti',
  `range_cuti_start` date NOT NULL COMMENT 'tanggal cuti tsb boleh diambil oleh user',
  `range_cuti_end` date NOT NULL COMMENT 'tanggal cuti tsb boleh diambil oleh user',
  `alasan_cuti_diberikan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'alasan cuti diberikan oleh kepegawaian, biasanya ditulis cuti pengganti cuti bersama atau lain',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 200 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_level
-- ----------------------------
DROP TABLE IF EXISTS `tm_level`;
CREATE TABLE `tm_level`  (
  `id_level` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `level` int(1) NOT NULL COMMENT 'ascending, i.e. 1, 2, 3. digunakan utk mendapatkan atasannya. ',
  `nama_level` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `menu_kepegawaian` int(1) NOT NULL,
  `submenu_data_pegawai` int(1) NOT NULL,
  `menu_surtug` int(1) NOT NULL,
  `menu_diklat` int(1) NOT NULL,
  `menu_keuangan` int(1) NOT NULL,
  `menu_val_pj` int(1) NOT NULL,
  `menu_val_kasatpel` int(1) NOT NULL,
  `menu_val_kasie` int(1) NOT NULL,
  `menu_val_kepegawaian` int(1) NOT NULL,
  `menu_val_cuti` int(1) NOT NULL,
  `menu_shift_pj` int(1) NOT NULL DEFAULT 0,
  `menu_shift_managerial` int(1) NOT NULL DEFAULT 0,
  `menu_laporan` int(1) NOT NULL,
  `menu_kepegawaian_absensi` int(1) NOT NULL DEFAULT 0,
  `upload_perpustakaan` int(1) NOT NULL DEFAULT 0,
  `menu_helpdesk` int(1) NOT NULL DEFAULT 0,
  `configurasi` int(1) NOT NULL,
  `grade_kinerja` int(2) NOT NULL,
  `grade_prilaku` int(2) NOT NULL,
  PRIMARY KEY (`id_level`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_level_menu
-- ----------------------------
DROP TABLE IF EXISTS `tm_level_menu`;
CREATE TABLE `tm_level_menu`  (
  `id_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` int(11) NULL DEFAULT NULL,
  `nama_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `menu_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `aktif` tinyint(1) NULL DEFAULT 1,
  `menu_val_pj` tinyint(1) NULL DEFAULT 0,
  `menu_val_kasatpel` tinyint(1) NULL DEFAULT 0,
  `menu_val_kasie` tinyint(1) NULL DEFAULT 0,
  `menu_val_kepegawaian` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id_level`, `menu_key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_level_menuu
-- ----------------------------
DROP TABLE IF EXISTS `tm_level_menuu`;
CREATE TABLE `tm_level_menuu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `level` int(11) NULL DEFAULT NULL,
  `nama_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `admin_kendali_cuti` tinyint(1) NULL DEFAULT 0,
  `assesment_cuti_kasie` tinyint(1) NULL DEFAULT 0,
  `assesment_cuti_kepegawaian` tinyint(1) NULL DEFAULT 0,
  `assesment_cuti_ktu` tinyint(1) NULL DEFAULT 0,
  `assesment_cuti_pj` tinyint(1) NULL DEFAULT 0,
  `assesment_direktur` tinyint(1) NULL DEFAULT 0,
  `assesment_kasatpel` tinyint(1) NULL DEFAULT 0,
  `assesment_kasie` tinyint(1) NULL DEFAULT 0,
  `assesment_kepegawaian` tinyint(1) NULL DEFAULT 0,
  `assesment_ktu` tinyint(1) NULL DEFAULT 0,
  `assesment_pengganti` tinyint(1) NULL DEFAULT 0,
  `assesment_pj` tinyint(1) NULL DEFAULT 0,
  `buat_shift` tinyint(1) NULL DEFAULT 0,
  `configurasi` tinyint(1) NULL DEFAULT 0,
  `cuti_pegawai` tinyint(1) NULL DEFAULT 0,
  `diklat` tinyint(1) NULL DEFAULT 0,
  `form_pegawai` tinyint(1) NULL DEFAULT 0,
  `helpdesk` tinyint(1) NULL DEFAULT 0,
  `helpdesk_tiket` tinyint(1) NULL DEFAULT 0,
  `kepegawaian` tinyint(1) NULL DEFAULT 0,
  `keuangan` tinyint(1) NULL DEFAULT 0,
  `kinerja` tinyint(1) NULL DEFAULT 0,
  `laporan` tinyint(1) NULL DEFAULT 0,
  `laporan_absensi` tinyint(1) NULL DEFAULT 0,
  `laporan_cuti_pegawai` tinyint(1) NULL DEFAULT 0,
  `laporan_data_upload` tinyint(1) NULL DEFAULT 0,
  `laporan_gaji` tinyint(1) NULL DEFAULT 0,
  `laporan_mappingttdspj` tinyint(1) NULL DEFAULT 0,
  `laporan_penilaianpgw` tinyint(1) NULL DEFAULT 0,
  `laporan_rekapshifting` tinyint(1) NULL DEFAULT 0,
  `log_absensi_pribadi` tinyint(1) NULL DEFAULT 0,
  `log_absensi_unit` tinyint(1) NULL DEFAULT 0,
  `master_bagian` tinyint(1) NULL DEFAULT 0,
  `master_hari_libur` tinyint(1) NULL DEFAULT 0,
  `master_hari_raya` tinyint(1) NULL DEFAULT 0,
  `master_kasatpel` tinyint(1) NULL DEFAULT 0,
  `master_ketidakhadiran` tinyint(1) NULL DEFAULT 0,
  `master_kuota_cuti` tinyint(1) NULL DEFAULT 0,
  `master_sanksi` tinyint(1) NULL DEFAULT 0,
  `master_shift` tinyint(1) NULL DEFAULT 0,
  `master_skp` tinyint(1) NULL DEFAULT 0,
  `pegawai_non_pns` tinyint(1) NULL DEFAULT 0,
  `pegawai_pjlp` tinyint(1) NULL DEFAULT 0,
  `pegawai_pns` tinyint(1) NULL DEFAULT 0,
  `pegawai_spesialis` tinyint(1) NULL DEFAULT 0,
  `pengajuan_cuti` tinyint(1) NULL DEFAULT 0,
  `penilaian_pjlp` tinyint(1) NULL DEFAULT 0,
  `pergantian_dinas` tinyint(1) NULL DEFAULT 0,
  `perpustakaan` tinyint(1) NULL DEFAULT 0,
  `semuacuti_pegawai` tinyint(1) NULL DEFAULT 0,
  `shift_pegawai` tinyint(1) NULL DEFAULT 0,
  `sip_pegawai` tinyint(1) NULL DEFAULT 0,
  `surat_tugas` tinyint(1) NULL DEFAULT 0,
  `validasi_absensi` tinyint(1) NULL DEFAULT 0,
  `validasi_direktur` tinyint(1) NULL DEFAULT 0,
  `validasi_kasatpel` tinyint(1) NULL DEFAULT 0,
  `validasi_kasie` tinyint(1) NULL DEFAULT 0,
  `validasi_pegawai` tinyint(1) NULL DEFAULT 0,
  `validasi_pj` tinyint(1) NULL DEFAULT 0,
  `validasi_shift` tinyint(1) NULL DEFAULT 0,
  `menu_val_pj` tinyint(1) NULL DEFAULT 0,
  `menu_val_kasatpel` tinyint(1) NULL DEFAULT 0,
  `menu_val_kasie` tinyint(1) NULL DEFAULT 0,
  `menu_val_kepegawaian` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_panduan
-- ----------------------------
DROP TABLE IF EXISTS `tm_panduan`;
CREATE TABLE `tm_panduan`  (
  `id_panduan` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_panduan` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tentang` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_panduan`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_pedoman
-- ----------------------------
DROP TABLE IF EXISTS `tm_pedoman`;
CREATE TABLE `tm_pedoman`  (
  `id_pedoman` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_pedoman` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tentang` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_pedoman`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `tm_pegawai`;
CREATE TABLE `tm_pegawai`  (
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nip` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_pegawai` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jk` enum('-','L','P') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alamat_domisili` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_hp_wa` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_hp_sms` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `npwp` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_rek` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pendidikan` enum('-','SD','SMP','SLTA','D III / D IV','S1','S2 / dr./ drg./ Apoteker/ Ners','S3 / dr. Spesialis') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tgl_masuk` date NOT NULL,
  `status_nikah` enum('-','LAJANG','MENIKAH','MENIKAH ANAK 1','MENIKAH ANAK 2','JANDA / DUDA ANAK 1','JANDA / DUDA ANAK 2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rumpun` enum('-','Dokter Spesialis Bedah','Dokter Spesialis Non Bedah','Dokter Spesialis Penunjang','Dokter Umum / Dokter Gigi','Apoteker / Ners','Radiologi/Analis/DIV/DIII Kes','Teknis Tingkat Ahli','Teknis Tingkat Terampil','Administrasi Tingkat Ahli','Administrasi Tingkat Terampil','Operasional Tingkat Ahli','Operasional Tingkat Terampil','Pelayanan Tingkat Ahli','Pelayanan Tingkat Terampil') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
  `pajak` enum('-','Tk/0','K/0','K/1','K/2','K/3','Tk/1','Tk/2','Tk/3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bpjs_ks` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_jkk` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_ijht` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_jp` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_bpjs` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `foto` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_pegawai` enum('PNS','NON PNS','-','PJLP','SPESIALIS','SPESIALIS-PARTTIME') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sub_bagian` varchar(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `log_finger` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agama` enum('-','KATOLIK','BUDHA','HINDU','KRISTEN','ISLAM') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `status` enum('AKTIF','NONAKTIF') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_kasatpel` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_user`, `nip`) USING BTREE,
  INDEX `id_rumpun_tm_rumpun_ibfk`(`rumpun`) USING BTREE,
  INDEX `id_pajak_tm_pajak_ibfk`(`pajak`) USING BTREE,
  INDEX `id_status_tm_status_pernikahan_ibfk`(`status_nikah`) USING BTREE,
  INDEX `id_unit_tm_unit_ibfk`(`id_unit`) USING BTREE,
  INDEX `nip`(`nip`) USING BTREE,
  INDEX `id_user`(`id_user`) USING BTREE,
  INDEX `tm_pegawai_ibfk3`(`id_kasatpel`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tm_penambahan_cuti
-- ----------------------------
DROP TABLE IF EXISTS `tm_penambahan_cuti`;
CREATE TABLE `tm_penambahan_cuti`  (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_shift_ketidakhadiran.id_ketidakhadiran',
  `tahun_cuti` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis` int(1) NOT NULL DEFAULT 1 COMMENT '1 : penambahan cuti,\r\n2 : pengurangan cuti',
  `jumlah` int(2) NOT NULL DEFAULT 0 COMMENT 'jumlah cuti yang ditambah/dikurang',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `active` int(1) NOT NULL DEFAULT 1 COMMENT '0: inactive,\r\n1: active',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_penilaian
-- ----------------------------
DROP TABLE IF EXISTS `tm_penilaian`;
CREATE TABLE `tm_penilaian`  (
  `id_penilaian` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nskp` decimal(5, 1) NOT NULL,
  `nprilaku` decimal(5, 1) NOT NULL,
  `id_sanksi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_penyerapan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_waktu_s` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_waktu_k` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_waktu_t` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gaji_pokok` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gaji_bruto` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tunjangan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tunjangan_val` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `masa_kerja` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rumpun` enum('Dokter Spesialis Bedah','Dokter Spesialis Non Bedah','Dokter Spesialis Penunjang','Dokter Umum / Dokter Gigi','Apoteker / Ners','Radiologi/Analis/DIV/DIII Kes','Teknis Tingkat Ahli','Teknis Tingkat Terampil','Administrasi Tingkat Ahli','Administrasi Tingkat Terampil','Operasional Tingkat Ahli','Operasional Tingkat Terampil','Pelayanan Tingkat Ahli','Pelayanan Tingkat Terampil') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pajak` enum('Tk/0','K/0','K/1','K/2','K/3','Tk/1','Tk/2','Tk/3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `penilai` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_nikah` enum('LAJANG','MENIKAH','MENIKAH ANAK 1','MENIKAH ANAK 2','JANDA / DUDA ANAK 1','JANDA / DUDA ANAK 2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pendidikan` enum('SD','SMP','SLTA','D III / D IV','S1','S2 / dr./ drg./ Apoteker/ Ners','S3 / dr.Spesialis','S3 / dr. Spesialis') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bpjs_ks` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_jkk` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_ijht` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bpjs_jp` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggal_penilaian` date NOT NULL,
  `date_real` datetime NOT NULL,
  PRIMARY KEY (`id_penilaian`) USING BTREE,
  INDEX `id_user`(`id_user`) USING BTREE,
  INDEX `tm_penilaian_ibfk2`(`id_penyerapan`) USING BTREE,
  INDEX `tm_penilaian_ibfk3`(`id_waktu_s`) USING BTREE,
  INDEX `tm_penilaian_ibfk4`(`id_waktu_k`) USING BTREE,
  INDEX `tm_penilaian_ibfk5`(`id_waktu_t`) USING BTREE,
  INDEX `tm_penilaian_ibfk6`(`rumpun`) USING BTREE,
  INDEX `tm_penilaian_ibfk7`(`pajak`) USING BTREE,
  INDEX `tm_penilaian_ibfk9`(`penilai`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_penilaian_pjlp
-- ----------------------------
DROP TABLE IF EXISTS `tm_penilaian_pjlp`;
CREATE TABLE `tm_penilaian_pjlp`  (
  `id_penilaian` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nabsensi` decimal(5, 0) NOT NULL,
  `nkinerja` decimal(5, 0) NOT NULL,
  `nkepatuhan` decimal(5, 0) NOT NULL,
  `penilai` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggal_penilaian` date NULL DEFAULT NULL,
  `date_real` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_penilaian`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_penyerapan
-- ----------------------------
DROP TABLE IF EXISTS `tm_penyerapan`;
CREATE TABLE `tm_penyerapan`  (
  `id_penyerapan` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bulan` date NULL DEFAULT NULL,
  `penyerapan` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_penyerapan`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_peraturan
-- ----------------------------
DROP TABLE IF EXISTS `tm_peraturan`;
CREATE TABLE `tm_peraturan`  (
  `id_peraturan` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_peraturan` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tentang` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis` enum('KEMEN','INDIR','INKADIS','INGUB','PERDA','PERMEN','PERGUB','PERDA','UU','PP') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_peraturan`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_phelebethomy
-- ----------------------------
DROP TABLE IF EXISTS `tm_phelebethomy`;
CREATE TABLE `tm_phelebethomy`  (
  `id_phelebethomy` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_phelebethomy` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_phelebethomy` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_phelebethomy`) USING BTREE,
  INDEX `tm_phelebotomy_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_riwayat_diklat
-- ----------------------------
DROP TABLE IF EXISTS `tm_riwayat_diklat`;
CREATE TABLE `tm_riwayat_diklat`  (
  `id_riwayat_diklat` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_pelatihan` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `instansi_pelatihan` varchar(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lokasi` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat_pelatihan` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `total_jam` float(3, 0) NULL DEFAULT 0,
  `jenis_diklat` enum('-','Diklat Struktural','Teknis Profesi Kesehatan','Teknis Program/Upaya Kesehatan','Teknis Umum/Administrasi dan Manajemen','Manajemen Kesehatan','Penjenjangan','Pratugas','Prajabatan','Diklat Fungsional') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
  `no_sertifikat` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status_akreditasi` enum('YA','TIDAK') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `config` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0',
  PRIMARY KEY (`id_riwayat_diklat`) USING BTREE,
  INDEX `tm_riwayat_diklat_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_riwayat_pend
-- ----------------------------
DROP TABLE IF EXISTS `tm_riwayat_pend`;
CREATE TABLE `tm_riwayat_pend`  (
  `id_riwayat_pend` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pendidikan` enum('-','S3','S2','S1 / D IV','D III','SMA/SLTA/SMK/STM/MTA','SMP/SLTP/MTS','SD/MI','TK') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_sekolah` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kota` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jurusan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_ijazah` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_ijazah` date NULL DEFAULT NULL,
  PRIMARY KEY (`id_riwayat_pend`) USING BTREE,
  INDEX `tm_riwayat_pen_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_riwayat_penempatan
-- ----------------------------
DROP TABLE IF EXISTS `tm_riwayat_penempatan`;
CREATE TABLE `tm_riwayat_penempatan`  (
  `id_riwayat_penempatan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_sk` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_riwayat_penempatan`) USING BTREE,
  INDEX `tm_riwayat_penempatan_ibfk1`(`id_user`) USING BTREE,
  INDEX `tm_riwayat_penempatan_ibfk2`(`id_unit`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_rkk
-- ----------------------------
DROP TABLE IF EXISTS `tm_rkk`;
CREATE TABLE `tm_rkk`  (
  `id_rkk` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_rkk` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_rkk` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_rkk`) USING BTREE,
  INDEX `tm_str_ibfk_1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_sanksi
-- ----------------------------
DROP TABLE IF EXISTS `tm_sanksi`;
CREATE TABLE `tm_sanksi`  (
  `id_sanksi` int(5) NOT NULL AUTO_INCREMENT,
  `nama_sanksi` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `masa_aktif` float(1, 0) NOT NULL,
  `nilai_sanksi` decimal(5, 2) NOT NULL,
  PRIMARY KEY (`id_sanksi`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_shift
-- ----------------------------
DROP TABLE IF EXISTS `tm_shift`;
CREATE TABLE `tm_shift`  (
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_shift` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `desc_shift` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `working_time_minute` int(3) NULL DEFAULT 0 COMMENT 'waktu kerja dalam menit',
  `counting_work` int(1) NULL DEFAULT 1 COMMENT '0 : not counting as working, 1 : in counting',
  `counting_as_isolman` int(1) NULL DEFAULT 0 COMMENT 'digunakan untuk mencegah karyawan isolman dihitung alpha',
  `hex_color_shift` varchar(7) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jam_masuk` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jam_pulang` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ai` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bo` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ao` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `shift_tipe` enum('PAGI','SORE','MALAM') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'PAGI' COMMENT 'tm_shift_tipe.shift_tipe',
  `nonshift_default_weekday` int(1) NULL DEFAULT 0 COMMENT 'pilihan default untuk non shift di weekday',
  `nonshift_default_friday` int(1) NULL DEFAULT 0 COMMENT 'pilihan default untuk non shift di jumat',
  PRIMARY KEY (`id_absensi`) USING BTREE,
  INDEX `id_absensi`(`id_absensi`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_shift_ketidakhadiran
-- ----------------------------
DROP TABLE IF EXISTS `tm_shift_ketidakhadiran`;
CREATE TABLE `tm_shift_ketidakhadiran`  (
  `id_ketidakhadiran` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_ketidakhadiran` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `desc_ketidakhadiran` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hex_color_ketidakhadiran` varchar(7) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_ketidakhadiran_tipe` int(1) NULL DEFAULT 1 COMMENT '1 : waktu pengurangan, 2 : waktu penambahan',
  `is_show_for_pj` int(1) NOT NULL DEFAULT 0 COMMENT '0 : false, 1 : true',
  `is_show_for_cuti_options` int(1) NULL DEFAULT 0 COMMENT '0 : false, 1 : true',
  `count_cuti` int(2) NOT NULL DEFAULT 0,
  `created` timestamp(1) NOT NULL DEFAULT CURRENT_TIMESTAMP(1),
  PRIMARY KEY (`id_ketidakhadiran`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_shift_ketidakhadiran_tipe
-- ----------------------------
DROP TABLE IF EXISTS `tm_shift_ketidakhadiran_tipe`;
CREATE TABLE `tm_shift_ketidakhadiran_tipe`  (
  `id_ketidakhadiran_tipe` int(1) NOT NULL AUTO_INCREMENT,
  `nama_ketidakhadiran_tipe` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ketidakhadiran_tipe`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_shift_tipe
-- ----------------------------
DROP TABLE IF EXISTS `tm_shift_tipe`;
CREATE TABLE `tm_shift_tipe`  (
  `id_absensi_tipe` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `shift_tipe` enum('PAGI','SORE','MALAM') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'PAGI',
  `shift_hari_tipe` enum('KERJA','LIBUR','RAYA') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'KERJA',
  `nama_shift_tipe` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `desc_shift_tipe` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bi` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jam_masuk` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ai` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `bo` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jam_pulang` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ao` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_absensi_tipe`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_sip
-- ----------------------------
DROP TABLE IF EXISTS `tm_sip`;
CREATE TABLE `tm_sip`  (
  `id_sip` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_sip` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file_sip` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_sip`) USING BTREE,
  INDEX `tm_sip_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_sk
-- ----------------------------
DROP TABLE IF EXISTS `tm_sk`;
CREATE TABLE `tm_sk`  (
  `id_sk` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_sk` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal_sk` date NOT NULL,
  `tentang` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_sk`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_skp
-- ----------------------------
DROP TABLE IF EXISTS `tm_skp`;
CREATE TABLE `tm_skp`  (
  `kd_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `skp` varchar(160) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `waktu` int(5) NOT NULL,
  PRIMARY KEY (`kd_skp`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_skp_pjlp
-- ----------------------------
DROP TABLE IF EXISTS `tm_skp_pjlp`;
CREATE TABLE `tm_skp_pjlp`  (
  `kd_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `skp` varchar(160) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `waktu` int(5) NOT NULL,
  PRIMARY KEY (`kd_skp`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_slipgaji_order
-- ----------------------------
DROP TABLE IF EXISTS `tm_slipgaji_order`;
CREATE TABLE `tm_slipgaji_order`  (
  `id_slipgaji_order` int(255) NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `desc_status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hex_color_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hex_color_keuangan` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '000000',
  `is_option_keuangan` int(1) NOT NULL DEFAULT 0 COMMENT '0 : bukan opsi u keuangan, 1 : opsi keuangan',
  `order_keuangan` int(1) NOT NULL,
  `is_final_option_keuangan` int(1) NOT NULL DEFAULT 0 COMMENT '0 : false, 1 : true',
  `is_start_option_keuangan` int(1) NOT NULL COMMENT '0 : false, 1 : true',
  `show_print_slipgaji_keuangan` int(1) NULL DEFAULT 0 COMMENT '0 : false, 1 : true',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_spk
-- ----------------------------
DROP TABLE IF EXISTS `tm_spk`;
CREATE TABLE `tm_spk`  (
  `id_spk` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_spk` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_spk` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_spk`) USING BTREE,
  INDEX `tm_str_ibfk_1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_spo
-- ----------------------------
DROP TABLE IF EXISTS `tm_spo`;
CREATE TABLE `tm_spo`  (
  `id_spo` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_spo` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tentang` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `deskripsi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `file` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_spo`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_str
-- ----------------------------
DROP TABLE IF EXISTS `tm_str`;
CREATE TABLE `tm_str`  (
  `id_str` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_str` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `periode` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file_str` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_str`) USING BTREE,
  INDEX `tm_str_ibfk_1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_sub_bagian
-- ----------------------------
DROP TABLE IF EXISTS `tm_sub_bagian`;
CREATE TABLE `tm_sub_bagian`  (
  `sub_bagian` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tm_surat_tugas
-- ----------------------------
DROP TABLE IF EXISTS `tm_surat_tugas`;
CREATE TABLE `tm_surat_tugas`  (
  `id_surat` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tgl_surat` date NULL DEFAULT NULL,
  `no_surat` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kegiatan` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_kegiatan` date NULL DEFAULT NULL,
  `waktu` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lokasi` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `an` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_surat`) USING BTREE,
  INDEX `tm_surat_tugas_ibfk1`(`an`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_tukarshift_order
-- ----------------------------
DROP TABLE IF EXISTS `tm_tukarshift_order`;
CREATE TABLE `tm_tukarshift_order`  (
  `id_tukarshift_status` int(2) NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `active_process` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '1 : true, 0 : false',
  `start_order` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0' COMMENT '1 : true, 0 : false',
  `end_order` enum('0','1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0' COMMENT '0 : false, \r\n1 : end order diterima,\r\n2 : end order ditolak',
  `order` int(2) NULL DEFAULT NULL,
  `id_level_sender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `id_level_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `created` datetime NULL DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tukarshift_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_unit
-- ----------------------------
DROP TABLE IF EXISTS `tm_unit`;
CREATE TABLE `tm_unit`  (
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_unit` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_petugas` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_honor_shift.id_petugas',
  `sub_bagian` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_unit`) USING BTREE,
  INDEX `id_petugas_tm_shift_ibfk`(`id_petugas`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_user
-- ----------------------------
DROP TABLE IF EXISTS `tm_user`;
CREATE TABLE `tm_user`  (
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password_backup` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `id_level` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `api_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`) USING BTREE,
  INDEX `id_level_tm_level_ibfk`(`id_level`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_waktu_k
-- ----------------------------
DROP TABLE IF EXISTS `tm_waktu_k`;
CREATE TABLE `tm_waktu_k`  (
  `id_waktu_k` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sakit1` float(2, 0) NOT NULL,
  `sakit2` float(2, 0) NOT NULL,
  `alpha` float(2, 0) NOT NULL,
  `izin` float(2, 0) NOT NULL,
  `izin_setengah_hari` float(2, 0) NOT NULL,
  `telat` float(5, 0) NOT NULL,
  `pulang_cepat` float(5, 0) NOT NULL DEFAULT 0,
  `ct_sakit_k` float(2, 0) NOT NULL,
  `ct_alasan_k` float(2, 0) NOT NULL,
  `ct_persalinan_k` float(2, 0) NOT NULL,
  `meninggal` float(2, 0) NOT NULL,
  `date_k` date NOT NULL,
  PRIMARY KEY (`id_waktu_k`) USING BTREE,
  INDEX `tm_waktu_k_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_waktu_s
-- ----------------------------
DROP TABLE IF EXISTS `tm_waktu_s`;
CREATE TABLE `tm_waktu_s`  (
  `id_waktu_s` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `j_hks` float(2, 0) NOT NULL,
  `j_hkm` float(2, 0) NOT NULL,
  `j_hlp` float(2, 0) NOT NULL,
  `j_hls` float(2, 0) NOT NULL,
  `j_hlm` float(2, 0) NOT NULL,
  `j_hrp` float(2, 0) NOT NULL,
  `j_hrs` float(2, 0) NOT NULL,
  `j_hrm` float(2, 0) NOT NULL,
  `j_ns` float(2, 0) NOT NULL,
  `date_s` date NOT NULL,
  PRIMARY KEY (`id_waktu_s`) USING BTREE,
  INDEX `tm_waktu_s_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tm_waktu_t
-- ----------------------------
DROP TABLE IF EXISTS `tm_waktu_t`;
CREATE TABLE `tm_waktu_t`  (
  `id_waktu_t` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ct_sakit_t` float(2, 0) NOT NULL,
  `ct_alasan_t` float(2, 0) NOT NULL,
  `ct_tahunan_t` float(2, 0) NOT NULL,
  `diklat` float(2, 0) NOT NULL,
  `spd` float(2, 0) NOT NULL,
  `haji` float(2, 0) NOT NULL,
  `date_t` date NOT NULL,
  PRIMARY KEY (`id_waktu_t`) USING BTREE,
  INDEX `tm_waktu_t_ibfk1`(`id_user`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_agenda
-- ----------------------------
DROP TABLE IF EXISTS `tt_agenda`;
CREATE TABLE `tt_agenda`  (
  `id_agenda` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal` date NOT NULL,
  `ruang` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `waktu` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kegiatan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `note` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id_agenda`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_helpdesk
-- ----------------------------
DROP TABLE IF EXISTS `tt_helpdesk`;
CREATE TABLE `tt_helpdesk`  (
  `no_tiket` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis` enum('Pembuatan Sistem','Pengembangan Sistem','Perbaikan Sistem','Perubahan Data Sistem','Perbaikan Hardware','Kerusakan Hardware','Perbaikan Jaringan','Permohonan Akun','Sosialisasi') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `narasi` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `respon` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` enum('Terkirim','Diterima','Menunggu','On Proses','Selesai') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` datetime NOT NULL,
  `nip` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `selesai` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`no_tiket`) USING BTREE,
  INDEX `tt_helpdesk_ibfk1`(`nip`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_hukuman
-- ----------------------------
DROP TABLE IF EXISTS `tt_hukuman`;
CREATE TABLE `tt_hukuman`  (
  `id_hukuman` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_sanksi` int(5) NOT NULL,
  `no_hukuman` varchar(35) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_hukuman` date NULL DEFAULT NULL,
  `aktif_hukuman` date NOT NULL,
  `alasan_hukuman` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id_hukuman`) USING BTREE,
  INDEX `id_user_tm_hukuman_ibfk`(`id_user`) USING BTREE,
  INDEX `id_sanksi_tm_hukuman_ibfk`(`id_sanksi`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_jabatan_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `tt_jabatan_pegawai`;
CREATE TABLE `tt_jabatan_pegawai`  (
  `id_jabatan_pegawai` int(255) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_jabatan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `is_jabatan_utama` int(1) NULL DEFAULT 0 COMMENT '0 : false, 1 : true, hanya ada 1 jabatan utama',
  `status` int(1) NULL DEFAULT 1 COMMENT '0 : inactive, 1 : active',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jabatan_pegawai`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tt_jadwalpegawai_unit_shift
-- ----------------------------
DROP TABLE IF EXISTS `tt_jadwalpegawai_unit_shift`;
CREATE TABLE `tt_jadwalpegawai_unit_shift`  (
  `id_absensi` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_shift.id_absensi',
  `id_unit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_unit.id_unit',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tt_kinerja
-- ----------------------------
DROP TABLE IF EXISTS `tt_kinerja`;
CREATE TABLE `tt_kinerja`  (
  `id_kinerja` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal_kinerja` date NOT NULL,
  `kd_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `uraian` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_akhir` time NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('Utama','Tambahan') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `validasi` enum('Y','T') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'T',
  PRIMARY KEY (`id_kinerja`) USING BTREE,
  INDEX `id_user_tt_kinerja_ibfk`(`id_user`) USING BTREE,
  INDEX `kd_skp_tm_skp_ibfk`(`kd_skp`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_komite_anggota
-- ----------------------------
DROP TABLE IF EXISTS `tt_komite_anggota`;
CREATE TABLE `tt_komite_anggota`  (
  `id_komite_anggota` int(255) NOT NULL AUTO_INCREMENT,
  `id_komite` int(255) NOT NULL COMMENT 'tm_komite.id_komite',
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_kepegawaian` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `full_time` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '0 : Tidak Full Time, 1 : Full Time',
  `active_status` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '0 : Tidak Aktif, 1 : Aktif',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_komite_anggota`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_pph21
-- ----------------------------
DROP TABLE IF EXISTS `tt_pph21`;
CREATE TABLE `tt_pph21`  (
  `id_pph21` int(255) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_keuangan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pph21` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `bulan` int(2) NULL DEFAULT NULL,
  `tahun` int(4) NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pph21`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 101 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_skptahunan
-- ----------------------------
DROP TABLE IF EXISTS `tt_skptahunan`;
CREATE TABLE `tt_skptahunan`  (
  `id_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kd_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kuantitas` int(5) NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  PRIMARY KEY (`id_skp`) USING BTREE,
  INDEX `tt_skptahunan_id_user_ibfk`(`id_user`) USING BTREE,
  INDEX `tt_skptahunan_kd_skp_ibfk`(`kd_skp`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_skptahunan_pjlp
-- ----------------------------
DROP TABLE IF EXISTS `tt_skptahunan_pjlp`;
CREATE TABLE `tt_skptahunan_pjlp`  (
  `id_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kd_skp` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kuantitas` int(5) NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  PRIMARY KEY (`id_skp`) USING BTREE,
  INDEX `tt_skptahunan_id_user_ibfk`(`id_user`) USING BTREE,
  INDEX `tt_skptahunan_kd_skp_ibfk`(`kd_skp`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_slipgaji_log
-- ----------------------------
DROP TABLE IF EXISTS `tt_slipgaji_log`;
CREATE TABLE `tt_slipgaji_log`  (
  `id_slipgaji_log` int(255) NOT NULL AUTO_INCREMENT,
  `id_slipgaji_request` int(255) NOT NULL,
  `id_slipgaji_order` int(1) NOT NULL,
  `tipe_log` int(1) NOT NULL COMMENT '1 : insert, 2 : update, 3 : delete',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_slipgaji_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 775 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_slipgaji_req
-- ----------------------------
DROP TABLE IF EXISTS `tt_slipgaji_req`;
CREATE TABLE `tt_slipgaji_req`  (
  `id_slipgaji_request` int(255) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user',
  `id_penilaian` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_keuangan` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user',
  `id_slipgaji_order` int(1) NULL DEFAULT 1 COMMENT 'tm_slipgaji_order.id_slipgaji_order',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_slipgaji_request`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 627 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_tukarshift
-- ----------------------------
DROP TABLE IF EXISTS `tt_tukarshift`;
CREATE TABLE `tt_tukarshift`  (
  `id_tukarshift` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_sender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user. pemohon',
  `id_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'tm_pegawai.id_user. penerima',
  `id_jadwalkerja_shift_1` int(255) NULL DEFAULT NULL COMMENT 'tm_jadwalpegawai_shift_m.id_jadwalkerja_shift.  akan ditukar',
  `id_jadwalkerja_shift_2` int(255) NULL DEFAULT NULL COMMENT 'tm_jadwalpegawai_shift_m.id_jadwalkerja_shift.  akan ditukar',
  `keperluan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `id_tukarshift_status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT 'tm_tukarshift_order.id_tukarshift_status',
  `created` datetime NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tukarshift`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_tukarshift_log
-- ----------------------------
DROP TABLE IF EXISTS `tt_tukarshift_log`;
CREATE TABLE `tt_tukarshift_log`  (
  `id_tukarshift_log` int(255) NOT NULL AUTO_INCREMENT,
  `id_tukarshift` int(255) NULL DEFAULT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tukarshift_log`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_tukarshift_notification
-- ----------------------------
DROP TABLE IF EXISTS `tt_tukarshift_notification`;
CREATE TABLE `tt_tukarshift_notification`  (
  `id_tukarshift_notification` int(255) NOT NULL AUTO_INCREMENT,
  `id_user_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `valid` int(1) NOT NULL DEFAULT 1 COMMENT '1 : true, 0 : false',
  `read_status` int(1) NOT NULL DEFAULT 0 COMMENT '1 : true, 0 : false',
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `click_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_tukarshift_notification`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for tt_tukarshift_validation
-- ----------------------------
DROP TABLE IF EXISTS `tt_tukarshift_validation`;
CREATE TABLE `tt_tukarshift_validation`  (
  `id_tukarshift_validation` int(255) NOT NULL AUTO_INCREMENT,
  `id_sender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_receiver` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tm_pegawai.id_user',
  `id_tukarshift` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'tt_tukarshift.id_tukarshift',
  `answered` int(1) NOT NULL DEFAULT 0 COMMENT '0 : belum dijawab, 1: diterima, 2: ditolak',
  `notes` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '-',
  `timestamp_request` datetime NULL DEFAULT NULL,
  `timestamp_read` datetime NULL DEFAULT NULL,
  `timestamp_answer` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id_tukarshift_validation`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Procedure structure for updateJadwalPegawaiAbsensiRekap
-- ----------------------------
DROP PROCEDURE IF EXISTS `updateJadwalPegawaiAbsensiRekap`;
delimiter ;;
CREATE PROCEDURE `updateJadwalPegawaiAbsensiRekap`(IN id_users INT,
	IN months INT,
	IN years INT,
	IN id_ketidakhadiran VARCHAR(50),
	IN shift_aktif INT,
	IN absensi_masuk VARCHAR(50),
	IN absensi_pulang VARCHAR(50),
	IN jml_cuti_thnan INT,
	IN jml_sakit_kurang_2hari INT,
	IN jml_libur INT)
BEGIN
		
	IF
		jml_cuti_thnan > 0 THEN
			UPDATE tm_jadwalpegawai_absensi_rekap 
			SET k_jml_alpha = k_jml_alpha - jml_cuti_thnan,
			t_jml_cuti_thnan = t_jml_cuti_thnan + jml_cuti_thnan
		WHERE
			id_user = id_users 
			AND MONTH = months 
			AND YEAR = years;
		
	END IF;
	IF
		jml_sakit_kurang_2hari > 0 THEN
			UPDATE tm_jadwalpegawai_absensi_rekap 
			SET k_jml_alpha = k_jml_alpha - jml_sakit_kurang_2hari,
			k_jml_sakit_1hari = k_jml_sakit_1hari + jml_sakit_kurang_2hari 
		WHERE
			id_user = id_users 
			AND MONTH = months 
			AND YEAR = years;
		
	END IF;
	IF
		jml_libur > 0 THEN
			UPDATE tm_jadwalpegawai_absensi_rekap 
			SET k_jml_alpha = k_jml_alpha - jml_libur 
		WHERE
			id_user = id_users 
			AND MONTH = months 
			AND YEAR = years;
		
	END IF;

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tm_jadwalpegawai_shift_m
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_shift_m_log_inst_copy1_copy1_copy_copy1`;
delimiter ;;
CREATE TRIGGER `insert_shift_m_log_inst_copy1_copy1_copy_copy1` AFTER INSERT ON `tm_jadwalpegawai_shift_m` FOR EACH ROW BEGIN
INSERT INTO tm_jadwalpegawai_shift_m_log (tipe_log, id_unit, id_pj, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, date, submitted, editable) 
VALUES ('1', NEW.id_unit, NEW.id_penanggung_jawab, NEW.id_user, NEW.id_absensi, NEW.id_absensi_tipe, NEW.id_ketidakhadiran, NEW.date, NEW.submitted, NEW.editable);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tm_jadwalpegawai_shift_m
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_shift_m_log_upd_copy1_copy1_copy_copy1`;
delimiter ;;
CREATE TRIGGER `insert_shift_m_log_upd_copy1_copy1_copy_copy1` AFTER UPDATE ON `tm_jadwalpegawai_shift_m` FOR EACH ROW BEGIN
INSERT INTO tm_jadwalpegawai_shift_m_log (tipe_log, id_unit, id_pj, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, date, submitted, editable) 
VALUES ('2', NEW.id_unit, NEW.id_penanggung_jawab, NEW.id_user, NEW.id_absensi, NEW.id_absensi_tipe, NEW.id_ketidakhadiran, NEW.date, NEW.submitted, NEW.editable);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tm_jadwalpegawai_shift_m
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_shift_m_log_del_copy1_copy1_copy_copy1`;
delimiter ;;
CREATE TRIGGER `insert_shift_m_log_del_copy1_copy1_copy_copy1` AFTER DELETE ON `tm_jadwalpegawai_shift_m` FOR EACH ROW BEGIN
INSERT INTO tm_jadwalpegawai_shift_m_log (tipe_log, id_unit, id_pj, id_user, id_absensi, id_absensi_tipe, id_ketidakhadiran, date, submitted, editable) 
VALUES ('3', OLD.id_unit, OLD.id_penanggung_jawab, OLD.id_user, OLD.id_absensi, OLD.id_absensi_tipe, OLD.id_ketidakhadiran, OLD.date, OLD.submitted, OLD.editable);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tt_slipgaji_req
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_tt_slipgaji_log`;
delimiter ;;
CREATE TRIGGER `insert_tt_slipgaji_log` AFTER INSERT ON `tt_slipgaji_req` FOR EACH ROW BEGIN
INSERT INTO tt_slipgaji_log (tipe_log, id_slipgaji_request, id_slipgaji_order)
VALUES ('1', NEW.id_slipgaji_request, NEW.id_slipgaji_order);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tt_slipgaji_req
-- ----------------------------
DROP TRIGGER IF EXISTS `update_tt_slipgaji_log`;
delimiter ;;
CREATE TRIGGER `update_tt_slipgaji_log` AFTER UPDATE ON `tt_slipgaji_req` FOR EACH ROW BEGIN
INSERT INTO tt_slipgaji_log (tipe_log, id_slipgaji_request, id_slipgaji_order)
VALUES ('2', NEW.id_slipgaji_request, NEW.id_slipgaji_order);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tt_slipgaji_req
-- ----------------------------
DROP TRIGGER IF EXISTS `delete_tt_slipgaji_log`;
delimiter ;;
CREATE TRIGGER `delete_tt_slipgaji_log` AFTER DELETE ON `tt_slipgaji_req` FOR EACH ROW BEGIN
INSERT INTO tt_slipgaji_log (tipe_log, id_slipgaji_request, id_slipgaji_order)
VALUES ('3', OLD.id_slipgaji_request, OLD.id_slipgaji_order);
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
