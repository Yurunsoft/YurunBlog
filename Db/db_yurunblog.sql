/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : db_yurunblog

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-02-11 17:49:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yb_category
-- ----------------------------
DROP TABLE IF EXISTS `yb_category`;
CREATE TABLE `yb_category` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类ID；0-顶级分类',
  `Articles` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类文章数量',
  `Name` varchar(128) NOT NULL DEFAULT '' COMMENT '分类名称',
  `Title` varchar(128) NOT NULL COMMENT '在分类页面中显示的标题',
  `Keywords` varchar(1024) NOT NULL,
  `Description` varchar(1024) NOT NULL,
  `Index` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序；可选：0-255 越小越靠前',
  `IsShow` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否显示',
  `Url` varchar(255) NOT NULL COMMENT '缓存URL地址',
  `Alias` varchar(128) NOT NULL COMMENT '别名',
  `CategoryTemplate` varchar(255) NOT NULL COMMENT '使用的分类模版名称',
  `ArticleTemplate` varchar(255) NOT NULL COMMENT '分类下默认文章模版名称',
  `NavigationShow` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否在导航栏显示；0-隐藏 1-显示',
  `Level` int(11) NOT NULL DEFAULT '0' COMMENT '层级',
  PRIMARY KEY (`ID`),
  KEY `alias` (`Alias`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_category
-- ----------------------------
INSERT INTO `yb_category` VALUES ('1', '0', '0', '软件', '1', '2', '3', '0', '', 'http://localhost:3333/Admin/Category/list?id=1&parent=0&articles=0&name=%E8%BD%AF%E4%BB%B6&title=1&keywords=2&description=3&index=0&show=1&url=&alias=soft&template_category=list&template_article=view&navigation_show=1', 'soft', 'list', 'view', '', '0');
INSERT INTO `yb_category` VALUES ('2', '1', '0', '电脑软件', '', '', '', '0', '', '', 'pcsoft', 'list', 'view', '\0', '1');
INSERT INTO `yb_category` VALUES ('3', '1', '0', '安卓软件', '', '', '', '0', '', '', '', 'list', 'view', '\0', '1');
INSERT INTO `yb_category` VALUES ('4', '0', '0', '技术杂谈', '', '', '', '0', '', '', '', 'list', 'view', '\0', '0');
INSERT INTO `yb_category` VALUES ('5', '4', '0', '编程语言', '', '', '', '0', '', '', '', 'list', 'view', '\0', '1');
INSERT INTO `yb_category` VALUES ('6', '4', '0', '数据库', '', '', '', '0', '', '', '', 'list', 'view', '\0', '1');
INSERT INTO `yb_category` VALUES ('7', '5', '0', 'PHP', '', '', '', '0', '', '', '', 'list', 'view', '\0', '3');
INSERT INTO `yb_category` VALUES ('8', '5', '0', 'C#', '', '', '', '0', '', '', '', 'list', 'view', '\0', '3');
INSERT INTO `yb_category` VALUES ('9', '5', '0', 'C++', '', '', '', '0', '', '', '', 'list', 'view', '\0', '3');
INSERT INTO `yb_category` VALUES ('10', '6', '0', 'MySQL', '', '', '', '0', '', '', '', 'list', 'view', '\0', '3');
INSERT INTO `yb_category` VALUES ('11', '6', '0', 'SQL Server', '', '', '', '0', '', '', '', 'list', 'view', '\0', '3');
INSERT INTO `yb_category` VALUES ('12', '5', '0', '易语言', '', '', '', '0', '', 'http://localhost:3333/Admin/Category/list?id=12&parent=5&articles=0&name=%E6%98%93%E8%AF%AD%E8%A8%80&title=&keywords=&description=&index=0&show=1&url=&alias=&template_category=list&template_article=view&navigation_show=0', '', 'list', 'view', '\0', '3');

-- ----------------------------
-- Table structure for yb_dict
-- ----------------------------
DROP TABLE IF EXISTS `yb_dict`;
CREATE TABLE `yb_dict` (
  `Name` varchar(64) NOT NULL,
  `Type` varchar(64) NOT NULL DEFAULT '',
  `Text` varchar(64) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_dict
-- ----------------------------
INSERT INTO `yb_dict` VALUES ('TAG_TYPE', '', '标签类型', '');

-- ----------------------------
-- Table structure for yb_user
-- ----------------------------
DROP TABLE IF EXISTS `yb_user`;
CREATE TABLE `yb_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `LevelID` tinyint(4) NOT NULL COMMENT '级别ID',
  `Username` varchar(32) NOT NULL COMMENT '用户名',
  `Name` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user
-- ----------------------------
INSERT INTO `yb_user` VALUES ('1', '1', 'admin', '管理员', '85be93f7f51fce6dd0c0d702e98c86c8');

-- ----------------------------
-- Table structure for yb_user_level
-- ----------------------------
DROP TABLE IF EXISTS `yb_user_level`;
CREATE TABLE `yb_user_level` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user_level
-- ----------------------------
INSERT INTO `yb_user_level` VALUES ('1', '管理员');
INSERT INTO `yb_user_level` VALUES ('2', '普通用户');
