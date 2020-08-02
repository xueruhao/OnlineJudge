/*
 Navicat Premium Data Transfer

 Source Server         : mysql_win10
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : lduoj

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 2020/07/31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blacklist
-- ----------------------------
DROP TABLE IF EXISTS `blacklist`;
CREATE TABLE `blacklist`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NULL DEFAULT NULL,
    `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for contest_balloons
-- ----------------------------
DROP TABLE IF EXISTS `contest_balloons`;
CREATE TABLE `contest_balloons`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `solution_id` int(11) NULL DEFAULT NULL,
    `sent` tinyint(4) NULL DEFAULT 0,
    `send_time` datetime(0) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for contest_notices
-- ----------------------------
DROP TABLE IF EXISTS `contest_notices`;
CREATE TABLE `contest_notices`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `contest_id` int(11) NULL DEFAULT NULL,
    `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `cid`(`contest_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contest_problems
-- ----------------------------
DROP TABLE IF EXISTS `contest_problems`;
CREATE TABLE `contest_problems`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `contest_id` int(11) NULL DEFAULT NULL,
    `index` int(11) NULL DEFAULT 1001,
    `problem_id` int(11) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `cid`(`contest_id`) USING BTREE,
    INDEX `pid`(`problem_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for contest_users
-- ----------------------------
DROP TABLE IF EXISTS `contest_users`;
CREATE TABLE `contest_users`  (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `contest_id` int(11) NULL DEFAULT NULL,
    `user_id` int(11) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `cid`(`contest_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for contests
-- ----------------------------
DROP TABLE IF EXISTS `contests`;
CREATE TABLE `contests`  (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '竞赛分类',
    `judge_instantly` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否即时判题，否则赛后只判最后一次提交',
    `judge_type` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'acm' COMMENT 'acm,oi',
    `open_discussion` tinyint(4) NULL DEFAULT 1,
    `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `allow_lang` int(11) NULL DEFAULT 0 COMMENT '按位标记允许的提交语言',
    `start_time` datetime(0) NULL DEFAULT NULL,
    `end_time` datetime(0) NULL DEFAULT NULL,
    `lock_rate` float NULL DEFAULT 0 COMMENT '封榜比例，0.00~1.00',
    `access` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'public' COMMENT 'public,password,private',
    `password` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `user_id` int(11) NULL DEFAULT NULL,
    `hidden` tinyint(4) NULL DEFAULT 0,
    `top` int(11) NULL DEFAULT 0 COMMENT '置顶级别',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `stime`(`start_time`) USING BTREE,
    INDEX `etime`(`end_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for discussions
-- ----------------------------
DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `problem_id` int(11) NULL DEFAULT -1,
    `discussion_id` int(11) NULL DEFAULT -1,
    `reply_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `top` int(11) NULL DEFAULT 0,
    `hidden` tinyint(4) NULL DEFAULT 0,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notices
-- ----------------------------
DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices`  (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `state` tinyint(4) NULL DEFAULT 1 COMMENT '0:hidden,1:normal,2:置顶',
    `user_id` int(11) NULL DEFAULT NULL,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for privileges
-- ----------------------------
DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges`  (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `authority` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `uid`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for problems
-- ----------------------------
DROP TABLE IF EXISTS `problems`;
CREATE TABLE `problems`  (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:编程,1:代码填空',
    `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `input` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `fill_in_blank` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '代码填空的完整代码',
    `hint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `spj` tinyint(4) NULL DEFAULT 0,
    `time_limit` int(11) NULL DEFAULT 0 COMMENT 'MS',
    `memory_limit` int(11) NULL DEFAULT 0 COMMENT 'MB',
    `hidden` tinyint(4) NULL DEFAULT 1,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for solutions
-- ----------------------------
DROP TABLE IF EXISTS `solutions`;
CREATE TABLE `solutions`  (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `problem_id` int(11) NOT NULL DEFAULT 0,
    `contest_id` int(11) NULL DEFAULT -1,
    `user_id` int(11) NULL DEFAULT NULL,
    `judge_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'acm,oi,exam',
    `result` tinyint(4) NULL DEFAULT 0,
    `time` int(11) NULL DEFAULT 0 COMMENT 'MS',
    `memory` float NULL DEFAULT 0 COMMENT 'MB',
    `language` tinyint(4) NULL DEFAULT 0,
    `submit_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    `judge_time` datetime(0) NULL DEFAULT NULL,
    `pass_rate` decimal(3, 2) UNSIGNED NULL DEFAULT 0.00,
    `error_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `judger` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `code_length` int(11) NULL DEFAULT 0,
    `code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    `sim_rate` int(11) NULL DEFAULT 0 COMMENT '0~100',
    `sim_sid` int(11) NULL DEFAULT -1,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `uid`(`user_id`) USING BTREE,
    INDEX `pid`(`problem_id`) USING BTREE,
    INDEX `res`(`result`) USING BTREE,
    INDEX `cid`(`contest_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tag_marks
-- ----------------------------
DROP TABLE IF EXISTS `tag_marks`;
CREATE TABLE `tag_marks`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `problem_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `tag_id` int(11) NOT NULL,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for tag_pool
-- ----------------------------
DROP TABLE IF EXISTS `tag_pool`;
CREATE TABLE `tag_pool`  (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `parent_id` int(11) NULL DEFAULT -1,
    `hidden` tinyint(4) NULL DEFAULT 0,
    `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `nick` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `school` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `class` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `revise` int(11) NULL DEFAULT 2,
    `created_at` datetime(0) NULL DEFAULT NULL,
    `updated_at` datetime(0) NULL DEFAULT NULL,
    `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `uname`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
