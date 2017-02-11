/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : yurun_blog

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-02-11 17:49:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yb_auth
-- ----------------------------
DROP TABLE IF EXISTS `yb_auth`;
CREATE TABLE `yb_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '权限名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父权限ID；为0则为顶级权限',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_auth
-- ----------------------------

-- ----------------------------
-- Table structure for yb_auth_relation
-- ----------------------------
DROP TABLE IF EXISTS `yb_auth_relation`;
CREATE TABLE `yb_auth_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户组ID；为0则以user_id为准',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID；为0则以group_id为准',
  `auth_id` int(11) NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`user_id`,`auth_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_auth_relation
-- ----------------------------

-- ----------------------------
-- Table structure for yb_category
-- ----------------------------
DROP TABLE IF EXISTS `yb_category`;
CREATE TABLE `yb_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类ID；0-顶级分类',
  `articles` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类文章数量',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `title` varchar(100) NOT NULL COMMENT '在分类页面中显示的标题',
  `keywords` varchar(1024) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `index` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '顺序；可选：0-255 越小越靠前',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示；0-隐藏 1-显示',
  `url` varchar(255) NOT NULL COMMENT '缓存URL地址',
  `alias` varchar(100) NOT NULL COMMENT '别名',
  `template_category` varchar(255) NOT NULL COMMENT '使用的分类模版名称',
  `template_article` varchar(255) NOT NULL COMMENT '分类下默认文章模版名称',
  `navigation_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在导航栏显示；0-隐藏 1-显示',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_category
-- ----------------------------
INSERT INTO `yb_category` VALUES ('1', '0', '0', '软件', '1', '2', '3', '0', '1', 'http://localhost:3333/Admin/Category/list?id=1&parent=0&articles=0&name=%E8%BD%AF%E4%BB%B6&title=1&keywords=2&description=3&index=0&show=1&url=&alias=soft&template_category=list&template_article=view&navigation_show=1', 'soft', 'list', 'view', '1');
INSERT INTO `yb_category` VALUES ('2', '1', '0', '电脑软件', '', '', '', '0', '1', '', 'pcsoft', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('3', '1', '0', '安卓软件', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('4', '0', '0', '技术杂谈', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('5', '4', '0', '编程语言', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('6', '4', '0', '数据库', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('7', '5', '0', 'PHP', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('8', '5', '0', 'C#', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('9', '5', '0', 'C++', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('10', '6', '0', 'MySQL', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('11', '6', '0', 'SQL Server', '', '', '', '0', '1', '', '', 'list', 'view', '0');
INSERT INTO `yb_category` VALUES ('12', '5', '0', '易语言', '', '', '', '0', '1', 'http://localhost:3333/Admin/Category/list?id=12&parent=5&articles=0&name=%E6%98%93%E8%AF%AD%E8%A8%80&title=&keywords=&description=&index=0&show=1&url=&alias=&template_category=list&template_article=view&navigation_show=0', '', 'list', 'view', '0');

-- ----------------------------
-- Table structure for yb_comment
-- ----------------------------
DROP TABLE IF EXISTS `yb_comment`;
CREATE TABLE `yb_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '对应的文章ID',
  `comment_id` int(11) NOT NULL DEFAULT '0' COMMENT '父评论ID；为0则为顶级评论',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '评论状态；0-禁止显示 1-正常显示 2-等待审核',
  `content` text NOT NULL COMMENT '评论内容',
  `name` varchar(32) NOT NULL COMMENT '评论人名称',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  `email` varchar(255) NOT NULL COMMENT '评论人邮箱地址',
  `qq` varchar(11) NOT NULL DEFAULT '' COMMENT '评论人QQ号码',
  `ua` varchar(255) NOT NULL COMMENT '评论者的User Agent',
  `ip` varbinary(16) NOT NULL COMMENT '发布评论的客户端IP（可能是代理地址）',
  `user_ip` varbinary(16) NOT NULL COMMENT '发布评论的用户IP（可能被伪造）',
  `time` datetime NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`comment_id`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_comment
-- ----------------------------

