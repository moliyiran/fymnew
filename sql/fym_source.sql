/*
 Navicat Premium Data Transfer

 Source Server         : bd
 Source Server Type    : MySQL
 Source Server Version : 80018
 Source Host           : localhost:3308
 Source Schema         : fym_source

 Target Server Type    : MySQL
 Target Server Version : 80018
 File Encoding         : 65001

 Date: 21/01/2020 16:57:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for source_add_queue
-- ----------------------------
DROP TABLE IF EXISTS `source_add_queue`;
CREATE TABLE `source_add_queue`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `data` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` tinyint(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_bt_1
-- ----------------------------
DROP TABLE IF EXISTS `source_bt_1`;
CREATE TABLE `source_bt_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_ditu_1
-- ----------------------------
DROP TABLE IF EXISTS `source_ditu_1`;
CREATE TABLE `source_ditu_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_hou_1
-- ----------------------------
DROP TABLE IF EXISTS `source_hou_1`;
CREATE TABLE `source_hou_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_img_1
-- ----------------------------
DROP TABLE IF EXISTS `source_img_1`;
CREATE TABLE `source_img_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_juzi2_1
-- ----------------------------
DROP TABLE IF EXISTS `source_juzi2_1`;
CREATE TABLE `source_juzi2_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_juzi_1
-- ----------------------------
DROP TABLE IF EXISTS `source_juzi_1`;
CREATE TABLE `source_juzi_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_keyword_1
-- ----------------------------
DROP TABLE IF EXISTS `source_keyword_1`;
CREATE TABLE `source_keyword_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_lanmu_1
-- ----------------------------
DROP TABLE IF EXISTS `source_lanmu_1`;
CREATE TABLE `source_lanmu_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_moban_1
-- ----------------------------
DROP TABLE IF EXISTS `source_moban_1`;
CREATE TABLE `source_moban_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `type` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '1:index 2:list 3:show',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_pic_1
-- ----------------------------
DROP TABLE IF EXISTS `source_pic_1`;
CREATE TABLE `source_pic_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_valid_table
-- ----------------------------
DROP TABLE IF EXISTS `source_valid_table`;
CREATE TABLE `source_valid_table`  (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `num` int(11) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of source_valid_table
-- ----------------------------
INSERT INTO `source_valid_table` VALUES (1, 'juzi', 1);
INSERT INTO `source_valid_table` VALUES (2, 'bt', 1);
INSERT INTO `source_valid_table` VALUES (3, 'pic', 1);
INSERT INTO `source_valid_table` VALUES (4, 'img', 1);
INSERT INTO `source_valid_table` VALUES (5, 'wzmz', 1);
INSERT INTO `source_valid_table` VALUES (6, 'lanmu', 1);
INSERT INTO `source_valid_table` VALUES (7, 'keyword', 1);
INSERT INTO `source_valid_table` VALUES (8, 'zhon', 1);
INSERT INTO `source_valid_table` VALUES (9, 'hou', 1);
INSERT INTO `source_valid_table` VALUES (10, 'wailian', 1);
INSERT INTO `source_valid_table` VALUES (11, 'moban', 1);
INSERT INTO `source_valid_table` VALUES (12, 'juzi2', 1);
INSERT INTO `source_valid_table` VALUES (13, 'ditu', 1);

-- ----------------------------
-- Table structure for source_wailian_1
-- ----------------------------
DROP TABLE IF EXISTS `source_wailian_1`;
CREATE TABLE `source_wailian_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_wzmz_1
-- ----------------------------
DROP TABLE IF EXISTS `source_wzmz_1`;
CREATE TABLE `source_wzmz_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 149 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source_zhon_1
-- ----------------------------
DROP TABLE IF EXISTS `source_zhon_1`;
CREATE TABLE `source_zhon_1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mkey`(`mkey`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
