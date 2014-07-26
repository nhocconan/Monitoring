/*
 Navicat MySQL Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50534
 Source Host           : localhost
 Source Database       : servermetrics

 Target Server Type    : MySQL
 Target Server Version : 50534
 File Encoding         : utf-8

 Date: 07/26/2014 16:44:17 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `a_stats_day`
-- ----------------------------
DROP TABLE IF EXISTS `a_stats_day`;
CREATE TABLE `a_stats_day` (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `string_found` tinyint(1) DEFAULT NULL,
  `connect_time` double DEFAULT NULL,
  `get_time` double DEFAULT NULL,
  `code` smallint(5) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `probe` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `a_stats_hour`
-- ----------------------------
DROP TABLE IF EXISTS `a_stats_hour`;
CREATE TABLE `a_stats_hour` (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `string_found` tinyint(1) DEFAULT NULL,
  `connect_time` double DEFAULT NULL,
  `get_time` double DEFAULT NULL,
  `code` smallint(5) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `probe` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `a_stats_unit`
-- ----------------------------
DROP TABLE IF EXISTS `a_stats_unit`;
CREATE TABLE `a_stats_unit` (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `string_found` tinyint(1) DEFAULT NULL,
  `connect_time` double DEFAULT NULL,
  `get_time` double DEFAULT NULL,
  `code` smallint(5) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `probe` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `applications`
-- ----------------------------
DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `content` text,
  `owner` int(11) NOT NULL,
  `server` int(11) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  `last_probed` datetime DEFAULT NULL,
  `last_probed_from` varchar(3) DEFAULT NULL,
  `friendly_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `brutes`
-- ----------------------------
DROP TABLE IF EXISTS `brutes`;
CREATE TABLE `brutes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `policies`
-- ----------------------------
DROP TABLE IF EXISTS `policies`;
CREATE TABLE `policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `what_to_do` varchar(15) NOT NULL,
  `condition_1` int(11) DEFAULT NULL,
  `operator_1` varchar(3) DEFAULT NULL,
  `condition_2` int(11) DEFAULT NULL,
  `operator_2` varchar(3) DEFAULT NULL,
  `condition_3` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `policy_conditions`
-- ----------------------------
DROP TABLE IF EXISTS `policy_conditions`;
CREATE TABLE `policy_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_id` int(11) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `metric` varchar(255) NOT NULL,
  `operator` varchar(15) NOT NULL,
  `threshold` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `servers`
-- ----------------------------
DROP TABLE IF EXISTS `servers`;
CREATE TABLE `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `monitor_key` varchar(32) DEFAULT NULL,
  `monitor_pass` varchar(32) DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `friendly_name` varchar(70) DEFAULT NULL,
  `load_threshold` tinyint(2) DEFAULT NULL,
  `alert_owner` tinyint(1) DEFAULT NULL,
  `alert_admin` tinyint(1) DEFAULT NULL,
  `type` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `stats_day`
-- ----------------------------
DROP TABLE IF EXISTS `stats_day`;
CREATE TABLE `stats_day` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `mem_used_mb` int(11) NOT NULL,
  `mem_cached_mb` int(11) NOT NULL,
  `mem_free_mb` int(11) NOT NULL,
  `load` decimal(6,2) NOT NULL,
  `disks` text NOT NULL,
  `networks` text NOT NULL,
  `server_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `stats_hour`
-- ----------------------------
DROP TABLE IF EXISTS `stats_hour`;
CREATE TABLE `stats_hour` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `mem_used_mb` int(11) NOT NULL,
  `mem_cached_mb` int(11) NOT NULL,
  `mem_free_mb` int(11) NOT NULL,
  `load` decimal(6,2) NOT NULL,
  `disks` text NOT NULL,
  `networks` text NOT NULL,
  `server_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `stats_unit`
-- ----------------------------
DROP TABLE IF EXISTS `stats_unit`;
CREATE TABLE `stats_unit` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `mem_used_mb` int(11) NOT NULL,
  `mem_cached_mb` int(11) NOT NULL,
  `mem_free_mb` int(11) NOT NULL,
  `load` decimal(6,2) NOT NULL,
  `disks` text NOT NULL,
  `networks` text NOT NULL,
  `server_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `triggers`
-- ----------------------------
DROP TABLE IF EXISTS `triggers`;
CREATE TABLE `triggers` (
  `id` bigint(15) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `alarm_on_timestamp` datetime DEFAULT NULL,
  `alarm_off_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) DEFAULT NULL,
  `email` varchar(70) NOT NULL,
  `password` varchar(70) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` varchar(15) DEFAULT NULL,
  `monitor_servers` smallint(5) DEFAULT NULL,
  `monitor_applications` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `users`
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('1', 'admin', 'admin@nobody.zz', '$2a$12$lfP9eyldDRgKVyUCw670z.5k7Xcj0C/VsWkLOAsu7EGEUHrvvOkgC', '1', null, null, null, null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