-- ----------------------------
-- Table structure for yb_content
-- ----------------------------
DROP TABLE IF EXISTS `yb_content`;
CREATE TABLE `yb_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL COMMENT '数据表内部内容类型；1-文章 2-单页',
  `content_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '内容类型；1-普通文章 2-草稿 3-定时发布 4-投稿',
  `is_verify` tinyint(4) NOT NULL DEFAULT '0' COMMENT '内容是否通过审核；0-审核不通过 1-通过审核 2-等待审核',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在前台显示；0-隐藏 1-显示',
  `user_id` int(11) NOT NULL COMMENT '发布人ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL COMMENT '内容标题',
  `content` mediumtext NOT NULL COMMENT '内容',
  `summary` text NOT NULL COMMENT '摘要',
  `url` varchar(255) NOT NULL COMMENT '缓存URL地址',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `comments` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `can_comments` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可被评论',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否指定；0-不置顶 1-置顶',
  `alias` varchar(100) NOT NULL COMMENT '别名',
  `template` varchar(255) NOT NULL COMMENT '使用的模版名称',
  `post_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发布时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_content
-- ----------------------------
INSERT INTO `yb_content` VALUES ('1', '0', '1', '0', '1', '0', '1', 'Hello world!', '<p>hehe</p>', '<p>h</p>', '', '0', '0', '1', '0', '', 'view', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `yb_content` VALUES ('2', '0', '1', '0', '1', '0', '1', 'tset', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 14:33:36', '2016-02-16 14:33:36');
INSERT INTO `yb_content` VALUES ('3', '0', '1', '0', '1', '0', '1', '1', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:20:11', '2016-02-16 16:20:11');
INSERT INTO `yb_content` VALUES ('4', '0', '1', '0', '1', '0', '1', '2', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:23:41', '2016-02-16 16:23:41');
INSERT INTO `yb_content` VALUES ('5', '0', '1', '0', '1', '0', '1', '25', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:24:44', '2016-02-16 16:24:44');
INSERT INTO `yb_content` VALUES ('6', '0', '1', '0', '1', '0', '1', '256', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:26:18', '2016-02-16 16:26:18');
INSERT INTO `yb_content` VALUES ('7', '0', '1', '0', '1', '0', '1', '256', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:26:44', '2016-02-16 16:26:44');
INSERT INTO `yb_content` VALUES ('8', '0', '1', '0', '1', '0', '1', '1221212121', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:29:14', '2016-02-16 16:29:14');
INSERT INTO `yb_content` VALUES ('9', '0', '1', '0', '1', '0', '1', 'gdsgds', '', '', '', '0', '0', '1', '0', '', 'view', '2016-02-16 16:29:37', '2016-02-16 16:29:37');
INSERT INTO `yb_content` VALUES ('10', '0', '1', '1', '1', '0', '1', '1', '<p>222</p><p>333</p>', '<p>4</p><p>45</p>', '', '0', '0', '1', '0', '', 'view', '2016-09-24 09:53:48', '2016-09-24 09:53:48');
INSERT INTO `yb_content` VALUES ('11', '0', '1', '1', '1', '0', '1', '1', '<p>222</p><p>333</p>', '<p>4</p><p>45</p>', '', '0', '0', '1', '0', '', 'view', '2016-09-24 09:57:03', '2016-09-24 09:57:03');
INSERT INTO `yb_content` VALUES ('12', '0', '1', '1', '1', '0', '1', '1', '<p>2</p>', '<p>3</p>', '', '0', '0', '1', '0', '', 'view', '2016-09-24 09:57:09', '2016-09-24 09:57:09');

-- ----------------------------
-- Table structure for yb_exdata
-- ----------------------------
DROP TABLE IF EXISTS `yb_exdata`;
CREATE TABLE `yb_exdata` (
  `type` tinyint(3) unsigned NOT NULL COMMENT '扩展数据类型；1-文章 2-单页 3-分类',
  `assoc_pk` int(10) unsigned NOT NULL COMMENT '如果是文章则是文章ID；如果是分类则是分类ID；同理……',
  `key` varchar(255) NOT NULL COMMENT '键',
  `value` mediumtext NOT NULL COMMENT '值',
  PRIMARY KEY (`type`,`assoc_pk`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_exdata
-- ----------------------------
INSERT INTO `yb_exdata` VALUES ('1', '1', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '1', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '2', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '2', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '3', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '3', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '4', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '4', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '5', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '5', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '6', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '6', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '7', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '7', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '8', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '8', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '9', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '9', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '10', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '10', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '11', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '11', 'description', '');
INSERT INTO `yb_exdata` VALUES ('1', '12', 'keywords', '');
INSERT INTO `yb_exdata` VALUES ('1', '12', 'description', '');

-- ----------------------------
-- Table structure for yb_tag
-- ----------------------------
DROP TABLE IF EXISTS `yb_tag`;
CREATE TABLE `yb_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '标签名称',
  `alias` varchar(64) NOT NULL COMMENT '标签别名',
  `articles` int(11) NOT NULL DEFAULT '0' COMMENT '标签文章数量',
  `url` varchar(255) NOT NULL COMMENT '缓存URL地址',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `last_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后使用时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `alias` (`alias`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_tag
-- ----------------------------
INSERT INTO `yb_tag` VALUES ('1', '1', '1', '1', '', '2016-02-16 16:23:41', '2016-02-16 16:29:15');
INSERT INTO `yb_tag` VALUES ('2', '2', '2', '1', '', '2016-02-16 16:23:41', '2016-02-16 16:29:15');
INSERT INTO `yb_tag` VALUES ('3', '3', '3', '1', '', '2016-02-16 16:23:41', '2016-02-16 16:29:15');
INSERT INTO `yb_tag` VALUES ('4', '4', '4', '0', '', '2016-02-16 16:24:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('5', '5', '5', '0', '', '2016-02-16 16:24:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('6', '6', '6', '0', '', '2016-02-16 16:24:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('7', 'a', 'a', '2', '', '2016-02-16 16:26:18', '2016-02-16 16:29:37');
INSERT INTO `yb_tag` VALUES ('8', 'b', 'b', '2', '', '2016-02-16 16:26:18', '2016-02-16 16:29:37');
INSERT INTO `yb_tag` VALUES ('9', 'c', 'c', '2', '', '2016-02-16 16:26:18', '2016-02-16 16:29:37');
INSERT INTO `yb_tag` VALUES ('10', 'd', 'd', '1', '', '2016-02-16 16:26:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('11', 'e', 'e', '1', '', '2016-02-16 16:26:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('12', 'f', 'f', '1', '', '2016-02-16 16:26:44', '0000-00-00 00:00:00');
INSERT INTO `yb_tag` VALUES ('13', '呵呵', '呵呵', '1', '', '2016-02-16 16:29:15', '2016-02-16 16:29:15');

-- ----------------------------
-- Table structure for yb_tag_relation
-- ----------------------------
DROP TABLE IF EXISTS `yb_tag_relation`;
CREATE TABLE `yb_tag_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL COMMENT '标签ID',
  `article_id` int(11) NOT NULL COMMENT '文章ID',
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`,`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_tag_relation
-- ----------------------------
INSERT INTO `yb_tag_relation` VALUES ('1', '10', '7');
INSERT INTO `yb_tag_relation` VALUES ('2', '11', '7');
INSERT INTO `yb_tag_relation` VALUES ('3', '12', '7');
INSERT INTO `yb_tag_relation` VALUES ('4', '13', '8');
INSERT INTO `yb_tag_relation` VALUES ('5', '7', '8');
INSERT INTO `yb_tag_relation` VALUES ('6', '8', '8');
INSERT INTO `yb_tag_relation` VALUES ('7', '9', '8');
INSERT INTO `yb_tag_relation` VALUES ('8', '1', '8');
INSERT INTO `yb_tag_relation` VALUES ('9', '2', '8');
INSERT INTO `yb_tag_relation` VALUES ('10', '3', '8');
INSERT INTO `yb_tag_relation` VALUES ('11', '7', '9');
INSERT INTO `yb_tag_relation` VALUES ('12', '8', '9');
INSERT INTO `yb_tag_relation` VALUES ('13', '9', '9');

-- ----------------------------
-- Table structure for yb_user
-- ----------------------------
DROP TABLE IF EXISTS `yb_user`;
CREATE TABLE `yb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL COMMENT '用作登录的用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `name` varchar(32) NOT NULL COMMENT '姓名/昵称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户状态；0-禁止 1-可用',
  `group` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `phone` varchar(11) NOT NULL COMMENT '手机号码',
  `qq` varchar(11) NOT NULL COMMENT 'QQ号',
  `email` varchar(255) NOT NULL COMMENT '电子邮箱',
  `reg_time` datetime NOT NULL COMMENT '注册时间',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user
-- ----------------------------
INSERT INTO `yb_user` VALUES ('1', 'admin', '9092284ac061d89dab92f6363d1dccb5', '管理员', '1', '1', '', '', '', '2016-02-03 19:37:49', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for yb_user_group
-- ----------------------------
DROP TABLE IF EXISTS `yb_user_group`;
CREATE TABLE `yb_user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL COMMENT '用户组名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yb_user_group
-- ----------------------------
