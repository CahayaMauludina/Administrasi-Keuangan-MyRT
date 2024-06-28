/*
 Navicat Premium Data Transfer

 Source Server         : MySQLLocal
 Source Server Type    : MySQL
 Source Server Version : 80036 (8.0.36)
 Source Host           : localhost:3306
 Source Schema         : myrt

 Target Server Type    : MySQL
 Target Server Version : 80036 (8.0.36)
 File Encoding         : 65001

 Date: 22/06/2024 22:09:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kategori_pemasukan
-- ----------------------------
DROP TABLE IF EXISTS `kategori_pemasukan`;
CREATE TABLE `kategori_pemasukan`  (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_pemasukan
-- ----------------------------
INSERT INTO `kategori_pemasukan` VALUES (1, 'Bulanan');
INSERT INTO `kategori_pemasukan` VALUES (2, 'Harian');

-- ----------------------------
-- Table structure for kategori_pengeluaran
-- ----------------------------
DROP TABLE IF EXISTS `kategori_pengeluaran`;
CREATE TABLE `kategori_pengeluaran`  (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_pengeluaran
-- ----------------------------
INSERT INTO `kategori_pengeluaran` VALUES (1, 'Bulanan');
INSERT INTO `kategori_pengeluaran` VALUES (2, 'Harian');

-- ----------------------------
-- Table structure for pemasukan
-- ----------------------------
DROP TABLE IF EXISTS `pemasukan`;
CREATE TABLE `pemasukan`  (
  `id_pemasukan` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_kategori` int NOT NULL,
  `jumlah` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_pemasukan`) USING BTREE,
  INDEX `fk_id_user_pemasukan`(`id_user` ASC) USING BTREE,
  INDEX `fk_id_kategori_pemasukan`(`id_kategori` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pemasukan
-- ----------------------------
INSERT INTO `pemasukan` VALUES (1, '2024-06-21', 'tes pemasukan', 1, 500000, 1);
INSERT INTO `pemasukan` VALUES (3, '2024-06-22', 'tes pemasukan', 1, 150000, 1);
INSERT INTO `pemasukan` VALUES (4, '2024-05-24', 'tes', 1, 200000, 1);
INSERT INTO `pemasukan` VALUES (5, '2024-06-22', 'tes', 2, 200000, 6);
INSERT INTO `pemasukan` VALUES (6, '2024-06-22', 'tes', 1, 100000, 10);

-- ----------------------------
-- Table structure for pengeluaran
-- ----------------------------
DROP TABLE IF EXISTS `pengeluaran`;
CREATE TABLE `pengeluaran`  (
  `id_pengeluaran` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_kategori` int NOT NULL,
  `jumlah` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_pengeluaran`) USING BTREE,
  INDEX `fk_id_kategori_pengeluaran`(`id_kategori` ASC) USING BTREE,
  INDEX `fk_id_user_pengeluaran`(`id_user` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengeluaran
-- ----------------------------
INSERT INTO `pengeluaran` VALUES (1, '2024-06-21', 'tes pengeluaran', 1, 100000, 1);
INSERT INTO `pengeluaran` VALUES (3, '2024-06-21', 'tes pengeluaran 2', 1, 120000, 1);
INSERT INTO `pengeluaran` VALUES (5, '2024-06-22', 'tes pengeluaran', 1, 200000, 1);
INSERT INTO `pengeluaran` VALUES (6, '2024-05-22', 'tes', 1, 100000, 1);
INSERT INTO `pengeluaran` VALUES (7, '2024-06-22', 'tes', 2, 150000, 6);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'aktif',
  `role` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'bellymandiri@gmail.com', 'Mandiri', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (2, 'admin@gmail.com', 'admin', '$2a$10$u7HAUz2ffOcZ36KPktTfYOXHoRO5V7maYSNqJ1dl5OWmP0LcCYhYq', 'aktif', 'admin');
INSERT INTO `users` VALUES (4, 'eka@gmail.com', 'admin-eka', '$2a$10$u7HAUz2ffOcZ36KPktTfYOXHoRO5V7maYSNqJ1dl5OWmP0LcCYhYq', 'aktif', 'admin');
INSERT INTO `users` VALUES (6, 'bellybca@gmail.com', 'BCA', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (7, 'revanda@gmail.com', 'admin-revanda', '$2a$10$u7HAUz2ffOcZ36KPktTfYOXHoRO5V7maYSNqJ1dl5OWmP0LcCYhYq', 'aktif', 'admin');
INSERT INTO `users` VALUES (10, 'adit@gmail.com', 'Adit', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (11, 'ageng@gmail.com', 'Ageng', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (12, 'hartoyo@gmail.com', 'Hartoyo', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (13, 'johan@gmail.com', 'Johan', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (15, 'cica@gmail.com', 'Cica', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');
INSERT INTO `users` VALUES (16, '', 'Belly', '$2a$10$D.YoxTj3jBmNZA7iYyZKU.IpQR8IZ5cEOdyzP1fpCidloUOSjJkNe', 'aktif', 'user');

SET FOREIGN_KEY_CHECKS = 1;
