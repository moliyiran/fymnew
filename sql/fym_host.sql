/*
 Navicat Premium Data Transfer

 Source Server         : bd
 Source Server Type    : MySQL
 Source Server Version : 80018
 Source Host           : localhost:3308
 Source Schema         : fym_host

 Target Server Type    : MySQL
 Target Server Version : 80018
 File Encoding         : 65001

 Date: 21/01/2020 16:57:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for host_map
-- ----------------------------
DROP TABLE IF EXISTS `host_map`;
CREATE TABLE `host_map`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `url`(`url`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of host_map
-- ----------------------------
INSERT INTO `host_map` VALUES (1, '1', NULL);

-- ----------------------------
-- Table structure for host_valid_table
-- ----------------------------
DROP TABLE IF EXISTS `host_valid_table`;
CREATE TABLE `host_valid_table`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `num` int(11) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tname`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of host_valid_table
-- ----------------------------
INSERT INTO `host_valid_table` VALUES (1, 'visiter_index', 1);

-- ----------------------------
-- Table structure for host_visiter_index_1
-- ----------------------------
DROP TABLE IF EXISTS `host_visiter_index_1`;
CREATE TABLE `host_visiter_index_1`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ukey` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `table_no` int(11) NOT NULL,
  `tid` bigint(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ukey`(`ukey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for host_visiter_samp
-- ----------------------------
DROP TABLE IF EXISTS `host_visiter_samp`;
CREATE TABLE `host_visiter_samp`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ukey` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `table_no` int(11) NOT NULL,
  `tid` bigint(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ukey`(`ukey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
