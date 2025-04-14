/*
 Navicat Premium Dump SQL

 Source Server         : serverwebsiteabsensi
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : presensi_gps

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 04/03/2025 02:03:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for karyawan
-- ----------------------------
DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE `karyawan`  (
  `nik` char(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `nama_lengkap` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jabatan` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `foto` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode_dept` char(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kode_cabang` char(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_location` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `status_jam_kerja` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`nik`) USING BTREE,
  INDEX `fk_karyawan_cabang`(`kode_cabang` ASC) USING BTREE,
  INDEX `fk_karyawan_dept`(`kode_dept` ASC) USING BTREE,
  CONSTRAINT `fk_karyawan_cabang` FOREIGN KEY (`kode_cabang`) REFERENCES `cabang` (`kode_cabang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_karyawan_dept` FOREIGN KEY (`kode_dept`) REFERENCES `departemen` (`kode_dept`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of karyawan
-- ----------------------------
INSERT INTO `karyawan` VALUES ('bucandy', 'candy alfa agustina,S.Pd', 'Guru matematika', '081847487393', 'bucandy.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$3bdaMN7ktBFCRNEaUhE4eechKlgu7O3aKPK4qJz962CVMQjT159Na', NULL);
INSERT INTO `karyawan` VALUES ('buifa', 'Ifa miftahul rifdah,S.Pd', 'Guru pai dan ips', '08197653488', 'buifa.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$fnho9qE51KD7e/C7.9Nj0.8vyroMOIff5No7xDSygy5SiKCCYlVg2', NULL);
INSERT INTO `karyawan` VALUES ('bulailatul', 'Lailatul fitriyah,S.Fil', 'Guru tik', '086738039740', 'bulailatul.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$0fpv4CslJj37ya.Qea7/M.Pjqc.LgWd/vnRrEM8Nz0nZ6rTxwRave', NULL);
INSERT INTO `karyawan` VALUES ('bulailiyatul', 'lailiyatul fadlilah,S.Pd', 'Guru ips', '081749293824', 'bulailiyatul.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$zeyb30WAJyqQG1/H0QQ6p.Lad9McQi.KySUdvdxakCsJ7Nos.ifKK', NULL);
INSERT INTO `karyawan` VALUES ('burizki', 'rizki aminatut tazkiyah,S.Kep', 'Ipa Terpadu', '0858920848402', 'burizki.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$idvv.f.ALzbbkjsTS5uWO.U9..g.46XhwBU6dIVMNNkVG3DTX92sS', NULL);
INSERT INTO `karyawan` VALUES ('busiti', 'siti maimunah,S.Pd', 'Guru bahasa inggris', '081748273893', 'busiti.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$BUmZ1HvcpT0hNoyF1h9VCeodyzBSmOjbEkIK7OY9jt/q0bpQNAcrS', NULL);
INSERT INTO `karyawan` VALUES ('butauqiyah', 'tauqiyah ridha adfina,S.Pd', 'Guru pai', '0859092847739', 'butauqiyah.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$7Yu/Wo7bfBW60j.b3CCYpO16TFslo5nZzEvfr/sxSKPUTAZw92Ea2', NULL);
INSERT INTO `karyawan` VALUES ('kangbadrit', 'Badrit tamimi', 'Staff tu', '085873939373', 'kangbadrit.jpg', 'TU', 'SMP', '1', '1', '$2y$10$nhAwD/ARR33mLvGMOUE21.9sNWqX5DBzCOuGFz7NxSdVuwQO6B/Vu', NULL);
INSERT INTO `karyawan` VALUES ('Ustadafrizal', 'Afrizal asyari S.Pd.i', 'Guru aswaja', '0859404040040', 'Ustadafrizal.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$caPGSmIiw5wPd2wSA1houu5Gl46XFrhwkYrgZqedV0Jj7AwtOZq2e', NULL);
INSERT INTO `karyawan` VALUES ('Ustadmashuda', 'Mashuda,S.Pd', 'Guru Matematika', '085469738393', 'Ustadmashuda.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$oURZPi5IRBKicjREzjEc8ePtlBn9rzWWn/3PZoJ34Hh.kdPh5cG8O', NULL);
INSERT INTO `karyawan` VALUES ('Ustadnasrudin', 'H.Agus nasrudin,S.Pd. i', 'Guru pkn', '0858990384859', 'Ustadnasrudin.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$7p6Lv0FA8t1fpVFhnubltOnWyI6iDLIw7w0oO8siZabDAc8iF0IQe', NULL);
INSERT INTO `karyawan` VALUES ('Ustadnur', 'Nur rohman,M.Pd', 'guru btq', '8178459378', 'Ustadnur.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$Aw30deJBG6BY7ov5tEh0IO6VVxEesYUaOcRb.CoyprRUxREhGv3Xy', NULL);
INSERT INTO `karyawan` VALUES ('Ustadwahyu', 'Wahyu agung ari susanto.S,Pd', 'Gurubahasaindonesia', '0867588767855', 'Ustadwahyu.jpg', 'GRU', 'SMP', '1', '1', '$2y$10$l7I9x5nx92M88S6BzW8B5e7D4NKxSv8e4fPv8v7xZqI3iJjxHGjGW', NULL);

SET FOREIGN_KEY_CHECKS = 1;
